<?php namespace

DLJField\CompaniesHouse\Responses;

use GuzzleHttp\Psr7\Response;

abstract class ApiResponse
{
    /**
     * @param string $contents
     */
    private function __construct($contents)
    {
        foreach (json_decode($contents) as $key => $item) {
            $this->{$key} = $item;
        }
    }

    /**
     * Create a new CompanyProfile from the API Response
     * @param Response $response
     * @return static
     */
    public static function fromApiResponse(Response $response)
    {
        return new static($response->getBody()->getContents());
    }
}
