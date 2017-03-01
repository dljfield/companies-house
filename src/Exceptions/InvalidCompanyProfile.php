<?php

namespace DLJField\CompaniesHouse\Exceptions;

class InvalidCompanyProfile extends InvalidResponse
{
    /**
     * @param int $statusCode
     * @param string $reason
     * @param string $contents
     */
    public function __construct($statusCode, $reason, $contents = null)
    {
        parent::__construct('The company profile returned was invalid.', $statusCode, $reason, $contents);
    }
}
