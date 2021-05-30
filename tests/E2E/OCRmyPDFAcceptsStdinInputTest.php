<?php


namespace mishagp\OCRmyPDF\Tests\E2E;


use mishagp\OCRmyPDF\NoWritePermissionsException;
use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFException;
use mishagp\OCRmyPDF\UnsuccessfulCommandException;
use PHPUnit\Framework\TestCase;

class OCRmyPDFAcceptsStdinInputTest extends TestCase
{
    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc1_NoParameters()
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc1.pdf";
        $instance = new OCRmyPDF();
        $instance->setInputData(file_get_contents($inputFile), filesize($inputFile));
        $this->assertTrue($instance->run());
        $this->assertFileExists($instance->command->getOutputPDFPath());
        $this->assertFileIsReadable($instance->command->getOutputPDFPath());
        $this->assertFileIsWritable($instance->command->getOutputPDFPath());
        echo "Output: " . $instance->command->getOutputPDFPath();
    }
}