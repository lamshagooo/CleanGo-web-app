
# CleanGo – Cleaning Service Booking Application

## Project Description

CleanGo is a web application that connects customers
with professional cleaning service providers.

Customers can browse cleaners, view their profiles,
select cleaning services, and book appointments.

The application is developed as a university project
for the Web Programming course.

## Technologies

### Frontend
- HTML5
- CSS3
- JavaScript
- Bootstrap 5
- Single Page Application (SPA)

### Backend
- PHP
- FlightPHP REST API
- MySQL
- PDO
- JWT Authentication (planned for Milestone 4)
- OpenAPI / Swagger

## User Roles

### Customer
- Register and log in
- Browse available cleaners
- View cleaner profiles
- Book cleaning services
- View personal bookings
- Leave reviews (planned)

### Cleaner
- View cleaner dashboard
- View appointments
- Manage cleaning profile (planned)

### Admin
- View admin dashboard
- Manage users (planned)
- Manage cleaners (planned)
- Manage bookings (planned)

## Database Design

The planned database contains six entities:

1. Users
2. CleanerProfiles
3. Services
4. CleanerServices
5. Bookings
6. Reviews

The draft Entity Relationship Diagram is available
in docs/CleanGoERD.png.

## Current Features – Milestone 3

- Service classes with validation for all six entities
- REST API with FlightPHP (GET, POST, PATCH, DELETE) returning JSON
- OpenAPI documentation and Swagger UI at `/docs`
- Address search with OpenStreetMap Nominatim (`GET /locations/search`)

## Features – Milestone 1

- Responsive frontend layout
- SPA navigation using JavaScript
- Home page
- Cleaner listing
- Individual cleaner profiles
- Booking form with price estimation
- Login and registration forms
- My Bookings page
- Cleaner Dashboard
- Admin Dashboard
- Form validation

All current data is for demonstration purposes.

## External API

Milestone 3 uses OpenStreetMap Nominatim to search for cleaning locations by address.
In Swagger, open `GET /locations/search`, choose **Try it out**, and enter a public
address such as `Vijecnica, Sarajevo`. The response contains up to five matches
with a display address, latitude, and longitude.

The public service is for manual searches using public addresses. Use no personal
or confidential addresses, autocomplete, or bulk requests. CleanGo limits requests
to one per second per server and displays OpenStreetMap attribution.
If the provider fails or times out, the API returns a short error message and
writes the problem to `backend/logs/external-api.log`.
Set the server environment variable `NOMINATIM_SEARCH_URL` to switch to another
compatible Nominatim server. The public endpoint requires no API key.
[Nominatim usage policy](https://operations.osmfoundation.org/policies/nominatim/)

## Project Structure

- frontend/ – User interface
- backend/ – PHP REST API, services, DAOs, Swagger and tests
- docs/ – ERD and AI usage documentation
- AGENTS.md – AI coding instructions
- README.md – Project documentation

## Running the Project Locally

1. Install XAMPP.
2. Copy the CleanGo-web-app folder into htdocs.
3. Start Apache in XAMPP.
4. Open the application in your browser:

http://localhost/CleanGo-web-app/frontend/index.html

The frontend currently uses demo data and does not
require a database connection.

### Running the Milestone 3 API

1. Start MySQL or MariaDB. If you are setting up a fresh database, import
   `database/cleango.sql`. Keep your local settings in `backend/config/config.php`
   and use `backend/config/config.example.php` as the template.
2. Use PHP with PDO MySQL, cURL and mbstring enabled. With XAMPP, use
   `C:\xampp\php\php.exe`. From the project root run
   `composer install`.
3. Start the local API with the following command.

   ```powershell
   php -S 127.0.0.1:8083 -t backend/public backend/public/index.php
   ```

4. Open [Swagger UI](http://127.0.0.1:8083/docs) and try `GET /services`.
   Swagger UI loads its JavaScript and CSS from a CDN and needs internet access.
   This API is for local demos while authentication is scheduled for Milestone 4.

### Running the Milestone 3 tests

Use a test database loaded with `database/cleango.sql`. Point the local ignored
database config at that test database before starting the API. The tests create
and remove their own records.

```powershell
php backend/tests/smoke.php http://127.0.0.1:8083 crud
```

For repeatable location tests, start the local fake provider in one terminal.

```powershell
php -S 127.0.0.1:8084 -t backend/tests backend/tests/nominatim_stub.php
```

Start another API instance pointing to the fake provider in a second terminal.

```powershell
$env:NOMINATIM_SEARCH_URL = 'http://127.0.0.1:8084/search'
php -S 127.0.0.1:8085 -t backend/public backend/public/index.php
```

Run the test from a third terminal.

```powershell
php backend/tests/smoke.php http://127.0.0.1:8085 locations
```

## Future Development

Milestone 4:
- JWT authentication
- Role-based authorization
- Logging and middleware
- Frontend/backend integration

Milestone 5:
- Final validation
- Deployment
- Final documentation

## AI Usage

AI assistance is documented in docs/AI_LOG.md.

## Deployment

Not deployed yet.
