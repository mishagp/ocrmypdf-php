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
    public function testCheckWritePermissions(): void
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            $this->markTestSkipped('OCRmyPDFTest::testCheckWritePermissions unimplemented on Windows-based platforms, skipping.');
        }
        $this->expectException(NoWritePermissionsException::class);
        OCRmyPDF::checkWritePermissions("/dev/null");
    }

    public function testCheckFilePath(): void
    {
        $this->expectException(FileNotFoundException::class);
        OCRmyPDF::checkFilePath(substr(md5((string)rand()), 0, 20));
    }

    /**
     * @throws OCRmyPDFNotFoundException
     */
    public function testSetExecutable(): void
    {
        $instance = new OCRmyPDF();
        $this->assertInstanceOf(OCRmyPDF::class, $instance->setExecutable("ocrmypdf"));
    }
}
