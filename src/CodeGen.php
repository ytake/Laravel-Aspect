<?php

/**
 * CodeGenMethod Class, CodeGen Class is:
 * Copyright (c) 2012-2015, The Ray Project for PHP
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */

namespace Ytake\LaravelAspect;

use PhpParser\BuilderFactory;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use Ray\Aop\CodeGenInterface;
use Ray\Aop\BindInterface;
use Ray\Aop\CodeGenVisitor;

/**
 * Class CodeGen
 */
final class CodeGen implements CodeGenInterface
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
     * @var CodeGenMethod
     */
    private $codeGenMethod;

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
        $this->codeGenMethod = new CodeGenMethod($parser, $factory, $printer);
    }

    /**
     * @param string           $class
     * @param \ReflectionClass $sourceClass
     *
     * @return string
     */
    public function generate($class, \ReflectionClass $sourceClass, BindInterface $bind)
    {
        $methods = $this->codeGenMethod->getMethods($sourceClass, $bind);
        $stmt = $this
            ->getClass($class, $sourceClass)
            ->addStmts($methods)
            ->getNode();
        $stmt = $this->addClassDocComment($stmt, $sourceClass);
        $code = $this->printer->prettyPrint([$stmt]);
        $statements = $this->getUseStatements($sourceClass);

        return $statements . $code;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return string
     */
    private function getUseStatements(\ReflectionClass $class)
    {
        $traverser = new NodeTraverser();
        $useStmtsVisitor = new CodeGenVisitor();
        $traverser->addVisitor($useStmtsVisitor);
        // parse
        $stmts = $this->parser->parse(file_get_contents($class->getFileName()));
        // traverse
        $traverser->traverse($stmts);
        // pretty print
        $code = $this->printer->prettyPrint($useStmtsVisitor());

        return (string)$code;
    }

    /**
     * Return class statement
     *
     * @param string           $newClassName
     * @param \ReflectionClass $class
     *
     * @return \PhpParser\Builder\Class_
     */
    private function getClass($newClassName, \ReflectionClass $class)
    {
        $parentClass = $class->name;
        $builder = $this->factory
            ->class($newClassName)
            ->extend($parentClass)
            ->implement('Ray\Aop\WeavedInterface')
            ->addStmt(
                $this->factory->property('isIntercepting')->makePrivate()->setDefault(true)
            )->addStmt(
                $this->factory->property('bind')->makePublic()
            );

        return $builder;
    }

    /**
     * Add class doc comment
     *
     * @param Class_           $node
     * @param \ReflectionClass $class
     *
     * @return \PHPParser\Node\Stmt\Class_
     */
    private function addClassDocComment(Class_ $node, \ReflectionClass $class)
    {
        $docComment = $class->getDocComment();
        if ($docComment) {
            $node->setAttribute('comments', [new Doc($docComment)]);
        }

        return $node;
    }
}
