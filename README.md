# Medium support

This repository is used as support for the medium article : https://medium.com/@PixelKodeKrafter/getting-started-with-laravel-projects-using-docker-my-basic-configuration-eb86d795dfa0

## Prerequisites

Before getting started, ensure that you have the following installed on your machine:
•	[Docker](https://www.docker.com/get-started/)
•	[Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started
### Clone the repository

Clone this repository to your local machine:
```shell 
  git clone git@github.com:mouize/demo-modular-architecture.git
  ```  

### Set up environment variables for the docker

Create a `.env` file in the **docker folder** and copy the contents of the `.env.example` file into it. You can modify the values in the `.env` file to suit your needs.

### Set up environment variables for the Laravel project

Create a `.env` file in the **app folder** and copy the contents of the `.env.example` file into it. You can modify the values in the `.env` file to suit your needs.

### Init the project

Run the following command to initialize the project so it will launch your docker & install the laravel project (dependencies, migrations, ...)

```shell 
make init-project
```

