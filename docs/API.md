# Canoe Test API

## Get Funds

**URL:** `/funds`

**Method:** `GET`

**URL Parameters:**

- `name=[string]` (optional): Filter funds by name.
- `fundManager=[integer]` (optional): Filter funds by fund manager ID.
- `start-year=[integer]` (optional): Filter funds by start year.
- `duplicates=[boolean]` (optional): If true, return only funds that have potential duplicates.

**Success Response:**

- **Code:** 200
- **Content:** Array of funds.

## Create Fund

**URL:** `/funds`

**Method:** `POST`

**Data Parameters:**

- `name=[string]` (required): The name of the fund.
- `fund_manager_id=[integer]` (required): The ID of the fund manager.
- `start_year=[integer]` (required): The start year of the fund.
- `aliases=[array]` (required): The aliases of the fund.

**Success Response:**

- **Code:** 201
- **Content:** The created fund.

## Update Fund

**URL:** `/funds/{id}`

**Method:** `PUT`

**URL Parameters:**

- `id=[integer]` (required): The ID of the fund to update.

**Data Parameters:**

- `name=[string]` (required): The new name of the fund.
- `fund_manager_id=[integer]` (required): The new ID of the fund manager.
- `start_year=[integer]` (required): The new start year of the fund.
- `aliases=[array]` (required): The new aliases of the fund.

**Success Response:**

- **Code:** 200
- **Content:** The updated fund.
