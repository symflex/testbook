<?php

namespace Component;

class Response
{
    private string $content;

    private int $code;

    private array $headers;

    public function __construct(string $content, int $code = 200, array $headers = [])
    {
        $this->content = $content;
        $this->code = $code;
        $this->headers = $headers;
    }

    public function __toString()
    {
        header('HTTP/1.1 ' . $this->code. ' OK');
        foreach ($this->headers as $key => $value) {
            header($key . ': ' . $value);
        }
        return $this->content;
    }
}
