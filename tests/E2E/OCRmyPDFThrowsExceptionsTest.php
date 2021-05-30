<?php


namespace mishagp\OCRmyPDF\Tests\E2E;


use mishagp\OCRmyPDF\FileNotFoundException;
use mishagp\OCRmyPDF\NoWritePermissionsException;
use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFException;
use mishagp\OCRmyPDF\OCRmyPDFNotFoundException;
use mishagp\OCRmyPDF\UnsuccessfulCommandException;
use PHPUnit\Framework\TestCase;

class OCRmyPDFThrowsExceptionsTest extends TestCase
{
    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     * @throws NoWritePermissionsException
     */
    public function testOCRmyPDFThrowsFileNotFoundExceptionWithoutInput()
    {
        $this->expectException(FileNotFoundException::class);
        $instance = new OCRmyPDF();
        $instance->run();
    }

    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     * @throws NoWritePermissionsException
     */
    public function testOCRmyPDFThrowsOCRmyPDFFoundExceptionWithMalformedExecutable()
    {
        $this->expectException(OCRmyPDFNotFoundException::class);
        $instance = new OCRmyPDF();
        $instance->setExecutable(substr(md5(rand()), 0, 20));
        $instance->run();
    }

    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     * @throws NoWritePermissionsException
     */
    public function testOCRmyPDFThrowsExceptionWithInvalidPDF()
    {
        $this->expectException(UnsuccessfulCommandException::class);
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "invalid_pdf.pdf";
        $instance = new OCRmyPDF($inputFile);
        $instance->run();
    }
}