# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

CineMall is a cinema management system (Cine Plus) built with PHP 5.6 and MySQL 5.5, running in a Docker containerized environment. The system handles ticket sales, inventory management, movie scheduling, pricing, and reporting for cinema operations.

## Docker Environment

### Starting/Stopping Services

```bash
# Start all services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Restart specific service
docker-compose restart php
```

### Service Access

- PHP Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- MySQL: localhost:3306

### Database Credentials

Located in `docker-php-mysql-project/app/.env`:
- Root Password: Check `.env` file (MYSQL_ROOT_PASSWORD)
- Manager Password: Check `.env` file (MYSQL_MANAGER_PASSWORD)

### Working in Containers

```bash
# Execute commands in PHP container
docker-compose exec php bash

# Execute MySQL commands
docker-compose exec mysql mysql -u root -p
```

## Application Architecture

### Module Structure

The application is organized into multiple independent modules under `docker-php-mysql-project/app/`, each with similar structure:

- **admin**: Main administration panel (login, configuration, reports, maintenance)
- **taquilla** / **taquilla_nueva**: Box office/ticket sales modules
- **inventario**: Inventory management
- **carreta**: Shopping cart functionality
- **video**: Video/movie display for customers
- **publicidad**: Advertising management
- **precios** / **preciosnuevo** / **pantallaprecios**: Price management and display
- **pantalla** / **pantalla3**: Screen/display modules
- **proyeccion-mall34**: Projection scheduling
- **asseco**: phpMyAdmin integration (version 4.9)

### Common Module Pattern

Each module typically contains:
- `include/bd.inc.php`: Database connection configuration
- `include/func.combo.php`: Dropdown/combo box functions
- `include/func.glb.php`: Global utility functions
- `include/func.ajax.php`: AJAX request handlers (in some modules)
- `main/`: Calendar and request handling JavaScript
- `pdf/`: PDF generation (FPDF library)
- Entry PHP files for different views/actions

### Database Connection

Database connections are established in each module's `include/bd.inc.php` file using:
- Server: `127.0.0.1:3406` (or `192.168.0.1` in some configs)
- User: `manager`
- Password: `4dm1n`
- Database: `cine_plus`
- Uses deprecated `mysql_connect()` functions (PHP 5.6)

The main SQL schema is in `app/cine_plus (4).sql` (~9000 lines).

### Key Architecture Notes

1. **No OOP**: The codebase uses procedural PHP, not classes or OOP patterns
2. **Legacy MySQL**: Uses deprecated `mysql_*` functions instead of mysqli or PDO
3. **Timezone**: Set to 'America/Caracas' in database connection files
4. **Character Encoding**: Latin1/ISO-8859-1 encoding throughout
5. **Module Independence**: Each module is largely self-contained with duplicated libraries (calendar, PDF, etc.)

### Common Constants (defined in bd.inc.php)

```php
AGREGAR = 1       // Add action
MODIFICAR = 2     // Modify action
MANTENIMIENTO     // Maintenance section URL
PROGRAMACION      // Programming section URL
CONFIGURACION     // Configuration section URL
REPORTES          // Reports section URL
DESCUENTO         // Discount section URL
```

## Development Workflow

### Adding New Features

1. Identify the relevant module(s) in `app/`
2. Use existing include files (`func.combo.php`, `func.glb.php`) for common operations
3. Follow the procedural programming pattern used throughout
4. Update the database schema if needed (test via phpMyAdmin first)

### Database Changes

1. Access phpMyAdmin at http://localhost:8081
2. Test SQL changes manually first
3. Update the SQL dump file if making structural changes
4. Database is persistent in Docker volume `mysql_data`

### Testing Changes

Place PHP files in the `app/` directory - they are automatically available at http://localhost:8080/

The `app/` directory is mounted as a volume, so changes are reflected immediately without rebuilding containers.

## Important Technical Details

- **PHP Version**: 5.6.40 (legacy, end-of-life)
- **MySQL Version**: 5.5.60 (legacy)
- **Apache**: Enabled mod_rewrite
- **PHP Extensions**: mysqli, mysql, pdo, pdo_mysql
- **Calendar Library**: Custom JavaScript calendar with multiple language files
- **PDF Generation**: FPDF library (duplicated across modules)
- **jPlayer**: Video player library in `video/` module with Aurora codec support

## File Locations

- Docker configuration: `docker-php-mysql-project/docker-compose.yml`
- PHP container: `docker-php-mysql-project/docker/php/Dockerfile`
- MySQL init: `docker-php-mysql-project/docker/mysql/init.sql`
- PHP config: `docker-php-mysql-project/config/php.ini`
- Application root: `docker-php-mysql-project/app/`
- Database schema: `docker-php-mysql-project/app/cine_plus (4).sql`
