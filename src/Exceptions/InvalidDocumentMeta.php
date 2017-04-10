<?php namespace DLJField\CompaniesHouse\Exceptions;

class InvalidDocumentMeta extends InvalidResponse
{
    /**
     * @param string $message
     * @param int $statusCode
     * @param string $reason
     * @param string $contents
     */
    public function __construct($message, $statusCode, $reason, $contents = null)
    {
        parent::__construct($this->resolveMessage($statusCode), $statusCode, $reason, $contents);
    }

    /**
     * Resolves a userful exception message based on the status code
     *
     * @param int $statusCode
     * @return string
     */
    protected function resolveMessage($statusCode)
    {
        return 'The document meta returned was invalid.';
    }
}
