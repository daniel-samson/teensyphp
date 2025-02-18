<?php

use PHPUnit\Framework\TestCase;
use TeensyPHP\Exceptions\TeensyPHPException;
class TeensyPHPExceptionTest extends TestCase
{
    public function test_exception()
    {
        $this->expectException(TeensyPHPException::class);
        throw new TeensyPHPException("test");
    }

    public function test_exception_with_code()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(100);
        throw new TeensyPHPException("test", 100);
    }

    public function test_exception_with_code_and_message()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(100);
        $this->expectExceptionMessage("test");
        throw new TeensyPHPException("test", 100);
    }

    public function test_exception_with_message()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionMessage("test");
        throw new TeensyPHPException("test");
    }


    public function test_internal_server_error()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionMessage("Internal Server Error");
        $this->expectExceptionCode(500);
        throw new TeensyPHPException();
    }

    public function test_exception_not_found()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionMessage("Not Found");
        $this->expectExceptionCode(404);
        TeensyPHPException::throwNotFound();
    }

    public function test_exception_unauthorized()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionMessage("Unauthorized");
        $this->expectExceptionCode(401);
        TeensyPHPException::throwUnauthorized();
    }

    public function test_exception_bad_request()
    {
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionMessage("Bad Request");
        $this->expectExceptionCode(400);
        TeensyPHPException::throwBadRequest();
    }

    public function test_exception_pretty_trace()
    {
        $exception = new TeensyPHPException("test", 100);
        $this->assertStringNotContainsString("vendor", $exception->getPrettyTraceAsString());
    }

    public function test_exception_to_string()
    {
        $exception = new TeensyPHPException("test", 100);
        $this->assertStringContainsString("test", $exception->__toString());
    }
}