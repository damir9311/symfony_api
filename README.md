This test task requires you to create an API that will import data from a 3rd party API and be able to display it. This test task should demonstrate how you structure your code and apply any appropriate design patterns.

**Features overview**

- Import customers from a 3rd party data provider and save to database
- Display a list of customers from the database
- Select and display details of a single customer from the database

**Acceptance criteria**

- Lumen or Symfony framework must be used and the database layer should be
    - [https://www.doctrine-project.org/projects/orm.html](https://www.doctrine-project.org/projects/orm.html) - for Symfony
    - [http://www.laraveldoctrine.org/docs/1.4/orm/lumen](http://www.laraveldoctrine.org/docs/1.4/orm/lumen) - for Lumen
- Implement Data Importer
    - Fetch and store a minimum of 100 users from this data provider: [https://randomuser.me/api](https://randomuser.me/api). See official documentation for fetching multiple users [API Documentation](https://randomuser.me/documentation)
    - The user data retrieved from the data provider must be stored in `customers` table of a RDBMS database
    - Import only the customers that have the Australian nationality
    - The importer should be constructed in a way that it can be used in any part of the application: services, controllers, commands, jobs, etc.
    - The importer should be designed so the data provider could be replaced later with a minimal impact on the codebase
    - If the data provider returns a customer that already exist by email then update the customer
- Implement RESTful API having two endpoints
    - `GET /customers`
        - Retrieve the list of all customers from the database. The response should contain
            - `id`
            - `fullName` (first name + last name)
            - `email`
            - `country`
    - `GET /customers/{customerId}`
        - Retrieve more details of a single customer. The response should contain
            - `id`
            - `fullName` (first name + last name)
            - `email`
            - `country`
            - `username`
            - `gender`
            - `city`
            - `phone`
- Create a console command to be able to execute the importer
- Tests must not request the 3rd party API
- The database should only store the information that is needed for this task
- You may not secure your API
- **Submit your code in a GitHub repository**

**How to run**

- Copy docker/.env.dist to docker/.env
- Edit docker/.env as you need
- Run `docker-compose build`
- Run `docker-compose up -d`
- Run `docker-compose exec fpm composer install`
- Run `docker-compose exec fpm bin/console doctrine:database:create`
- Run `docker-compose exec fpm php bin/console doctrine:migrations:migrate`
- Import customers from randomuser.me by `docker-compose exec fpm php bin/console app:customer:import`
- Now you can use the API endpoints `GET /customers` and `GET /customers/{id}`

**Run tests**

- Run `docker-compose exec fpm bin/console doctrine:database:create --env=test`
- Run `docker-compose exec fpm php bin/console doctrine:migrations:migrate --env=test`
- Run tests `docker-compose exec fpm bin/phpunit`