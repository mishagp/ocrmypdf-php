<?php

namespace mishagp\OCRmyPDF;

class Command
{
    public string $executable = 'ocrmypdf';
    public bool $useFileAsInput = true;
    public bool $useFileAsOutput = true;
    public string|null $tempDir;
    public int|null $threadLimit;
    public string|null $inputFilePath;
    public int|null $inputDataSize;
    public string|null $inputData;
    private string|null $outputPDFPath;

    /**
     * Command constructor.
     * @param string|null $inputFilePath Path to input file
     * @param string|null $outputPDFPath Path to output file
     */
    public function __construct(string $inputFilePath = null, string $outputPDFPath = null, string $tempDir = null, int $threadLimit = null)
    {
        $this->inputFilePath = $inputFilePath;
        $this->outputPDFPath = $outputPDFPath;
        $this->tempDir = $tempDir;
        $this->threadLimit = $threadLimit;
    }

    /**
     * @param Command $command
     * @param $stdout
     * @param $stderr
     * @throws UnsuccessfulCommandException
     */
    public static function checkCommandExecution(Command $command, $stdout, $stderr)
    {
        if ($command->useFileAsOutput) {
            $file = $command->getOutputPDFPath();
            if (file_exists($file) && filesize($file) > 0) return;
        }

        if (!$command->useFileAsOutput && $stdout) {
            return;
        }

        if (!strpos($stderr, 'error') === FALSE) {
            return;
        }

        $msg = [];
        $msg[] = 'Error: The command output contains an error.';
        $msg[] = '';
        $msg[] = 'Generated command:';
        $msg[] = "$command";
        $msg[] = '';
        $msg[] = 'Returned message:';
        $arrayStderr = explode(PHP_EOL, $stderr);
        array_pop($arrayStderr);
        $msg = array_merge($msg, $arrayStderr);
        $msg = join(PHP_EOL, $msg);

        throw new UnsuccessfulCommandException($msg);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $cmd = [];

        $cmd[] = self::escape($this->executable);
        if ($this->threadLimit) $cmd[] = "--jobs=$this->threadLimit";
        $cmd[] = $this->useFileAsInput ? self::escape($this->inputFilePath) : "-";
        $cmd[] = $this->useFileAsOutput ? self::escape($this->getOutputPDFPath()) : "-";

        return join(' ', $cmd);
    }

    /**
     * @return string|null
     */
    public function getOutputPDFPath(): ?string
    {
        if (!$this->outputPDFPath)
            $this->outputPDFPath = $this->getTempDir()
                . DIRECTORY_SEPARATOR
                . basename(tempnam($this->getTempDir(), 'ocr_'))
                . ".pdf";
        return $this->outputPDFPath;
    }

    /**
     * @throws NoWritePermissionsException
     */
    public function setOutputPDFPath(string|null $outputPDFPath)
    {
        if ($outputPDFPath == null) {
            $this->useFileAsOutput = false;
        } else {
            $this->useFileAsOutput = true;
            if (OCRmyPDF::checkWritePermissions($outputPDFPath)) {
                $this->outputPDFPath = $outputPDFPath;
            }
        }
    }

    /**
     * @return string
     */
    public function getTempDir(): string
    {
        return $this->tempDir ?: sys_get_temp_dir();
    }

    /**
     * @return string
     */
    public function getOCRmyPDFVersion(): string
    {
        exec(self::escape($this->executable) . ' --version 2>&1', $output);
        return reset($output);
    }

    /**
     * @param $str
     * @return string
     */
    public static function escape($str): string
    {
        $charlist = strtoupper(substr(PHP_OS, 0, 3)) == 'WIN' ? '$"`' : '$"\\`';
        return '"' . addcslashes($str, $charlist) . '"';
    }
}