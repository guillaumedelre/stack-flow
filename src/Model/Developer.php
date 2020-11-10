<?php

namespace App\Model;

class Developer
{
    private string $code;
    private string $color;
    private array $data;

    public function __construct(string $code, string $color, array $data)
    {
        $this->code = $code;
        $this->color = $color;
        $this->data = $data;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
