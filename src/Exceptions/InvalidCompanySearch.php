<?php namespace DLJField\CompaniesHouse\Exceptions;

class InvalidCompanySearch extends InvalidResponse
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
    private function resolveMessage($statusCode)
    {
        $message = 'The company search returned was invalid.';

        if ($statusCode == 401) {
            $message = 'Authentication with Companies House API failed.';
        }

        return $message;
    }
}
