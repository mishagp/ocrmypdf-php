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
    public string|null $outputPDFPath;

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
     * @param Command $command Generated command
     * @param string $stdout Value from stdout
     * @param string $stderr Value from stderr
     * @return bool Returns true upon successful execution
     * @throws UnsuccessfulCommandException
     */
    public static function checkCommandExecution(Command $command, string $stdout, string $stderr): bool
    {
        if ($command->useFileAsOutput) {
            $file = $command->getOutputPDFPath();
            if (file_exists($file) && filesize($file) > 0) return true;
        }

        if (!$command->useFileAsOutput && $stdout) {
            return true;
        }

        if (!strpos($stderr, 'error') === FALSE) {
            return true;
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
    public function __toString(): string
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