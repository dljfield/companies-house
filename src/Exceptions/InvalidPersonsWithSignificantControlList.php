<?php namespace DLJField\CompaniesHouse\Exceptions;

class InvalidPersonsWithSignificantControlList extends InvalidResponse
{
    /**
     * Resolves a userful exception message based on the status code
     *
     * @param int $statusCode
     * @return string
     */
    protected function resolveMessage($statusCode)
    {
        $message = 'The persons with significant control list returned was invalid.';

        if ($statusCode == 401) {
            $message = 'Authentication with Companies House API failed.';
        } elseif ($statusCode == 400) {
            $message = 'The request was malformed.';
        }

        return $message;
    }
}
