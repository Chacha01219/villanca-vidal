# Weather Service API, version 1

Every request needs the header `X-API-Key`.

Base path: `/api/v1`

The key is stored in the `.env` file as `API_KEY` on the server. Send it like this:

```
X-API-Key: <API_KEY>
```

There are three endpoints. `POST` saves data. `GET` only reads data, so calling it
again is always safe.

Errors always have the same shape:

```json
{
    "status": 422,
    "error": {
        "code": "invalid_limit",
        "message": "limit must be 1 to 50."
    }
}
```

Success responses always have the same shape:

```json
{
    "status": 200,
    "data": { }
}
```

A weather reading always looks like this:

```json
{
    "id": 4,
    "city": "Iligan City",
    "temperatureC": 26.5,
    "fetchedAt": "2026-10-11T05:57:53"
}
```

`fetchedAt` is in UTC, which is 8 hours behind Philippine time.

---

## POST /weather/refresh

What it does:
Gets the current temperature for Iligan City from the weather source and saves it
as a new reading. This is the only endpoint that writes data.

Success response:
`201 Created` with the saved reading in `data`.

```json
{
    "status": 201,
    "data": {
        "id": 4,
        "city": "Iligan City",
        "temperatureC": 26.5,
        "fetchedAt": "2026-10-11T05:57:53"
    }
}
```

Errors:

| Status | Code | When it happens |
| --- | --- | --- |
| 401 | `unauthorized` | The `X-API-Key` header is missing or wrong. |
| 502 | `upstream_error` | The weather source could not be reached, or it sent no temperature. Nothing is saved. |

Example:

```
curl.exe -X POST -H "X-API-Key: <API_KEY>" "http://localhost:8080/api/v1/weather/refresh"
```

---

## GET /weather/logs

What it does:
Returns saved readings, newest first.

Options:

| Option | Values | Default | Meaning |
| --- | --- | --- | --- |
| `limit` | Whole number from 1 to 50 | `10` | How many readings to return. |
| `format` | `json` or `xml` | `json` | Response format. |

Success response:
`200 OK`. With `format=json` the body is JSON with the readings in `data`.
With `format=xml` the body is XML with a `weatherReport` root element and one
`reading` element per row. The XML is checked against
`app/Schemas/weather-report.xsd` before it is sent.

```json
{
    "status": 200,
    "data": [
        {
            "id": 4,
            "city": "Iligan City",
            "temperatureC": 26.5,
            "fetchedAt": "2026-10-11T05:57:53"
        },
        {
            "id": 3,
            "city": "Iligan City",
            "temperatureC": 26.5,
            "fetchedAt": "2026-10-11T05:57:44"
        }
    ]
}
```

```xml
<?xml version="1.0" encoding="UTF-8"?>
<weatherReport>
  <reading>
    <id>4</id>
    <city>Iligan City</city>
    <temperatureC>26.5</temperatureC>
    <fetchedAt>2026-10-11T05:57:53</fetchedAt>
  </reading>
</weatherReport>
```

Errors:

| Status | Code | When it happens |
| --- | --- | --- |
| 401 | `unauthorized` | The `X-API-Key` header is missing or wrong. |
| 422 | `invalid_limit` | `limit` is not a whole number from 1 to 50 (for example `500`, `abc`, or `-1`). |
| 422 | `invalid_format` | `format` is not `json` or `xml` (for example `csv`). |
| 500 | `invalid_xml` | The XML did not match the schema. The service sends this instead of bad data. |

Examples:

```
curl.exe -H "X-API-Key: <API_KEY>" "http://localhost:8080/api/v1/weather/logs?limit=2"
curl.exe -H "X-API-Key: <API_KEY>" "http://localhost:8080/api/v1/weather/logs?limit=2&format=xml"
```

---

## GET /weather/logs/{id}

What it does:
Returns one saved reading. `{id}` is a whole number.

Success response:
`200 OK` with the reading in `data`.

```json
{
    "status": 200,
    "data": {
        "id": 1,
        "city": "Iligan City",
        "temperatureC": 26.5,
        "fetchedAt": "2026-10-11T05:57:41"
    }
}
```

Errors:

| Status | Code | When it happens |
| --- | --- | --- |
| 401 | `unauthorized` | The `X-API-Key` header is missing or wrong. |
| 404 | `not_found` | No reading with that id. |

Example:

```
curl.exe -H "X-API-Key: <API_KEY>" "http://localhost:8080/api/v1/weather/logs/1"
```

---

## Error codes at a glance

| Status | Code | Meaning |
| --- | --- | --- |
| 401 | `unauthorized` | Missing or wrong API key. |
| 404 | `not_found` | No weather log with that id. |
| 422 | `invalid_limit` | `limit` must be 1 to 50. |
| 422 | `invalid_format` | `format` must be `json` or `xml`. |
| 500 | `invalid_xml` | XML did not match the schema. |
| 502 | `upstream_error` | Weather source unreachable or returned no temperature. |
