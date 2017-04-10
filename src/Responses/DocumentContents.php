<?php namespace DLJField\CompaniesHouse\Responses;

use GuzzleHttp\Psr7\Response;

class DocumentContents extends ApiResponse
{
    const CONTENT_TYPE_PDF = 'application/pdf';
    const CONTENT_TYPE_JOSN = 'application/json';
    const CONTENT_TYPE_XML = 'application/xml';
    const CONTENT_TYPE_XML_XHTML = 'application/xhtml+xml';
    const CONTENT_TYPE_CSV = 'text/csv';

    /**
     * @var string
     */
    public $contents;

    /**
     * @var string
     */
    public $contentLength;

    /**
     * @param string $contents
     * @param string $contentLength
     */
    public function __construct($contents, $contentLength)
    {
        $this->contents = $contents;
        $this->contentLength = $contentLength;
    }

    /**
     * Create a new CompanyProfile from the API Response
     * @param Response $response
     * @return static
     */
    public static function fromApiResponse(Response $response)
    {
        return new static($response->getBody()->getContents(), $response->getHeader('Content-Length')[0]);
    }
}
