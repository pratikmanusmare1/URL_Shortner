Requirements PHP 8.2 or higher Composer Node.js & npm (for asset compilation) MySQL

Installation Steps

Note: code of ".env" file is given in ".env.example" file

Clone the project present in master branch

Install Dependencies composer install npm install

import MySQL Database given in root folder OR create a database and run the migration

Seed the Database (Creates SuperAdmin) php artisan db:seed

Default SuperAdmin Credentials: Email: superadmin@gmail.com Password: Pass@123

5.Run command in 2 different terminals: npm run build and php artisan serve

Steps After Setup

Login: with SuperAdmin credentials (see above)
Create a Company: via the navbar "Companies" button
Invite Users: to the company as Admin or Member
invitation email will be sent inside Log file (not implemented SMTP for security purpose)
Shorten URLs: by creating short links from the "URLs" section
