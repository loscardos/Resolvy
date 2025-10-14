
# Resolvy Ticketing System

Resolvy is a comprehensive helpdesk and customer support ticketing system built with the Laravel framework. It provides features for managing customers, subscriptions, service packages, and support tickets. The system also includes a complete RESTful API for integration with other services.

## Prerequisites

Before you begin, ensure you have the following installed on your local machine:

* PHP (\>= 8.0)
* Composer
* Node.js & npm
* A database server (e.g., MySQL, MariaDB)

## Installation

Follow these steps to get your development environment set up and running.

### 1\. Clone the Repository

Clone the project to your local machine using git:

```bash
git clone https://github.com/loscardos/Resolvy.git
cd resolvy
```

### 2\. Install Dependencies

Install the required PHP and JavaScript dependencies:

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3\. Environment Configuration

Create your local environment file and generate the application key:

```bash
# Create .env file from the example
cp .env.example .env

# Generate a new application key
php artisan key:generate
```

### 4\. Database Setup

Open the `.env` file in your code editor and update the database connection details:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=resolvy
DB_USERNAME=root
DB_PASSWORD=
```

Once configured, run the database migrations and seeders to create the necessary tables and populate them with initial data (including the admin user and permissions).

```bash
# Run migrations
php artisan migrate

# Run seeders
php artisan db:seed
```


## Running the Application

To start the local development server, run the following command:

```bash
php artisan serve
```

The application will be available at `http://127.0.0.1:8000`.

## Default Admin Credentials

After seeding the database, you can log in to the admin panel with the following default credentials:

- **Email:** `admin@admin.com`
- **Password:** `password`

## API Documentation (Swagger)

This project includes API documentation generated via Swagger (OpenAPI). To generate or update the documentation, run the following command:

```bash
php artisan l5-swagger:generate
```

You can access the API documentation in your browser by visiting:
[http://127.0.0.1:8000/api/documentation](https://www.google.com/search?q=http://127.0.0.1:8000/api/documentation)
