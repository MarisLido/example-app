<p align="center"><a href="https://codnity.com/lv/" target="_blank"><img src="https://codnity.com/_next/image/?url=%2Ficons%2Fcodnity-logo.svg&w=256&q=75" width="400" alt="Laravel Logo"></a></p>

## About this project

Backend part:
- Scraper that reads data from https://news.ycombinator.com/ (title, link, points, date created).
- Stores data to a local database (MySQL).
- Scraper updates points for each article.
- It can be also be run from the console.

Frontend part:
- Accessible only by username/password.
- All scraped information displayed using DataTables, 10 entries per package.
- Possibility to delete an item - if it’s deleted, it will not be updated.

## Setup

In your terminal write:
- `composer install` to install all composer dependencies.
- `npm install` to install NPM dependencies.
- Make a copy of `.env.example` file.
- Provide your database connection data. If you want you can make sqlite connection by commenting out everything that starts with DB_, except DB_CONNECTION. Set that to 'sqlite'.
- `php artisan key:generate` to generate app encryption key.
- `php artisan migrate --seed` to generate data tables and seed default user.
- `npm run dev` and `php artisan serve` to start the application.

> [!NOTE]
> Default user = [
>    test@example.com, 
>   password,
>  ] 


> [!TIP]
> You can run artisan commands to scrape and update data:
> php artisan `scraper:scrape` and `scraper:update`
