#KTsivkov 2020

#SETUP
1. composer install
2. npm install
3. npm run dev
4. php artisan migrate
5. copy and edit the env file
6. php artisan companies:refresh
7. php artisan serve

#Env file example

```dotenv
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:379GedSeA4hPEWm0pzG7S5lF5LgQKhUAg8yLkUi26aw=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=username
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_FROM_ADDRESS=ktsivkov@excercise19.com
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

#Project wise configuration below

#Endpoint should be a valid URL address
SEEDER_ENDPOINT="https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json"
#The method should be a valid HTTP request method
SEEDER_METHOD="GET"
#Frequency is stored as seconds (It's about how many seconds should the records be updated after the last update)
SEEDER_AUTOUPDATE_FREQUENCY=10

SEEDER_STRUCTURE="json"

SEEDER_STRUCTURE_COMPANY_NAME="Company Name"
SEEDER_STRUCTURE_FINANCIAL_STATUS="Financial Status"
SEEDER_STRUCTURE_ROUND_LOT_SIZE="Round Lot Size"
SEEDER_STRUCTURE_SECURITY_NAME="Security Name"
SEEDER_STRUCTURE_SYMBOL="Symbol"
SEEDER_STRUCTURE_TEST_ISSUE="Test Issue"
SEEDER_STRUCTURE_MARKET_CATEGORY="Market Category"

#Endpoint should be a valid URL address
DATASERVER_ENDPOINT="https://www.quandl.com/api/v3/datasets/WIKI/AAPL.csv?order=asc&start_date=2003-01-01&end_date=2003-03-06"
#The method should be a valid HTTP request method
DATASERVER_METHOD=GET
#The parameter that is going to contain the ordering
DATASERVER_ORDER_PARAM="order"
#The parameter that is going to contain the start date
DATASERVER_STARTDATE_PARAM="start_date"
#The parameter that is going to contain the end date
DATASERVER_ENDDATE_PARAM="end_date"
#What is the date format that the endpoint accepts
DATASERVER_DEFAULT_DATE_FORMAT="Y-m-d"
#What is the default ordering that we request
DATASERVER_DEFAULT_ORDERING="asc"

```
