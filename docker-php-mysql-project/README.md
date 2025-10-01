# Docker PHP MySQL Project

A dockerized development environment with PHP 5.6.40, MySQL 5.5.60, and phpMyAdmin 4.9.

## Services

- **PHP 5.6.40**: Apache web server with PHP
- **MySQL 5.5.60**: Database server
- **phpMyAdmin 4.9**: Database management interface

## Quick Start

1. **Copy the environment file**:
```bash
cp .env.example .env
```

2. **Configure your credentials** (optional):
Edit `.env` file to change database credentials and ports.

3. **Start the services**:
```bash
docker-compose up -d
```

4. **Access the applications**:
- PHP Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- MySQL: localhost:3306

## Environment Configuration

All sensitive credentials are stored in the `.env` file (not committed to git).

### Default Configuration (.env.example)

```bash
# Database Configuration
MYSQL_ROOT_PASSWORD=rootpassword
MYSQL_DATABASE=cine_plus
MYSQL_USER=manager
MYSQL_PASSWORD=4dm1n
MYSQL_HOST=mysql
MYSQL_PORT=3306

# Application Database Configuration
DB_SERVER=mysql
DB_USER=manager
DB_PASSWORD=4dm1n
DB_NAME=cine_plus
```

**Important**: The application automatically uses environment variables for database connection. No need to edit PHP files.

## Project Structure

```
docker-php-mysql-project/
├── app/                    # PHP application files
│   ├── index.php          # Main page
│   ├── phpinfo.php        # PHP configuration info
│   └── test-db.php        # Database connection test
├── config/                 # Configuration files
│   └── php.ini            # PHP configuration
├── docker/
│   ├── php/
│   │   └── Dockerfile     # PHP container configuration
│   └── mysql/
│       └── init.sql       # Database initialization script
├── docker-compose.yml      # Docker services configuration
└── .env.example           # Environment variables example
```

## Useful Commands

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# View logs
docker-compose logs -f

# Restart a specific service
docker-compose restart php

# Execute commands in PHP container
docker-compose exec php bash

# Execute MySQL commands
docker-compose exec mysql mysql -u root -p
```

## Development

Place your PHP files in the `app/` directory. They will be automatically available at http://localhost:8080/

The MySQL database is persistent and data is stored in a Docker volume.