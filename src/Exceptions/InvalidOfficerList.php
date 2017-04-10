<?php

namespace DLJField\CompaniesHouse\Exceptions;

class InvalidOfficerList extends InvalidResponse
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
        $message = 'The officer list returned was invalid.';

        if ($statusCode == 401) {
            $message = 'Authentication with Companies House API failed.';
        } elseif ($statusCode == 404) {
            $message = 'Could not find a company with the given company number.';
        }

        return $message;
    }
}
