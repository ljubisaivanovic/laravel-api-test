
# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x/installation)


Clone the repository

    git clone git@github.com:ljubisaivanovic/q-test.git


Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000


 
# Authentication
 
email: ahsoka.tano@q.agency
password: Kryze4President


# CLI Command

 To create an author from cli run this command: 
 
 php artisan create:author {email} {password} {first_name} {last_name} {birthday} {biography} {gender} {place_of_birth}

 Argument {email} and {password} are used for authentication

