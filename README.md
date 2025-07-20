# Mitfahrerspiele

Mitfahrerspiele is a web application that provides a collection of games to play in the car during a road trip.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

- Docker
- Docker Compose

### Installing

1.  Clone the repository:

    ```
    git clone https://github.com/achim-kraemer-com/mitfahrerspiele.git
    ```

2.  Navigate to the `docker` directory:

    ```
    cd mitfahrerspiele/docker
    ```

3.  Build and start the Docker containers:

    ```
    docker-compose up -d
    ```

Your application will be available at `http://localhost`.

## Services

The following services are included in the Docker setup:

-   **`db`**: MariaDB 10.1.44 database service.
-   **`apache`**: Apache web server with PHP.
-   **`apache-xdebug`**: Apache web server with Xdebug enabled for development.
-   **`mailcatcher`**: A simple SMTP server to catch emails sent from the application.
-   **`adminer`**: A full-featured database management tool.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.