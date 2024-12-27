# MyTheresa API Project

A PHP API project using Symfony with Redis caching and PostgresSQL database, running on FrankenPHP.

## Prerequisites

Before you begin, ensure you have the following installed:
- Docker and Docker Compose
- Make
- Git

## Installation

1. Clone the repository
```bash
git clone git@github.com:doppiogancio/challange-mytheresa.git
cd challange-mytheresa
```

2. Run the complete setup
The setup command will build the application, start it, recreate the database and load the fixtures.
Only 5 products are loaded
```bash
make setup
```

3. Add more fixtures
In order to add ~20000 fake products execute the following command
It may take up to 1 minute to complete.
```bash
make recreate-database-large
```

## Running the project 

The project runs the following services:
- API doc: http://localhost
- API: http://localhost/products
- Profiler: http://localhost/_profiler

## Test
```bash
make tests
```