
# CleanGo – AI Agent Instructions

## Project Overview
CleanGo is a web application that connects customers
with professional home cleaners.

Customers can browse cleaners, view profiles, and
book cleaning services.

## Technologies
- Frontend: HTML, CSS, JavaScript, Bootstrap
- Backend: PHP, FlightPHP
- Database: MySQL with PDO
- API: REST API
- Authentication: JWT (planned)

## Project Structure
- frontend/views – HTML pages
- frontend/css – Styles
- frontend/js – JavaScript
- backend/routes – API endpoints
- backend/services – Business logic
- backend/dao – Database access
- backend/middleware – Authentication and validation
- backend/config – Configuration
- backend/logs – Application logs
- docs – Project documentation

## Coding Rules
1. Keep code simple and beginner-friendly.
2. Use clear English names for variables and functions.
3. Keep frontend and backend code separate.
4. Use PDO for database operations.
5. Use FlightPHP for REST API routes.
6. Do not hardcode passwords or API keys.
7. Use JavaScript fetch() for frontend API requests.
8. Keep the frontend as a Single Page Application.
9. Do not delete existing functionality without checking.
10. Add comments where they help explain the code.

## AI Usage Rules
- AI can help explain, write, and debug code.
- All generated code must be reviewed and tested.
- Avoid unnecessary libraries and complex frameworks.
- Follow the professor's milestone requirements.
- Document important AI assistance in docs/AI_LOG.md.

----------------------------------------------------
## Milestone 2 – Database and DAO Layer

### Database
- Database name: cleango_db
- Database system: MySQL / MariaDB
- Database connection: PHP PDO
- Database schema and sample data: database/cleango.sql

The database contains six related tables:
1. users
2. cleaner_profiles
3. services
4. cleaner_services
5. bookings
6. reviews

The tables use primary keys and foreign keys to define relationships.

### DAO Layer
The DAO (Data Access Object) layer is located in backend/dao.

BaseDao.php contains reusable CRUD methods:
- insert() – Create a new record
- getAll() – Read all records
- getById() – Read one record
- update() – Update a record
- delete() – Delete a record

The project includes six DAO classes:
- UserDao
- CleanerProfileDao
- ServiceDao
- CleanerServiceDao
- BookingDao
- ReviewDao

Each DAO extends BaseDao and can include additional methods for its table.

CleanerServiceDao uses special methods because the cleaner_services table has a composite primary key.

### Database Security
- Use PDO prepared statements for database queries.
- Validate column names before INSERT and UPDATE operations.
- Store local database credentials in backend/config/config.php.
- Do not commit config.php to GitHub.
- Use backend/config/config.example.php as a configuration template.
- Never store plain-text passwords in the database.

### Testing
The DAO layer was tested using PHP CLI and backend/test_connection.php.

CRUD operations were successfully tested for all six entities.

Temporary records were created, updated, and deleted during testing.

