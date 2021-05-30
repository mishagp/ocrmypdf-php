<?php

namespace mishagp\OCRmyPDF\Tests\E2E;

use mishagp\OCRmyPDF\NoWritePermissionsException;
use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFException;
use mishagp\OCRmyPDF\UnsuccessfulCommandException;
use PHPUnit\Framework\TestCase;

class OCRmyPDFGeneratesOutputFileTest extends TestCase
{
    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc1_NoParameters()
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc1.pdf";
        $instance = new OCRmyPDF($inputFile);
        $outputPath = $instance->run();
        $this->assertFileExists($outputPath);
        $this->assertFileIsReadable($outputPath);
        $this->assertFileIsWritable($outputPath);
        echo "Output: $outputPath";
    }

    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc2_NoParameters()
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc2.pdf";
        $instance = new OCRmyPDF($inputFile);
        $outputPath = $instance->run();
        $this->assertFileExists($outputPath);
        $this->assertFileIsReadable($outputPath);
        $this->assertFileIsWritable($outputPath);
        echo "Output: $outputPath";
    }

    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc3_NoParameters()
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc3.pdf";
        $instance = new OCRmyPDF($inputFile);
        $outputPath = $instance->run();
        $this->assertFileExists($outputPath);
        $this->assertFileIsReadable($outputPath);
        $this->assertFileIsWritable($outputPath);
        echo "Output: $outputPath";
    }

    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_img1_NoParameters()
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_img1.png";
        $instance = new OCRmyPDF($inputFile);
        $outputPath = $instance->run();
        $this->assertFileExists($outputPath);
        $this->assertFileIsReadable($outputPath);
        $this->assertFileIsWritable($outputPath);
        echo "Output: $outputPath";
    }

    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc1_SetOutputPDFManually()
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc1.pdf";
        $instance = new OCRmyPDF($inputFile);
        $instance->setOutputPDFPath(sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename(tempnam(sys_get_temp_dir(), 'ocr_')) . ".pdf");
        $outputPath = $instance->run();
        $this->assertFileExists($outputPath);
        $this->assertFileIsReadable($outputPath);
        $this->assertFileIsWritable($outputPath);
        echo "Output: $outputPath";
    }
}
