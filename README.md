
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

### Backend (Planned)
- PHP
- FlightPHP REST API
- MySQL
- PDO
- JWT Authentication
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

## Current Features – Milestone 1

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

A meaningful external API integration is planned
for a later milestone.

The selected API and its functionality will be
documented after implementation.

## Project Structure

- frontend/ – User interface
- backend/ – PHP REST API (planned)
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

## Future Development

Milestone 2:
- MySQL database
- SQL schema
- DAO layer using PDO

Milestone 3:
- REST API with FlightPHP
- Services and routes
- Swagger / OpenAPI documentation
- External API integration

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
