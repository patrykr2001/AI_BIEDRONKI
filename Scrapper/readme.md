## Usage

To run the ZUT API scraper, use the following command:

```bash
php bin/console app:scrap_zut_api --no-debug --env=prod
```

## DataBase

1. Copy the .env file to .env.local:

 ```bash
 cp .env .env.local
 ```

2. Set up the database connection in the .env.local file.
3. Run the database migrations:

 ```bash
 php bin/console doctrine:migrations:migrate
 ```