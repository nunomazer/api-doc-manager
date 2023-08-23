### Docs API Management

## Definition

This application was built in PHP/Laravel for simple docs managemnt in PDF format:

- document types;
- columns (properties) in documents by type;
- documents based on type and columns;
- download the document in PDF format;

Libs and packages:
- barryvdh/laravel-dompdf;
- darkaonline/l5-swagger;

## Instalation

- git clone;
- composer install;
- cp .env.example .env;
- php artisa key:generate;
- update env vars;
- php artisan migrate;

## Usage

- Endpoint **/api/types**
  - POST = create;
- Endpoint **/api/columns**
  - POST = create
- Endpoint **/api/documents**
  - POST = create
- Endpoint /api/pdf/{id}
  - GET = Download in PDF

API documentation (swagger) in <b>/api/documentation</b>

## License

This application is an open-sourced software licensed under the MIT license.
