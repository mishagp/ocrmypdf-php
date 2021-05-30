<?php

namespace mishagp\OCRmyPDF\Tests\Unit;

use mishagp\OCRmyPDF\FileNotFoundException;
use mishagp\OCRmyPDF\NoWritePermissionsException;
use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFNotFoundException;
use PHPUnit\Framework\TestCase;

class OCRmyPDFTest extends TestCase
{

    /**
     * @throws NoWritePermissionsException
     */
    public function testCheckWritePermissions()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $this->markTestSkipped('OCRmyPDFTest::testCheckWritePermissions unimplemented on Windows-based platforms, skipping.');
        }
        $this->expectException(NoWritePermissionsException::class);
        OCRmyPDF::checkWritePermissions("/dev/null");
    }

    public function testRun()
    {
        $this->markTestSkipped('OCRmyPDFTest::testRun unimplemented, skipping.');
    }

    public function testSetInputData()
    {
        $this->markTestSkipped('OCRmyPDFTest::testSetInputData unimplemented, skipping.');
    }

    public function test__construct()
    {
        $this->markTestSkipped('OCRmyPDFTest::test__construct unimplemented, skipping.');
    }

    public function testSetInputFile()
    {
        $this->markTestSkipped('OCRmyPDFTest::testSetInputFile unimplemented, skipping.');
    }

    public function testCheckOCRmyPDFPresence()
    {
        $this->markTestSkipped('OCRmyPDFTest::testCheckOCRmyPDFPresence unimplemented, skipping.');
    }

    public function testCheckFilePath()
    {
        $this->expectException(FileNotFoundException::class);
        OCRmyPDF::checkFilePath(substr(md5(rand()), 0, 20));
    }

    /**
     * @throws OCRmyPDFNotFoundException
     */
    public function testSetExecutable()
    {
        $instance = new OCRmyPDF();
        $this->assertInstanceOf(OCRmyPDF::class, $instance->setExecutable("ocrmypdf"));
    }
}
