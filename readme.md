<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

# Laravel API

System built in Laravel to show my skills

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.8/installation#installation)

Clone the repository

    git clone https://github.com/fittipaldi/Laravel-API.git

Switch to the repo folder

    cd Laravel-API

Install all the dependencies using composer

    composer install

Copy the example env file and make the required configuration changes in the .env file (**PS.: Check the database credentials**) 

    cp .env.example .env 
 
Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

# API Action

    Header
        Authorization: Bearer SsHOX5O448YMBNf4

    
    GET /api/v1/contacts - List contacts params: pagination [page=1], contact name [name=Jhon], company name [company=Apple] 
    GET /api/v1/contact/id/{id} - Contact by ID
    POST /api/v1/contact/add - Add one contact params: [name, phone, email, company, note]
    DELETE /api/v1/contact/delete/{id} - Delete one contact
    PUT /api/v1/contact/edit/{id} - Edit one contact params: [name, phone, email, company, note]

    GET /api/v1/companies - List companies params: pagination [page=1]
    GET /api/v1/companies/all - List all companies and all contacts for each company
    POST /api/v1/company/add - Add one company params: [name]
    DELETE /api/v1/company/delete/{id} - Delete one company
    PUT /api/v1/company/edit/{id} - Edit one company params: [name]