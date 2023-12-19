<?php

namespace mishagp\OCRmyPDF\Tests\E2E;

use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFException;
use mishagp\OCRmyPDF\UnsuccessfulCommandException;
use PHPUnit\Framework\TestCase;

class OCRmyPDFParsesParametersTest extends TestCase
{
    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc1_SetTitleParam(): void
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc1.pdf";
        $outputPath = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            basename((string)tempnam(sys_get_temp_dir(), 'ocr_')) .
            ".pdf";

        $instance = OCRmyPDF::make($inputFile)
            ->setOutputPDFPath($outputPath)
            ->setParam('--title', "Lorem Ipsum");

        $outputPath = $instance->run();
        $this->assertFileExists($outputPath);
        $this->assertFileIsReadable($outputPath);
        $this->assertFileIsWritable($outputPath);
        echo "Output: $outputPath";
    }

    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc1_SetInvalidParam(): void
    {
        $this->expectException(UnsuccessfulCommandException::class);

        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc1.pdf";
        $outputPath = sys_get_temp_dir() .
            DIRECTORY_SEPARATOR .
            basename((string)tempnam(sys_get_temp_dir(), 'ocr_')) .
            ".pdf";

        $instance = OCRmyPDF::make($inputFile)
            ->setOutputPDFPath($outputPath)
            ->setParam('--this-is-not-a-valid-param');

        $instance->run();
    }
}
