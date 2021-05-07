# reset_password
Sample code for a password reset mechanism

This is the code that complements [this video example](https://www.youtube.com/watch?v=01K_JsFYtg4)

# Dependencies

* PHP 5.6+
* PDO extension
* Composer
* Local mysql server

# Installation

1. Install dependencies using composer: `composer install`
2. Create the database: `mysql < screen_casts.sql`
3. Create user `mauro`: `mysql -e "GRANT ALL on screen_casts.* TO 'mauro'@'localhost';"`
4. Edit the script `iniciar_recupero.php` and change lines 33-42 to match your SMTP configuration

# Running

Start local webserver at port 8989:

`php -S localhost:8989`

 Open a browser at http://localhost:8989
