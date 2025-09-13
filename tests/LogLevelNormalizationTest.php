<?php

use Monolog\Level;
use Ytake\LaravelAspect\Interceptor\AbstractLogger;

/**
 * normalizeLogLevelメソッドをテストするためのテストクラス
 */
class LogLevelNormalizationTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testNormalizeLogLevelWithIntegerValues()
    {
        $logger = new TestableAbstractLogger();
        
        // 整数値はそのまま返される
        $this->assertSame(100, $logger->testNormalizeLogLevel(100)); // Debug
        $this->assertSame(200, $logger->testNormalizeLogLevel(200)); // Info
        $this->assertSame(250, $logger->testNormalizeLogLevel(250)); // Notice
        $this->assertSame(300, $logger->testNormalizeLogLevel(300)); // Warning
        $this->assertSame(400, $logger->testNormalizeLogLevel(400)); // Error
        $this->assertSame(500, $logger->testNormalizeLogLevel(500)); // Critical
        $this->assertSame(550, $logger->testNormalizeLogLevel(550)); // Alert
        $this->assertSame(600, $logger->testNormalizeLogLevel(600)); // Emergency
    }

    public function testNormalizeLogLevelWithStringValues()
    {
        $logger = new TestableAbstractLogger();
        
        // 文字列値はLevel::fromName()で適切な整数値に変換される
        $this->assertSame(100, $logger->testNormalizeLogLevel('debug'));
        $this->assertSame(200, $logger->testNormalizeLogLevel('info'));
        $this->assertSame(250, $logger->testNormalizeLogLevel('notice'));
        $this->assertSame(300, $logger->testNormalizeLogLevel('warning'));
        $this->assertSame(400, $logger->testNormalizeLogLevel('error'));
        $this->assertSame(500, $logger->testNormalizeLogLevel('critical'));
        $this->assertSame(550, $logger->testNormalizeLogLevel('alert'));
        $this->assertSame(600, $logger->testNormalizeLogLevel('emergency'));
    }

    public function testNormalizeLogLevelWithLevelEnum()
    {
        $logger = new TestableAbstractLogger();
        
        // Level enumはvalueプロパティを返す
        $this->assertSame(100, $logger->testNormalizeLogLevel(Level::Debug));
        $this->assertSame(200, $logger->testNormalizeLogLevel(Level::Info));
        $this->assertSame(250, $logger->testNormalizeLogLevel(Level::Notice));
        $this->assertSame(300, $logger->testNormalizeLogLevel(Level::Warning));
        $this->assertSame(400, $logger->testNormalizeLogLevel(Level::Error));
        $this->assertSame(500, $logger->testNormalizeLogLevel(Level::Critical));
        $this->assertSame(550, $logger->testNormalizeLogLevel(Level::Alert));
        $this->assertSame(600, $logger->testNormalizeLogLevel(Level::Emergency));
    }

    public function testNormalizeLogLevelWithCaseInsensitiveStrings()
    {
        $logger = new TestableAbstractLogger();
        
        // 大文字小文字を問わず正しく変換される
        $this->assertSame(200, $logger->testNormalizeLogLevel('INFO'));
        $this->assertSame(200, $logger->testNormalizeLogLevel('Info'));
        $this->assertSame(400, $logger->testNormalizeLogLevel('ERROR'));
        $this->assertSame(400, $logger->testNormalizeLogLevel('Error'));
    }

    public function testNormalizeLogLevelWithInvalidInput()
    {
        $logger = new TestableAbstractLogger();
        
        // 無効な入力の場合はデフォルト値（Info）を返す
        $this->assertSame(200, $logger->testNormalizeLogLevel(null));
        $this->assertSame(200, $logger->testNormalizeLogLevel([]));
        $this->assertSame(200, $logger->testNormalizeLogLevel(new \stdClass()));
    }

    public function testNormalizeLogLevelWithUnknownString()
    {
        $logger = new TestableAbstractLogger();
        
        // 未知の文字列の場合は例外が発生することを確認
        $this->expectException(\UnhandledMatchError::class);
        $logger->testNormalizeLogLevel('unknown');
    }
}

/**
 * AbstractLoggerのテスト用サブクラス
 */
class TestableAbstractLogger extends AbstractLogger
{
    /**
     * normalizeLogLevelメソッドをテスト可能にするためのパブリックラッパー
     */
    public function testNormalizeLogLevel($level): int
    {
        return $this->normalizeLogLevel($level);
    }
}