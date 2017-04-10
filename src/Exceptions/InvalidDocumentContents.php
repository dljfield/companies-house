<?php namespace DLJField\CompaniesHouse\Exceptions;

class InvalidDocumentContents extends InvalidResponse
{
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
        $message = 'The document contents returned were invalid.';

        if ($statusCode == 406) {
            $message = 'The selected document type was unavailable.';
        }

        return $message;
    }
}
