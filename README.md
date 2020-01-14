# Custom Kanboard
Custom Kanboard is my own take at making a Trello-like platform.

It is built upon PHP with the Lumen framework (a subset of Laravel, better suited for backends without front) and with Vue and Vuetify for the frontend frameworks.

It started for me as a way to mainly train on Vue and because some of my friends wanted an open source version of Trello.

## How to use

### Docker
The easiest way to try it is with Docker and Docker Compose.

You should go [here](https://docs.docker.com/compose/install/) and follow what they say to first install Docker and then Docker Compose if it didn't come with the first (like on Linux).

Then clone this repo, go into the root and run `docker-compose up`. It should start and you can open [http://localhost:8080](http://localhost:8080) in your browser.

For now, as it uses MariaDB and as (at least on my machine) it takes a lot of time to first boot, you may have to wait a minute or two before the server migrated the database and is up and running. Maybe we'll find a solution to this problem, or I'll switch to SQLite to also get the benefit of not using a whole MySQL server.

### By hand
These instructions are mainly focused on a Linux machine, but the whole process consists in three steps and can be adapted on any setup:

1. Launch a MySQL server.
2. Launch the PHP server.
3. Launch the frontend.

#### Backend
Grab a MySQL server ([MariaDB](https://mariadb.org/) for example is a good one) and setup a user and a database. Copy `server/.env.example` to `server/.env` and put in the necessary credentials (host, port, user, password and database) you chose previously while setting up MySQL. Start the server.

Download PHP (and if you want Apache / Nginx...) and either:

- Run `php -S localhost:8000 -t public` from the `server/` directory if not using a web server.
- Point the web server home to `server/public/`, enable URL rewriting (`a2enmod rewrite` with Apache) and start it.

#### Frontend
You should have node and npm installed first. Then you just have to run `npm install` from `client/` and finally `npm run serve --open` so it launches the client and opens its page in your browser (didn't try the option).

You may have to change the URL of the API in `client/src/store.js` in the first few lines of the file to suit your setup.

## Contributions

Any help is appreciated, and if you have any question feel free to ask them in the issues board!
