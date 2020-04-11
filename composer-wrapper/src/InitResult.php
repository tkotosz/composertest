<?php

namespace Tkotosz\ComposerWrapper;

class InitResult
{
    /** @var int */
    private $status;

    /** @var string */
    private $output;

    public function __construct(int $status, string $output)
    {
        $this->status = $status;
        $this->output = $output;
    }

    public function isError(): bool
    {
        return $this->status !== 0;
    }

    public function output(): string
    {
        return $this->output;
    }
}