# Companies House API Client

A simple API client for the [Companies House API](https://developer.companieshouse.gov.uk/api/docs/index.html).

## Installation

This client can be installed with Composer:

```
composer require dljfield/companies-house
```

## Endpoints

This client currently has at least partial support for the following endpoints of the Companies House API:

- [Company profile](https://developer.companieshouse.gov.uk/api/docs/company/company_number/company_number.html)
- [Officer list](https://developer.companieshouse.gov.uk/api/docs/company/company_number/officers/officers.html)
- [Company search](https://developer.companieshouse.gov.uk/api/docs/search/companies/companysearch.html)


## Usage

To use the client, you must create an instance of `DLJField\CompaniesHouse\Client`, passing your Companies House API key into the constructor.

### Responses

All endpoints return a named class as a response. These classes are simple data objects that contain the response contents of the API call. 

### Exceptions
If an API call fails, an exception will be thrown. As well as a normal exception message, these exceptions hold a `statusCode` parameter, a `reason` parameter and a `contents` parameter. These are the status code of the API response, the reason phrase associated with that status code, and any response body returned from the Companies House API (such as their own errors). 
