# MyTheresa API Project

A PHP API project using Symfony with Redis caching and PostgresSQL database.

## Prerequisites

Before you begin, ensure you have the following installed:
- Docker and Docker Compose
- Make
- Git

## Installation

### Clone the repository
```bash
git clone git@github.com:doppiogancio/challange-mytheresa.git
cd challange-mytheresa
```

### Run the complete setup
The setup command will build and launch the application, recreate the database, and load the fixtures. By default, only five products are loaded. Once the setup process is complete, you can begin using the API, explore the documentation, or utilize the profiler.
```bash
make setup
```

### Add more fixtures
To add approximately 20,000 simulated products along with an equivalent number of discounts, execute the command below. Please note that the process may take between 20 seconds and 1 minute to complete, depending on the performance of the machine. 
```bash
make recreate-database-large
```

## Running the project 

The project runs the following services:

| Service       | URL                                                                             | Notes                              |
|---------------|---------------------------------------------------------------------------------|------------------------------------|
| Api doc       | http://localhost                                                                |                                    |
| Api request   | http://localhost/products?category=boots&priceLessThan=70000                    | All the parameters are optional.   |
| Profiler      | http://localhost/_profiler                                                      |                                    | 
| Code coverage | http://localhost/coverage/index.html                                            | Available after the first test run | 

1. The price filter will consider the final price, not the original.
2. Page size is defaulted to 5
3. Category and priceLassThan instead are defaulted to null

## Example API Request and Response

### Request
```http
GET http://localhost/products?category=boots
```

### Response
```json
{
  "query_string": {
    "category": "boots",
    "priceLessThan": null,
    "page": 1,
    "pageSize": 5
  },
  "pagination": {
    "total_items": 3,
    "items_per_page": 5,
    "total_pages": 1,
    "current_page": 1,
    "prev_page": null,
    "next_page": null
  },
  "products": [
    {
      "sku": "000001",
      "name": "BV Lean leather ankle boots",
      "category": "boots",
      "price": {
        "original": 89000,
        "final": 62300,
        "discount_percentage": "30%",
        "currency": "EUR"
      }
    },
    {
      "sku": "000002",
      "name": "BV Lean leather ankle boots",
      "category": "boots",
      "price": {
        "original": 99000,
        "final": 69300,
        "discount_percentage": "30%",
        "currency": "EUR"
      }
    },
    {
      "sku": "000003",
      "name": "Ashlington leather ankle boots",
      "category": "boots",
      "price": {
        "original": 71000,
        "final": 49700,
        "discount_percentage": "30%",
        "currency": "EUR"
      }
    }
  ]
}
```

## Test
```bash
make tests
```

## Final considerations
I intentionally omitted authentication, as it appeared to be outside the scope of this challenge.

Given the requirement to consider future scalability of the API to handle approximately 20,000 products, I aimed to test 
its performance under such conditions, including an equivalent number of discounts.

For performance optimization, I limited discounts to apply only to SKUs or categories (as specified in the request). 
Utilizing nullable foreign keys for products or categories improves query performance. Additionally, I consolidated 
product search and discount evaluation into a single query. For pagination, I reused the search query and simply counted 
the rows for efficient results.

The cache TTL is intentionally set to a low value to showcase the improvement in response time starting from the second 
request onward. The TTL can be adjusted via an environment variable. Additionally, the Symfony cache can be cleared, 
and Redis can be flushed simultaneously using the command `make clear-cache`.

I configured mock versions of the database and Redis cache using Symfony's in-memory storage services during testing.