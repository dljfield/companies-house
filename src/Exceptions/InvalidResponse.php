<?php

namespace DLJField\CompaniesHouse\Exceptions;

use Exception;

abstract class InvalidResponse extends Exception
{
    /**
     * The HTTP status code of the invalid response
     *
     * @var int
     */
    public $statusCode;

    /**
     * The reason associated with the HTTP status code
     *
     * @var string
     */
    public $reason;

    /**
     * Companies House API error contents
     *
     * @var array
     */
    public $contents;

    /**
     * @param string $message
     * @param int $statusCode
     * @param string $reason
     * @param string $contents
     */
    public function __construct($message, $statusCode, $reason, $contents = null)
    {
        parent::__construct($message);

        $this->statusCode = $statusCode;
        $this->reason = $reason;

        if ($contents) {
            $this->contents = json_decode($contents, true);
        }
    }

    /**
     * Resolves a userful exception message based on the status code
     *
     * @param int $statusCode
     * @return string
     */
    abstract protected function resolveMessage($statusCode);
}
