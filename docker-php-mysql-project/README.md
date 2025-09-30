# Docker PHP MySQL Project

A dockerized development environment with PHP 5.6.40, MySQL 5.5.60, and phpMyAdmin 4.9.

## Services

- **PHP 5.6.40**: Apache web server with PHP
- **MySQL 5.5.60**: Database server
- **phpMyAdmin 4.9**: Database management interface

## Quick Start

1. Start the services:
```bash
docker-compose up -d
```

2. Access the applications:
- PHP Application: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- MySQL: localhost:3306

## Default Credentials

### MySQL
- Root Password: `rootpassword`
- Database: `mydb`
- User: `dbuser`
- Password: `dbpassword`

### phpMyAdmin
- Username: `root`
- Password: `rootpassword`

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