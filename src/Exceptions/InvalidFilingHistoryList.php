<?php

namespace DLJField\CompaniesHouse\Exceptions;

class InvalidFilingHistoryList extends InvalidResponse
{
    /**
     * @param int $statusCode
     * @param string $reason
     * @param string $contents
     */
    public function __construct($statusCode, $reason, $contents = null)
    {
        parent::__construct($this->resolveMessage($statusCode), $statusCode, $reason, $contents);
    }

    /**
     * Resolve a useful exception message based on the status code
     *
     * @param $statusCode
     * @return string
     */
    protected function resolveMessage($statusCode)
    {
        $message = 'The filing history list returned was invalid.';

        if ($statusCode == 404) {
            $message = 'Filing history not available for this company.';
        }

        return $message;
    }
}
