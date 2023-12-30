# Canoe Test

## Introduction

This project is a `Take Home Test`, which is part of a selection process by `Canoe` and should not be used for other purposes.

This project is built using `Lumem 10.x`

## API Documentation

Detailed API documentation can be found [here](docs/API.md).

## Database ER Diagram

The database ER diagram can be found [here](docs/ER_diagram.png).

## Installation

1. Clone the repository: `git clone git@github.com:hdviegas/canoe-test.git`
2. Initialize the project: `make init`
3. Start the server: `make start`
4. Run tests: `make test`
5. Refresh DB: `make refresh_db`

## Usage

Please refer to the API documentation for more details.

## Credits

[Hilthermann Viegas](https://www.linkedin.com/in/hdviegas/)

## Extra considerations
*Q: How will your application work as the data set grows increasingly larger?*

A: The application is initially built using MySQL, which has a very expressive load capacity. If we need to migrate the DB at some point, it is easily implemented by switching DB drivers from Lumem.


---

*Q: How will your application work as the # of concurrent users grows increasingly larger?*

A: This application can run in multiple instances, and the number of instances can grow by demand without losing performance and reliability.
