# Data Import Command

A Symfony Console Command (`data:import`) designed to validate, process, and import data from a CSV file into a database.

---

## Features

- CSV validation and reading via `DataReaderService`(currently only supports csv data).
- Data insertion into MariaDB with `DatabaseWriter` (currently appends data without checking for duplicates or replacing existing data).
- Logging for progress and error tracking at: `/project/var/log/`.
- Dockerized setup for seamless development.
- **CSV Storage**:
  - Folder for storing CSV files: `/project/resources/uploads/`
  - Folder for test-related CSV files: `/project/tests/resources/uploads/`

---

## Quick Start

1. **Start Docker**:
   ```bash
   docker-compose up -d
   ```

2. **Run DataWriter Migrations**:
   ```bash
   docker exec -it php php bin/console doctrine:migrations:migrate
   ```

3. **Execute Command**:
   ```bash
   docker exec -it php php bin/console data:import <filePath>
   ```

Example:
```bash
docker exec -it php php bin/console data:import /project/tests/resources/uploads/feed.csv
```

---

## Testing

Run tests:
```bash
docker exec -it php vendor/bin/phpunit tests/Unit
```

---

## Extensibility

- **Additional Formats**: 
  - Designed with flexibility, allowing support for other data types such as JSON and XML to be added seamlessly.
  - Leverages Doctrine's capabilities, optimized for MySQL, but can easily integrate with other databases using Doctrine's built-in features.

- **More Options**: 
  - Features like batch processing, database rollback on failure, and fallback methods can be added if needed.
---

## Notes

The current implementation of `DatabaseWriter` appends data to the database without checking for duplicate entries or replacing existing data. Handling duplicates or overwriting data has been deliberately kept out of scope for this version.
