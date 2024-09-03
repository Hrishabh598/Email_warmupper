# Email Warmupper

## Overview

Email Warmupper is a Laravel-based application designed to automate the process of warming up email accounts. This helps in improving email deliverability by gradually increasing the email sending volume and frequency, which can prevent emails from landing in spam folders.

## Features

- Automated email sending at scheduled intervals.
- Dynamic configuration of multiple email accounts.
- Dashboard for monitoring email deliverability metrics.

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Hrishabh598/Email_warmupper.git
   cd Email_warmupper
   ```
2. **Install dependencies**:\
Do install other dependicies in which composer is included.
```bash
composer install
```


3. **Apply Migrations**
```bash
php artisan migrate
```

4. **start the application**

```bash
php artisan serve
php artisan queue:work
```
