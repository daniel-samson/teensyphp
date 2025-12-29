<?php

use PHPUnit\Framework\TestCase;
use TeensyPHP\OldCommand\ShowHelp;

require_once __DIR__ . '/../src/stop.php';

class ShowHelpTest extends TestCase
{
    protected function setUp(): void
    {
        unset($GLOBALS["TEENSYPHP_STOP_CODE"]);
    }

    protected function tearDown(): void
    {
        unset($GLOBALS["TEENSYPHP_STOP_CODE"]);
    }

    public function test_invoke_displays_help_message()
    {
        $showHelp = new ShowHelp();

        ob_start();
        $showHelp->__invoke();
        $output = ob_get_clean();

        $this->assertStringContainsString("Usage: teensyphp command [options]", $output);
        $this->assertStringContainsString("Commands:", $output);
        $this->assertStringContainsString("new\t\t Create a new project", $output);
        $this->assertStringContainsString("Options:", $output);
        $this->assertStringContainsString("-h, --help\t Display this help message", $output);
    }

    public function test_invoke_calls_stop_function()
    {
        $showHelp = new ShowHelp();

        ob_start();
        $result = $showHelp->__invoke();
        ob_end_clean();

        $this->assertEquals(0, $GLOBALS["TEENSYPHP_STOP_CODE"]);
    }

    public function test_help_output_format()
    {
        $showHelp = new ShowHelp();

        ob_start();
        $showHelp->__invoke();
        $output = ob_get_clean();

        $lines = explode("\n", $output);

        $this->assertGreaterThanOrEqual(5, count($lines));

        $this->assertEquals("Usage: teensyphp command [options]", $lines[0]);
        $this->assertEquals("Commands:", $lines[1]);
        $this->assertStringContainsString("new", $lines[2]);
        $this->assertEquals("Options:", $lines[3]);
        $this->assertStringContainsString("-h, --help", $lines[4]);
    }

    public function test_command_instantiation()
    {
        $showHelp = new ShowHelp();
        $this->assertInstanceOf(ShowHelp::class, $showHelp);
    }
}