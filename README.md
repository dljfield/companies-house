# Companies House API Client

A simple API client for the [Companies House API](https://developer.companieshouse.gov.uk/api/docs/index.html).

## Installation

This client can be installed with Composer:

```
composer require dljfield/companies-house
```

## Endpoints

This client currently has at least partial support for the following endpoints of the Companies House API:

- [Company Profile](https://developer.companieshouse.gov.uk/api/docs/company/company_number/company_number.html)
- [Officer List](https://developer.companieshouse.gov.uk/api/docs/company/company_number/officers/officers.html)
- [Filing History List](https://developer.companieshouse.gov.uk/api/docs/company/company_number/filing-history/getFilingHistoryList.html)
- [Company Search](https://developer.companieshouse.gov.uk/api/docs/search/companies/companysearch.html)
- [Document Meta](https://developer.companieshouse.gov.uk/document/docs/document/id/fetchDocumentMeta.html)
- [Document Contents](https://developer.companieshouse.gov.uk/document/docs/document/id/content/fetchDocument.html)


## Usage

To use the client, you must create an instance of `DLJField\CompaniesHouse\Client`, passing your Companies House API key into the constructor.

### Responses

All endpoints return a named class as a response. 

Except for the `DocumentContents` type, these classes are simple data objects that contain the response contents of the API call. As such, you can iterate over them and access them the way you would any other simple data object. e.g.

```
$companyProfile = $companiesHouseClient->getCompanyProfile($companyNumber);

foreach ($companyProfile as $field) {
    echo $field;
}

echo $companyProfile->next_accounts->due_on;
```

`DocumentContents` instead has two fields:
- `$content`
- `$contentLength`

The content should be the file available to stream directly to the browser, e.g.

```
$pdfFile = $companiesHouseClient->getDocumentContents($documentId, DocumentContents::CONTENT_TYPE_PDF);

header("Content-type: application/octet-stream");
header("Content-disposition: attachment;filename=filing-history-document.pdf");

echo $pdfFile->content;
exit;
```

### Exceptions
If an API call fails, an exception will be thrown. As well as a normal exception message, these exceptions hold a `statusCode` parameter, a `reason` parameter and a `contents` parameter. These are the status code of the API response, the reason phrase associated with that status code, and any response body returned from the Companies House API (such as their own errors). 
