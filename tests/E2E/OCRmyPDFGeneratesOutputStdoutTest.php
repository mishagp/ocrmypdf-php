<?php


namespace mishagp\OCRmyPDF\Tests\E2E;


use mishagp\OCRmyPDF\Command;
use mishagp\OCRmyPDF\NoWritePermissionsException;
use mishagp\OCRmyPDF\OCRmyPDF;
use mishagp\OCRmyPDF\OCRmyPDFException;
use mishagp\OCRmyPDF\Process;
use mishagp\OCRmyPDF\UnsuccessfulCommandException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertTrue;

#[CoversClass(OCRmyPDF::class)]
#[CoversClass(Command::class)]
#[CoversClass(Process::class)]
class OCRmyPDFGeneratesOutputStdoutTest extends TestCase
{
    /**
     * @throws OCRmyPDFException
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     */
    public function testProcess_en_US_doc1_NoParameters(): void
    {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "examples" . DIRECTORY_SEPARATOR . "en_US_doc1.pdf";
        $instance = new OCRmyPDF();
        $instance->setInputFile($inputFile);
        $instance->setOutputPDFPath(null);
        $stdOut = $instance->run();
        assertTrue(
            str_contains($stdOut, "<xmp:CreatorTool>OCRmyPDF")
            || str_contains($stdOut, "<xmp:CreatorTool>ocrmypdf")
        );
    }
}