<?php

namespace Dustin\Exception;

class StackException extends ErrorCodeException
{
    private $errors = [];

    public function __construct(?string $message = null, \Throwable ...$errors)
    {
        $this->errors = $errors;
        $message = $message !== null ? trim($message) : '{{ errorCount }} errors occured:';

        if (!empty($message)) {
            $message .= "\n";
        }

        $message .= '{{ errors }}';

        parent::__construct(
            $message,
            [
                'errorCount' => count($errors),
                'errors' => $this->getErrorMessages($errors),
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'STACK_EXCEPTION';
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array<int, \Throwable> $errors
     */
    protected function getErrorMessages(array $errors): string
    {
        $message = '';

        foreach ($errors as $error) {
            $message .= ' • '.trim($error->getMessage())."\n";
        }

        return $message;
    }
}
