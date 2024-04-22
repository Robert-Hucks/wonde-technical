# Wonde Technical Test

## Software Used
- Laravel v11 (https://laravel.com/docs/11.x)
- VueJS (https://vuejs.org)
- Hybridly (https://hybridly.dev/)
- Tailwind (https://tailwindcss.com/)
- Naive UI (https://www.naiveui.com)

## Running the project

Install Composer packages
```shell
composer install
```

Install NPM packages
```shell
npm install
```

Create a .env file and fill it out with any missing variables
```shell
cp .env.example .env
```

Run migrations and fill tables with data
```shell
php artisan migrate
php artisan app:fetch-data
```

Run the frontend
```shell
npm run dev
```
