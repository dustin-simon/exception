<?php

namespace Dustin\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class ErrorCodeException extends HttpException implements ErrorCode
{
    public function __construct(
        string $message,
        private array $parameters,
        array $headers = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $this->getStatusCode(),
            $this->buildMessage($message, $parameters),
            $previous,
            $headers
        );
    }

    abstract public function getErrorCode(): string;

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getParameter(string $key): mixed
    {
        return $this->parameters[$key] ?? null;
    }

    protected function buildMessage(string $message, array $parameters = []): string
    {
        $regex = [];

        foreach ($parameters as $key => $value) {
            if (\is_array($value)) {
                continue;
            }

            $formattedKey = preg_replace('/[^a-z]/i', '', $key);
            $regex[sprintf('/\{\{(\s+)?(%s)(\s+)?\}\}/', $formattedKey)] = $value;
        }

        return (string) preg_replace(array_keys($regex), array_values($regex), $message);
    }
}
