<?php


namespace mishagp\OCRmyPDF\Tests\E2E;


use mishagp\OCRmyPDF\Command;
use mishagp\OCRmyPDF\FileNotFoundException;
use mishagp\OCRmyPDF\NoWritePermissionsException;
use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFException;
use mishagp\OCRmyPDF\OCRmyPDFNotFoundException;
use mishagp\OCRmyPDF\Process;
use mishagp\OCRmyPDF\UnsuccessfulCommandException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(OCRmyPDF::class)]
#[CoversClass(Command::class)]
#[CoversClass(Process::class)]
class OCRmyPDFThrowsExceptionsTest extends TestCase
{
    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     * @throws NoWritePermissionsException
     */
    public function testOCRmyPDFThrowsFileNotFoundExceptionWithoutInput(): void
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
    public function testOCRmyPDFThrowsOCRmyPDFFoundExceptionWithMalformedExecutable(): void
    {
        $this->expectException(OCRmyPDFNotFoundException::class);
        $instance = new OCRmyPDF();
        $instance->setExecutable(substr(md5((string)rand()), 0, 20));
        $instance->run();
    }

    /**
     * @throws OCRmyPDFException
     * @throws UnsuccessfulCommandException
     * @throws NoWritePermissionsException
     */
    public function testOCRmyPDFThrowsExceptionWithInvalidPDF(): void
    {
        $this->expectException(UnsuccessfulCommandException::class);
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "invalid_pdf.pdf";
        $instance = new OCRmyPDF($inputFile);
        $instance->run();
    }
}