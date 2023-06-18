<?php

namespace Dustin\Exception;

interface ErrorCode extends \Throwable
{
    public function getErrorCode(): string;

    public function getParameters(): array;
}
