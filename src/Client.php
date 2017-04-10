<?php

namespace DLJField\CompaniesHouse;

use DLJField\CompaniesHouse\Exceptions\InvalidCompanyProfile;
use DLJField\CompaniesHouse\Exceptions\InvalidCompanySearch;
use DLJField\CompaniesHouse\Exceptions\InvalidDocumentContents;
use DLJField\CompaniesHouse\Exceptions\InvalidDocumentMeta;
use DLJField\CompaniesHouse\Exceptions\InvalidFilingHistoryList;
use DLJField\CompaniesHouse\Exceptions\InvalidOfficerList;
use DLJField\CompaniesHouse\Responses\CompanyProfile;
use DLJField\CompaniesHouse\Responses\CompanySearch;
use DLJField\CompaniesHouse\Responses\DocumentContents;
use DLJField\CompaniesHouse\Responses\DocumentMeta;
use DLJField\CompaniesHouse\Responses\FilingHistoryList;
use DLJField\CompaniesHouse\Responses\OfficerList;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;

class Client
{
    /**
     * @var GuzzleClient
     */
    private $guzzleClient;

    /**
     * The base URL for the Companies House API
     *
     * @var string
     */
    private $apiBaseUrl = 'https://api.companieshouse.gov.uk';

    /**
     * The base URL for the Companies House Document API
     *
     * @var string
     */
    private $documentApiBaseUrl = 'http://document-api.companieshouse.gov.uk';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Get the company profile for the provided company number
     *
     * https://developer.companieshouse.gov.uk/api/docs/company/company_number/company_number.html
     *
     * @param string $companyNumber
     * @return CompanyProfile
     * @throws InvalidCompanyProfile
     */
    public function getCompanyProfile($companyNumber)
    {
        $response = $this->get('/company/' . $companyNumber);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidCompanyProfile($response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()->getContents());
        }

        return CompanyProfile::fromApiResponse($response);
    }

    /**
     * List the company officers
     *
     * https://developer.companieshouse.gov.uk/api/docs/company/company_number/officers/officers.html
     *
     * @param string $companyNumber
     * @param int $itemsPerPage
     * @param int $startIndex
     * @param string $orderBy
     * @return OfficerList
     * @throws InvalidOfficerList
     */
    public function getOfficerList($companyNumber, $itemsPerPage = null, $startIndex = null, $orderBy = null)
    {
        $response = $this->get('/company/' . $companyNumber . '/officers', [
            'query' => [
                'items_per_page' => $itemsPerPage,
                'start_index' => $startIndex,
                'order_by' => $orderBy,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidOfficerList($response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()->getContents());
        }

        return OfficerList::fromApiResponse($response);
    }

    /**
     * List the filing history of the company
     *
     * https://developer.companieshouse.gov.uk/api/docs/company/company_number/filing-history/getFilingHistoryList.html
     *
     * @param string $companyNumber
     * @param int $itemsPerPage
     * @param int $startIndex
     * @return FilingHistoryList
     * @throws InvalidFilingHistoryList
     */
    public function getFilingHistoryList($companyNumber, $itemsPerPage = null, $startIndex = null)
    {
        $response = $this->get('/company/' . $companyNumber . '/filing-history', [
            'query' => [
                'items_per_page' => $itemsPerPage,
                'start_index' => $startIndex
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidFilingHistoryList($response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()->getContents());
        }

        return FilingHistoryList::fromApiResponse($response);
    }

    /**
     * Search for a company
     *
     * https://developer.companieshouse.gov.uk/api/docs/search/companies/companysearch.html
     *
     * @param string $q
     * @param int $itemsPerPage
     * @param int $startIndex
     * @return CompanySearch
     * @throws InvalidCompanySearch
     */
    public function companySearch($q, $itemsPerPage = null, $startIndex = null)
    {
        $response = $this->get('/search/companies', [
            'query' => [
                'q' => $q,
                'items_per_page' => $itemsPerPage,
                'start_index' => $startIndex,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new InvalidCompanySearch($response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()->getContents());
        }

        return CompanySearch::fromApiResponse($response);
    }

    /**
     * Get document meta
     *
     * https://developer.companieshouse.gov.uk/document/docs/document/id/fetchDocumentMeta.html
     *
     * @param string $documentId
     * @return DocumentMeta
     * @throws InvalidDocumentMeta
     */
    public function getDocumentMeta($documentId)
    {
        $response = $this->getDocument("/document/$documentId");

        if ($response->getStatusCode() !== 200) {
            throw new InvalidDocumentMeta($response->getStatusCode(), $response->getReasonPhrase(), $response->getBody()->getContents());
        }

        return DocumentMeta::fromApiResponse($response);
    }

    /**
     * Get document contents
     *
     * https://developer.companieshouse.gov.uk/document/docs/document/id/content/fetchDocument.html
     *
     * @param string $documentId
     * @param string $contentType
     * @return DocumentContents
     * @throws InvalidDocumentContents
     */
    public function getDocumentContents($documentId, $contentType)
    {
        $response = $this->getDocument("/document/$documentId/content", [
            'query' => [
                'accept' => $contentType
            ]
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode !== 200 && $statusCode !== 302) {
            throw new InvalidDocumentContents($statusCode, $response->getReasonPhrase(), $response->getBody()->getContents());
        }

        return DocumentContents::fromApiResponse($response);
    }

    /**
     * Perform a GET request on the Companies House API
     *
     * @param string $resource
     * @param array $headers
     * @return Response
     */
    private function get($resource, array $headers = [])
    {
        $this->setupClient($this->apiBaseUrl);

        return $this->performGetRequest($resource, $headers);
    }

    /**
     * Perform a GET request on the document API
     *
     * @param string $resource
     * @param array $headers
     * @return Response
     */
    private function getDocument($resource, array $headers = [])
    {
        $this->setupClient($this->documentApiBaseUrl);

        return $this->performGetRequest($resource, $headers);
    }

    /**
     * Perform a GET request on the given resource
     *
     * @param string $resource
     * @param array $headers
     * @return Response
     */
    private function performGetRequest($resource, array $headers = [])
    {
        try {
            return $this->guzzleClient->request('GET', $resource, ['auth' => [$this->apiKey, '']] + $headers);
        } catch (ClientException $e) {
            return $e->getResponse();
        }
    }

    /**
     * Set up the Guzzle client to perform our requests
     *
     * @return void
     */
    private function setupClient($baseUri)
    {
        $this->guzzleClient = new GuzzleClient(['base_uri' => $baseUri]);
    }
}
