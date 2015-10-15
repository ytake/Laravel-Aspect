<?php
/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * @author  yuuki.takezawa<yuuki.takezawa@comnect.jp.net>
 * @license http://opensource.org/licenses/MIT MIT
 */
return [

    /**
     * choose aop library
     * "go"(go-aop), "none"(for testing)
     *
     * @see https://github.com/goaop/framework
     */
    'default' => 'go',
    /**
     *
     */
    'aop' => [
        'go' => [
            // boolean Determines whether or not kernel is in debug mode
            'debug' => true,
            // string Path to the application root directory.
            'appDir' => __DIR__ . '/../src',
            // string Path to the cache directory where compiled classes will be stored
            'cacheDir' => __DIR__ . '/../storage',
            // integer Binary mask of features
            // 'features' => 0,
            // array WhiteList of directories where aspects should be applied. Empty for everywhere.
            'includePaths' => [
                __DIR__ . '/../src',
            ],
            // array BlackList of directories or files where aspects shouldn't be applied.
            // 'excludePaths' => []
        ],
        'none' => [
            // for testing driver
            // no use aspect
        ]
    ],
];
