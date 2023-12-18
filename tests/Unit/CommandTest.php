<?php

namespace mishagp\OCRmyPDF\Tests\Unit;

use mishagp\OCRmyPDF\Command;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{

    public function test__toString(): void
    {
        $this->markTestSkipped('CommandTest::test__toString unimplemented, skipping.');
    }

    public function test__construct(): void
    {
        $this->markTestSkipped('CommandTest::test__construct unimplemented, skipping.');
    }

    public function testEscape(): void
    {
        $this->markTestSkipped('CommandTest::testEscape unimplemented, skipping.');
    }

    public function testCheckCommandExecution(): void
    {
        $this->markTestSkipped('CommandTest::testCheckCommandExecution unimplemented, skipping.');
    }

    public function testGetOutputPDFPath(): void
    {
        $this->markTestSkipped('CommandTest::testGetOutputPDFPath unimplemented, skipping.');
    }

    public function testGetOCRmyPDFVersion(): void
    {
        $version = (new Command())->getOCRmyPDFVersion();
        $this->assertNotEmpty($version);
    }

    public function testGetTempDirDefaultTempDirectory(): void
    {
        $command = new Command();
        $this->assertEquals(sys_get_temp_dir(), $command->getTempDir());
    }

    public function testGetTempDirCustomTempDirectory(): void
    {
        $customTempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . rand(100000, 999999);
        $command = new Command(null, null, $customTempDir);
        $this->assertEquals($customTempDir, $command->getTempDir());
    }
}
