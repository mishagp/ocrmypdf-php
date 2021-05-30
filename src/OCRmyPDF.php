<?php

namespace mishagp\OCRmyPDF;

class OCRmyPDF
{
    public Command $command;

    public function __construct(string $inputFile = null, Command $command = null)
    {
        $this->command = $command ?: new Command();
        $this->setInputFile("$inputFile");
    }

    /**
     * @param $path
     * @return bool
     * @throws NoWritePermissionsException
     */
    public static function checkWritePermissions($path): bool
    {
        if (!is_dir(dirname($path))) mkdir(dirname($path));
        $writableDirectory = is_writable(dirname($path));
        $writableFile = true;
        if (file_exists($path)) $writableFile = is_writable($path);
        if ($writableFile && $writableDirectory) return true;

        $msg = [];
        $msg[] = "Error: No permission to write to $path";
        $msg[] = "Make sure you have the right outputFile and permissions "
            . "to write to the directory";
        $msg[] = '';
        $msg = join(PHP_EOL, $msg);

        throw new NoWritePermissionsException($msg);
    }

    /**
     * @param $executable
     * @throws OCRmyPDFNotFoundException
     */
    public static function checkOCRmyPDFPresence($executable)
    {
        if (file_exists($executable)) return;

        $cmd = stripos(PHP_OS, 'win') === 0
            ? 'where.exe ' . Command::escape($executable) . ' > NUL 2>&1'
            : 'type ' . Command::escape($executable) . ' > /dev/null 2>&1';
        system($cmd, $exitCode);

        if ($exitCode == 0) return;

        $currentPath = getenv('PATH');
        $msg = [];
        $msg[] = "Error: The command \"$executable\" was not found.";
        $msg[] = '';
        $msg[] = 'Make sure you have OCRmyPDF and required dependencies installed on your system:';
        $msg[] = 'https://github.com/jbarlow83/OCRmyPDF';
        $msg[] = '';
        $msg[] = "The current \$PATH is $currentPath";
        $msg = join(PHP_EOL, $msg);

        throw new OCRmyPDFNotFoundException($msg);
    }

    /**
     * @throws FileNotFoundException
     */
    public static function checkFilePath($file)
    {
        if (file_exists($file)) return;

        $currentDir = __DIR__;
        $msg = [];
        $msg[] = "Error: The input file \"$file\" was not found or is inaccessible.";
        $msg[] = '';
        $msg[] = "The current __DIR__ is $currentDir";
        $msg = join(PHP_EOL, $msg);

        throw new FileNotFoundException($msg);
    }

    /**
     * @return mixed
     * @throws NoWritePermissionsException
     * @throws UnsuccessfulCommandException
     * @throws OCRmyPDFException
     */
    public function run(): mixed
    {
        try {
            self::checkOCRmyPDFPresence($this->command->executable);
            if ($this->command->useFileAsInput) {
                self::checkFilePath($this->command->inputFilePath);
            }

            $process = new Process("$this->command");

            if (!$this->command->useFileAsInput) {
                $process->write($this->command->inputData, $this->command->inputDataSize);
                $process->closeStdin();
            }
            $output = $process->wait();

            Command::checkCommandExecution($this->command, $output["out"], $output["err"]);
        } catch (OCRmyPDFException $e) {
            if ($this->command->useFileAsOutput) $this->cleanTempFiles();
            throw $e;
        }
        if (!$this->command->useFileAsOutput) {
            return $output["out"];
        }

        $process->close();
        return true;
    }

    /**
     * @param string $inputFile
     * @return $this
     */
    public function setInputFile(string $inputFile): OCRmyPDF
    {
        $this->command->inputFilePath = $inputFile;
        return $this;
    }

    /**
     * @param string $inputData
     * @param int $inputDataSize
     * @return $this
     */
    public function setInputData(string $inputData, int $inputDataSize): OCRmyPDF
    {
        $this->command->useFileAsInput = false;
        $this->command->inputData = $inputData;
        $this->command->inputDataSize = $inputDataSize;
        return $this;
    }

    private function cleanTempFiles()
    {
        if (file_exists($this->command->getOutputPDFPath())) {
            unlink($this->command->getOutputPDFPath());
        }
        if (file_exists($this->command->getOutputPDFPath())) {
            unlink($this->command->getOutputPDFPath());
        }
    }

    /**
     * @param string $executablePath
     * @return $this
     * @throws OCRmyPDFNotFoundException
     */
    public function setExecutable(string $executablePath): OCRmyPDF
    {
        self::checkOCRmyPDFPresence($executablePath);
        $this->command->executable = $executablePath;
        return $this;
    }
}