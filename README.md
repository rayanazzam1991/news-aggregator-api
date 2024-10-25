# System Design
## Architecture Choice: Modular Monolith

In this project, I implemented a **modular monolith architecture** due to the diverse and distinct models, such as:

- **User Authentication**
- **Articles**
- **User Preferences**
- **System Components** (with the potential to evolve into microservices in the future)

This architecture offers several key advantages:

- **Separation of Concerns**: Each module operates independently, enhancing clarity and organization.
- **Maintainability and Testability**: Isolating functionalities allows for straightforward updates and easier testing.
- **Future Scalability**: Modules can be easily extracted into microservices if the system scales, allowing for a seamless transition to a microservices architecture.

I designed this project with flexibility in mind to support future expansion and evolving requirements. For a more in-depth understanding of my approach, please refer to the project walkthrough video.

Thank you for taking the time to explore my work!

# Building

1. **Clone the Repo**:
    ```bash
    git clone git@github.com:rayanazzam1991/news-aggregator-api.git
    ```
2. **Run Docker** on your machine or laptop.
3. **Install Dependencies**:  
   If you have Composer installed, run:
    ```bash
    composer install
    ```
   Otherwise, run:
    ```bash
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
    ```
4. **Run Sail**:
    ```bash
    ./vendor/sail/up
    ```
5. **Run Migrations**:
    ```bash
    sail artisan migrate
    ```

# QA and Testing
 - You can test the apis through the Swagger Ui from here : http://localhost/api/documentation
 - You can run test to check integration and unit test: 
    ```bash
    composer test
    ```
