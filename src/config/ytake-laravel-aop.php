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

return [

    'aspect' => [
        /**
         * choose aop library
         * "ray"(Ray.Aop), "none"(for testing)
         */
        'default' => env('ASPECT_DRIVER', 'ray'),

        /**
         *
         */
        'drivers'     => [
            'ray'  => [
                // string Path to the cache directory where compiled classes will be stored
                'compile_dir' => storage_path('framework/aop/compile'),

                'cache' => env('ASPECT_CACHEABLE', false),

                'cache_dir' => storage_path('framework/aop/cache'),
            ],
            'none' => [
                // for testing driver
                // no use aspect
            ]
        ],
    ],

    'annotation' => [
        /**
         * choose annotation reader
         * 'array'(default), 'file'(file cache)
         */
        'default' => env('ASPECT_ANNOTATION_DRIVER', 'array'),

        'drivers' => [
            'file' => [
                'cache_dir' => storage_path('framework/annotation'),
                //
                'debug' => env('ASPECT_ANNOTATION_DEBUG', true),
            ],
        ],
    ],
];
