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

## Automatic run on linux using crontab

1. create script scrapper_script.sh in your home directory.

 ```bash
 #!/bin/bash

 mkdir -p "$HOME/tmp"
 PIDFILE="$HOME/tmp/scrapper.pid"

 if [ -e "${PIDFILE}" ] && (ps -u $(whoami) -opid= | grep -P "^\s*$(cat ${PIDFILE})$" &> /dev/null); then
     echo "Already running."
     exit 99
 fi

 CURRENTDATE=`date +"%d%m%Y_%H%M"`

 (cd "$HOME/git/AI_BIEDRONKI/Scrapper"; php bin/console app:scrap_zut_api --no-debug --env=prod) >> $HOME/tmp/scrapper_$CURRENTDATE.log &

 echo $! > "${PIDFILE}"
 chmod 644 "${PIDFILE}"
 ```

2. Make it executable

 ```bash
 chmod +x scrapper_script.sh
 ```

3. Run crontab -e.
   
 ```bash
 crontab -e
 ```

4. Add new record.
   
 ```bash
 */30 * * * * bash $HOME/scrapper_script.sh
 ```

5. Save crontab.
