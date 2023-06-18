<?php

namespace Dustin\Exception;

class ExceptionStack
{
    private $errors = [];

    public function __construct(
        private ?string $message = null,
        \Throwable ...$errors
    ) {
        $this->errors = $errors;
    }

    public function add(\Throwable ...$exceptions): void
    {
        foreach ($exceptions as $exception) {
            $this->errors[] = $exception;
        }
    }

    public function throw(): void
    {
        if (count($this->errors) > 0) {
            throw new StackException($this->message, ...$this->errors);
        }
    }
}
