# reMarkable PDF backup

## Prerequisites

- PHP is installed (tested only with version 8.3)
- composer is installed

## Usage

- Copy env.example.php to env.php and adjust settings.
- Install packages with `composer install`.
- Connect your reMarkable device via USB.
- Activate the USB web interface.
- Run `php index.php sync` in project root.

## Possible problems

Sometimes a network fetch error occurs. Try to restart the web interface and run the program again. It will continue where it has stopped.

## Contributions

Feedback, ideas and contributions are welcome!
