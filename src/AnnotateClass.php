<?php
declare(strict_types=1);

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
 *
 * Copyright (c) 2015-2020 Yuuki Takezawa
 *
 */

namespace Bssd\LaravelAspect;

use Ray\Aop\WeavedInterface;
use Bssd\LaravelAspect\Annotation\PostConstruct;

use function is_array;
use function unserialize;

/**
 * Class AnnotateClass
 */
final class AnnotateClass
{
    /**
     * @param WeavedInterface $weavedInstance
     *
     * @return string
     */
    public function getPostConstructMethod(WeavedInterface $weavedInstance): string
    {
        $methods = unserialize($weavedInstance->methodAnnotations);
        if (!is_array($methods)) {
            return '';
        }
        foreach ($methods as $method => $annotations) {
            foreach ($annotations as $annotation) {
                if ($annotation instanceof PostConstruct) {
                    return $method;
                }
            }
        }

        return '';
    }
}
