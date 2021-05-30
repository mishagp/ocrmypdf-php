<?php

namespace mishagp\OCRmyPDF;

class Process
{
    private mixed $stdin;
    private mixed $stdout;
    private mixed $stderr;
    private mixed $handle;

    /**
     * @param string $commandString
     * @throws UnsuccessfulCommandException
     */
    public function __construct(string $commandString)
    {
        $streamDescriptors = [
            ["pipe", "r"],
            ["pipe", "w"],
            ["pipe", "w"]
        ];
        $this->handle = proc_open($commandString, $streamDescriptors, $pipes, NULL, NULL, ["bypass_shell" => true]);
        list($this->stdin, $this->stdout, $this->stderr) = $pipes;

        self::checkProcessCreation($this->handle, $commandString);

        //This is can avoid deadlock on some cases (when stderr buffer is filled up before writing to stdout and vice-versa)
        stream_set_blocking($this->stdout, 0);
        stream_set_blocking($this->stderr, 0);
    }

    /**
     * @param resource $processHandle
     * @param string|Command $command
     * @throws UnsuccessfulCommandException
     */
    public static function checkProcessCreation(mixed $processHandle, string|Command $command)
    {
        if ($processHandle !== FALSE) return;

        $msg[] = 'Error: The command could not be launched.';
        $msg[] = '';
        $msg[] = 'Generated command:';
        $msg[] = "$command";
        $msg = join(PHP_EOL, $msg);

        throw new UnsuccessfulCommandException($msg);
    }

    /**
     * @param string $data
     * @param int $dataLength
     * @return bool
     */
    public function write(string $data, int $dataLength): bool
    {
        $total = 0;
        do {
            $res = fwrite($this->stdin, substr($data, $total));
        } while ($res && $total += $res < $dataLength);
        return $total === $dataLength;
    }

    /**
     * @return string[]
     */
    public function wait(): array
    {
        $running = true;
        $data = [
            "out" => "",
            "err" => ""
        ];
        while ($running === true) {
            $data["out"] .= fread($this->stdout, 8192);
            $data["err"] .= fread($this->stderr, 8192);
            $procInfo = proc_get_status($this->handle);
            $running = $procInfo["running"];
        }
        return $data;
    }

    /**
     * @param string|null $stream
     * @return $this
     */
    public function closeStreams(string $stream = null): Process
    {
        switch ($stream) {
            case "stdin":
                $this->closeStream($this->stdin);
                break;
            case "stdout":
                $this->closeStream($this->stdout);
                break;
            case "stderr":
                $this->closeStream($this->stderr);
                break;
            case null:
                $this->closeStream($this->stdin);
                $this->closeStream($this->stdout);
                $this->closeStream($this->stderr);
                break;
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function closeHandle(): Process
    {
        proc_close($this->handle);
        return $this;
    }

    /**
     * @return void
     */
    public function closeStdin(): void
    {
        $this->closeStream($this->stdin);
    }

    /**
     * @param resource $stream
     */
    private function closeStream(&$stream)
    {
        if ($stream !== NULL) {
            fclose($stream);
            $stream = NULL;
        }
    }
}