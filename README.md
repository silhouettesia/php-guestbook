# php-guestbook
Guestbook written in php

## Installation
- Create database
  - Change the username or password in `create_database.sql` if you wish
  - Run the commands in `create_database.sql`
    - `mysql -u username -p < create_database.sql`
  - Create a admin user (admin user can delete messages)
    - `insert into admin values('admin',sha1('password'),'example@mail.com','http://example.com');`
  - Modify the account and password in `db_fns.php` to connect to your database
