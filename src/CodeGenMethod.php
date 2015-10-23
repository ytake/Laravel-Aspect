<?php

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 * Copyright (c) 2015 Yuuki Takezawa
 *
 *
 * CodeGenMethod Class, CodeGen Class is:
 * Copyright (c) 2012-2015, The Ray Project for PHP
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

namespace Ytake\LaravelAspect;

use PHPParser\Builder\Method;
use PhpParser\Builder\Param;
use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use Ray\Aop\BindInterface;

/**
 * Class CodeGenMethod
 */
final class CodeGenMethod
{
    /**
     * @var \PHPParser\Parser
     */
    private $parser;

    /**
     * @var \PHPParser\BuilderFactory
     */
    private $factory;

    /**
     * @var \PHPParser\PrettyPrinter\Standard
     */
    private $printer;

    /**
     * @param \PHPParser\Parser                 $parser
     * @param \PHPParser\BuilderFactory         $factory
     * @param \PHPParser\PrettyPrinter\Standard $printer
     */
    public function __construct(
        Parser $parser,
        BuilderFactory $factory,
        Standard $printer
    ) {
        $this->parser = $parser;
        $this->factory = $factory;
        $this->printer = $printer;
    }

    /**
     * @param \ReflectionClass $class
     * @param BindInterface    $bind
     *
     * @return array
     */
    public function getMethods(\ReflectionClass $class, BindInterface $bind)
    {
        $bindingMethods = array_keys($bind->getBindings());
        $stmts = [];
        $methods = $class->getMethods();
        foreach ($methods as $method) {
            $isBindingMethod = in_array($method->getName(), $bindingMethods);
            /* @var $method \ReflectionMethod */
            if ($isBindingMethod && $method->isPublic()) {
                $stmts[] = $this->getMethod($method);
            }
        }

        return $stmts;
    }

    /**
     * Return method statement
     *
     * @param \ReflectionMethod $method
     *
     * @return \PhpParser\Node\Stmt\ClassMethod
     */
    private function getMethod(\ReflectionMethod $method)
    {
        $methodStmt = $this->factory->method($method->name);
        $params = $method->getParameters();
        foreach ($params as $param) {
            $methodStmt = $this->getMethodStatement($param, $methodStmt);
        }
        $methodInsideStatements = $this->getMethodInsideStatement();
        $methodStmt->addStmts($methodInsideStatements);
        $node = $this->addMethodDocComment($methodStmt, $method);

        return $node;
    }

    /**
     * Return parameter reflection
     *
     * @param \ReflectionParameter      $param
     * @param \PHPParser\Builder\Method $methodStmt
     *
     * @return \PHPParser\Builder\Method
     */
    private function getMethodStatement(\ReflectionParameter $param, Method $methodStmt)
    {
        /** @var $paramStmt Param */
        $paramStmt = $this->factory->param($param->name);
        /* @var $param \ReflectionParameter */
        $typeHint = $param->getClass();
        $this->setTypeHint($param, $paramStmt, $typeHint);
        $this->setDefault($param, $paramStmt);
        $methodStmt->addParam($paramStmt);

        return $methodStmt;
    }

    /**
     * @param Method            $methodStmt
     * @param \ReflectionMethod $method
     *
     * @return \PhpParser\Node\Stmt\ClassMethod
     */
    private function addMethodDocComment(Method $methodStmt, \ReflectionMethod $method)
    {
        $node = $methodStmt->getNode();
        $docComment = $method->getDocComment();
        if ($docComment) {
            $node->setAttribute('comments', [new Doc($docComment)]);
        }

        return $node;
    }

    /**
     * @return \PHPParser\Node[]
     */
    private function getMethodInsideStatement()
    {
        // fixed call parent method
        $code = file_get_contents(__DIR__ . '/data/CodeTemplate.php');
        $node = $this->parser->parse($code)[0];
        /** @var $node \PHPParser\Node\Stmt\Class_ */
        $node = $node->getMethods()[0];

        return $node->stmts;
    }

    /**
     * @param \ReflectionParameter $param
     * @param Param                $paramStmt
     * @param \ReflectionClass     $typeHint
     */
    private function setTypeHint(\ReflectionParameter $param, Param $paramStmt, \ReflectionClass $typeHint = null)
    {
        if ($typeHint) {
            $paramStmt->setTypeHint($typeHint->name);
        }
        if ($param->isArray()) {
            $paramStmt->setTypeHint('array');
        }
        if ($param->isCallable()) {
            $paramStmt->setTypeHint('callable');
        }
    }

    /**
     * @param \ReflectionParameter $param
     * @param Param                $paramStmt
     */
    private function setDefault(\ReflectionParameter $param, $paramStmt)
    {
        if ($param->isDefaultValueAvailable()) {
            $paramStmt->setDefault($param->getDefaultValue());
        }
    }
}
