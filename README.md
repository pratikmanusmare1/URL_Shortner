Requirements
 PHP 8.2 or higher
 Composer
 Node.js & npm (for asset compilation)
 MySQL

Installation Steps

Note: code of ".env" file is given in ".env.example" file

1. Clone the project present in master branch

2. Install Dependencies
    composer install
    npm install

3. import MySQL Database given in root folder OR create a database and run the migration

4. Seed the Database (Creates SuperAdmin)
   php artisan db:seed

Default SuperAdmin Credentials:
 Email: `superadmin@gmail.com`
 Password: `Pass@123`

5.Run command in 2 different terminals:
npm run build
and 
php artisan serve


Steps After Setup

1. Login: with SuperAdmin credentials (see above)
2. Create a Company: via the navbar "Companies" button
3. Invite Users: to the company as Admin or Member
4. invitation email will be sent inside Log file (not implemented SMTP for security purpose)
5. Shorten URLs: by creating short links from the "URLs" section
