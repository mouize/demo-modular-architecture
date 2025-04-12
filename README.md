# docker-base

This is a PHP Laravel project setup using Docker. It is configured to work with Nginx, PHP-FPM, MariaDB, Redis, and Mailhog for a clean, fast, and efficient development environment. This setup ensures that you have a reproducible, isolated environment for your Laravel application, and all necessary services are containerized.

## Prerequisites

Before getting started, ensure that you have the following installed on your machine:
•	[Docker](https://www.docker.com/get-started/) 
•	[Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started
### Clone the repository

Clone this repository to your local machine:
    
    ```shell
    git clone git@github.com:mouize/docker-base.git
    ```

### Set up environment variables

Create a `.env` file in the root of the project directory and copy the contents of the `.env.example` file into it. You can modify the values in the `.env` file to suit your needs.

### Build the Docker containers

Build the Docker containers by running the following command:

    ```shell
    make up
    ```

After running the command, you should see the Docker containers being built and started. You can access to your application on the port you specified in the `.env` file.
