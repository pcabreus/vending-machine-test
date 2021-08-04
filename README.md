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
    ...
    OK (28 tests, 45 assertions)

Run the app:

    ~/pcabreus-vm-test$ docker exec -it vending_machine_php symfony console app:run
    Vending Machine
    ===============
    #Items
    SODA: 5 - $1.5
    WATER: 10 - $1
    JUICE: 50 - $0.65
    #Change in coins
    $0.05: 100
    $0.10: 50
    $0.25: 25
    $1.00: 10
    Place your order or type `help` to see all options:
    
The app come with a set of items and changes. You can type the following commands in order to interact with the vending machine.

There are 2 operational commands you can use:

 * `exit` to stop de app
 * `help` to display the help
 
There are 5 logical commands you can use:

 * `STATUS` display the current status of the vending machine.
 * `<coins> RETURN-COIN` return the inserted money. You can set a list of coins separated by a comma before the command. e.g. `0.05,0.25, RETURN-COIN`.
 * `<coins> GET-<ITEM>` return the selected item. You can set a list of coins separated by a comma before the command. e.g. `0.05,0.25, GET-SODA`.
 * `SERVICE-ITEM` allows to update the amount of items. After set this command, you will be able to enter the selector of the item and the quantity. e.g `SODA, 5`.
 * `SERVICE-CHANGE` allows to add the amount of coins. After set this command, you will be able to enter a list of coins. e.g `0.05, 1.00, 1.00`.

## TODO

* Add new items
