# Thrivecart Architech Labs Code Test

Welcome!
1. [Introduction](#introduction)
2. [System Requirements](#system-requirements)
3. [How To Run](#how-to-run)

## Introduction

This project is developed with PHP 8.3 and should be run via command line.  
  
Orders are stored in a generated file inside ./data/basket.json  
  
I skip PHPUnit and Docker, but I can provide it later. I hope the other additional features can cover it up.

## System-Requirements

- PHP 8.3
- Windows OS (developed in windows and not tested yet at linux/macOS)

## How-To-Run

From project root folder type in **php index.php**, it will show options of available commands.
```
$ php index.php
No command provided.
Available commands:
  add-item: Add item into basket
  remove-item: Remove item from the basket
  get-items: Get all items in the basket
  get-checkout-summary: Get summary of costs, discounts and offers
  help: Show this help message
```

### Add new item to basket 

From project root folder type in **php index.php add-item [item-code/product-code]**.
```
$ php index.php add-item R01
Item R01 has been added to basket.
Here's your current basket:
Array
(
    [0] => App\repository\Product Object
        (
            [name] => Red Widget
            [code] => R01
            [price] => 32.95
            [currency] => USD
        )

)
```

### Remove item from the basket
From project root folder type in **php index.php remove-item [item-code/product-code]**.

```
$ php index.php remove-item R01
Item R01 has been removed from the basket.
Here's your current basket:
Array
(
)
```

### Get current items in the basket
From project root folder type in **php index.php get-items**
```
$ php index.php get-items
Here's your current basket:
Array
(
    [0] => stdClass Object
        (
            [name] => Red Widget
            [code] => R01
            [price] => 32.95
            [currency] => USD
        )

    [1] => stdClass Object
        (
            [name] => Green Widget
            [code] => G01
            [price] => 24.95
            [currency] => USD
        )

)
```
### Get checkout summary / orders summary
From project root folder type in **php index.php get-checkout-summary**
```
$ php index.php get-checkout-summary
Here's your checkout summary:
Array
(
    [total-items] => 2
    [price] => Array
        (
            [price-all-items] => 57.9
            [delivery-cost] => 2.95
            [offers] => Array
                (
                    [offers] => Array
                        (
                            [name] => Buy one red widget, get the second half price
                        )

                    [discount-from-offers] => 0
                )

            [total-price] => 60.85
        )

    [items] => Array
        (
            [0] => stdClass Object
                (
                    [name] => Red Widget
                    [code] => R01
                    [price] => 32.95
                    [currency] => USD
                )

            [1] => stdClass Object
                (
                    [name] => Green Widget
                    [code] => G01
                    [price] => 24.95
                    [currency] => USD
                )

        )

)
```