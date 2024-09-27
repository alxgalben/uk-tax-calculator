# Symfony Tax Calculator Application

This is a simple Symfony-based Tax Calculator application. It allows users to input a gross annual salary, calculate the applicable taxes based on predefined tax bands, and view the results. Additionally, it includes an admin panel to manage tax bands (CRUD operations) with caching and error handling.

## Features

- Input gross salary and calculate taxes.
- Display calculated net salary, tax paid annually, and monthly breakdowns.
- Admin panel for managing tax bands (Add, Edit, Delete).
- Caching mechanism for tax band lists.
- Validation of user input (e.g., positive numbers, no letters).
- Logging and error handling for cache retrieval and database operations.

---

## Requirements

- PHP 8.2 or later
- Symfony 6.x
- Composer
- MySQL or MariaDB
- A web server like Apache or Nginx

---

## Routes

/tax-calculator  --> main page
/admin/tax-band/  --> admin page
/admin/tax-band/new  --> add a new band
/admin/tax-band/edit  --> edit a band

## Installation

Follow these steps to set up the application locally.

```bash
git clone https://github.com/your-username/tax-calculator-symfony.git
cd tax-calculator-symfony

run "composer install" to install all the php dependencies needed
run "symfony server:start" to start the project
