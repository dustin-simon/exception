<?php

namespace Dustin\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationException extends ErrorCodeException
{
    public const ERROR_CODE = 'CONSTRAINT_VALIDATION_FAILED';

    public function __construct(
        private ConstraintViolationListInterface $violations,
        private array $inputData
    ) {
        $message = "Constraint validation failed. Caught {{ count }} errors:\n{{ violations }}";

        $params = [
            'count' => count($violations),
            'violations' => $this->getViolationsMessage($violations),
        ];

        parent::__construct($message, $params);
    }

    public function getErrorCode(): string
    {
        return self::ERROR_CODE;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public function getInputData(): array
    {
        return $this->inputData;
    }

    private function getViolationsMessage(ConstraintViolationListInterface $violations): string
    {
        $message = '';

        foreach ($violations as $violation) {
            $message .= ' • '.$violation->getPropertyPath().': '.$violation->getMessage()."\n";
        }

        return $message;
    }
}
