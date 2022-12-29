**A brief description of the project:**

1. This project show cryptocurrency real time price, coins price changes of the 1h and 24h time period.
2. Also, it has got user part with buy/sell and short option.
3. User can see his coins profit in wallet, and he can send crypto to his friends.

**Getting started:**
You can copy project code from github and use it.

**To use this project you need to be installed**
1. PHP 8.1.13
2. Composer 2.4.4
3. MySql 8.0
4. vlucas/phpdotenv 5.5
5. nikic/fast-route 1.3
6. twig/twig 3.4
7. doctrine/dbal 3.5
8. php-di/php-di 6.4   

**Installing**
1. Clone the repository: https://github.com/Parmuhina/Project.git
2. Install dependencies:
   1. Install composer:composer install
   2. To get refresh your project, make: composer dump-autoload
   3. Install phpdotenv: composer require vlucas/phpdotenv
   4. Install fast-route: composer require nikic/fast-route
   5. Install twig: composer require twig/twig
   6. Install dbal: composer require doctrine/dbal
   7. Install php-di: composer require php-di/php-di
3. Install MySql:
   1. Install MySql to the computer from https://dev.mysql.com/downloads/
   2. Install the PHP MySQL extension
   3. After installing the PHP MySQL extension, you should be able to connect
   to a MySQL database from your PHP scripts. To do this, you will need to use the mysqli extension.
   
4. Copy .env.example file, rename it to the .env and chande data in example into "" with your own personal data.

5. You need to register in https://coinmarketcap.com/api/ and get your own Apy key for the requests.
6. To import the database you can use [db_structure.sql](db_structure.sql) file.
7. You can run project from terminal: 
    php -S localhost:8000

**In this project are used:**
1. Bootstrap 5: https://getbootstrap.com/
2. Tailwind: https://tailwind.com/

**Next are shown project visual examples (because of the Api key slow work, there will be sobe blank white spaces, 
need to make refresh):**

1. How project looks like
   ![](https://github.com/Parmuhina/Project/blob/main/View.gif)
2. How can buy crypto
   ![](https://github.com/Parmuhina/Project/blob/main/Buy.gif)
4. How send crypto
   ![](https://github.com/Parmuhina/Project/blob/main/Send.gif)
5. Registration view
https://github.com/Parmuhina/Project/blob/master/registration.png
