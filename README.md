# Vending Machine Test

# Installation and configuration

Download the project:

    ~/$ git clone https://github.com/pcabreus/vending-machine-test pcabreus-vm-test
    ~/$ cd pcabreus-vm-test

Run the container with docker:
    
    ~/pcabreus-vm-test$ docker-compose up -d
    
Install dependencies:

    ~/pcabreus-vm-test$ docker exec -it vending_machine_php symfony composer install
   
Run the test

    ~/pcabreus-vm-test$ docker exec -it vending_machine_php php bin/phpunit
    OK (8 tests, 12 assertions)
