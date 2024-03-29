<?php

namespace mishagp\OCRmyPDF;

use InvalidArgumentException;

class Process
{
    private mixed $stdin;
    private mixed $stdout;
    private mixed $stderr;
    private mixed $handle;

    /**
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

        //This can avoid deadlock on some cases (when stderr buffer is filled up before writing to stdout and vice-versa)
        stream_set_blocking($this->stdout, false);
        stream_set_blocking($this->stderr, false);
    }

    /**
     * @throws UnsuccessfulCommandException
     */
    public static function checkProcessCreation(mixed $processHandle, string|Command $command): void
    {
        if (is_resource($processHandle) === true) {
            return;
        }

        $msg[] = 'Error: The command could not be launched.';
        $msg[] = '';
        $msg[] = 'Generated command:';
        $msg[] = "$command";
        $msg = join(PHP_EOL, $msg);

        throw new UnsuccessfulCommandException($msg);
    }

    public function write(string $data, int $dataLength): bool
    {
        if (is_resource($this->stdin) === false) {
            return false;
        }

        $total = 0;
        do {
            $res = fwrite($this->stdin, substr($data, $total));
        } while ($res && $total += $res < $dataLength);
        return $total === $dataLength;
    }

    /**
     * @return array<string, string>
     */
    public function wait(): array
    {
        $data = [
            "out" => "",
            "err" => ""
        ];

        if (is_resource($this->stdout) === false
            || is_resource($this->stderr) === false
            || is_resource($this->handle) === false
        ) {
            return $data;
        }

        $running = true;
        while ($running === true) {
            $data["out"] .= fread($this->stdout, 8192);
            $data["err"] .= fread($this->stderr, 8192);
            $procInfo = proc_get_status($this->handle);
            $running = $procInfo["running"];
        }
        return $data;
    }

    public function closeStreams(?string $stream = null): self
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

    public function closeHandle(): self
    {
        if (is_resource($this->handle) === false) {
            throw new InvalidArgumentException("Process handle is not a resource");
        }
        proc_close($this->handle);
        return $this;
    }

    public function closeStdin(): void
    {
        $this->closeStream($this->stdin);
    }

    /**
     * @param resource $stream
     */
    private function closeStream(&$stream): void
    {
        if ($stream !== NULL) {
            fclose($stream);
            $stream = NULL;
        }
    }
}