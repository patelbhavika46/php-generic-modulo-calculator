# Generic Modulo Calculator (FSM-based)

This project provides a generic modulo calculator implemented using a Finite State Machine (FSM) approach in PHP. It can compute the remainder of a binary string (representing an unsigned integer) when divided by any given modulus N (where N > 1).

The core logic is based on dynamically generating the FSM's states and transition function based on the specified modulus.

## Features
- **Generic Modulo Calculation:** Calculate binary_string % N for any N > 1.

- **Finite State Machine (FSM) Implementation:** Uses an efficient FSM approach, avoiding direct binary-to-decimal conversion for large numbers.

- **Composer Package:** Easily integrate into other PHP projects.

- **Command-Line Interface (CLI):** A convenient script to perform calculations directly from the terminal.

- **PHPUnit Tests:** Comprehensive unit tests to ensure correctness and robustness.

## Project Structure
```
.
├── composer.json               # Composer configuration for dependencies and autoloading
├── src/
│   ├── FSM/
│   │   └── FiniteStateMachine.php # Abstract base class for FSMs
│   └── Modulo/
│       └── GenericModuloFSM.php # Concrete FSM implementation for generic modulo calculation
├── bin/
│   └── modulo-cli.php          # Command-line interface script
└── tests/
    └── GenericModuloFSMTest.php # PHPUnit test cases
```

## Installation
To set up the project, follow these steps:

1. Clone the repository (or create the files manually as provided):

```bash
git clone https://github.com/patelbhavika46/php-generic-modulo-calculator.git
cd php-generic-modulo-calculator
```

2. Install Composer dependencies:
Make sure you have Composer installed. Then, run the following command in the project's root directory:
```bash
composer install
```
This command will download PHPUnit and set up the PSR-4 autoloading, making the classes available.

3. Make the CLI script executable:

```bash
chmod +x bin/modulo-cli.php
```

## Usage
You can use the modulo calculator as a PHP library within your projects or via the provided command-line interface.

### Using the CLI Tool
The modulo-cli.php script allows you to calculate modulo remainders directly from your terminal.

```bash
php ./bin/modulo-cli.php
```

**Example Interactive Session:**

```bash
$ php ./bin/modulo-cli.php
Enter the modulus (must be an integer greater than 1, e.g., 3): 3
Enter the binary string (must contain only '0's and '1's, e.g., 1101): 1101

The remainder of binary '1101' modulo 3 is: 1
```
### Using as a PHP Library
You can integrate the GenericModuloFSM class into your own PHP applications.

First, ensure your project's composer.json includes this package and you've run ```composer install```.

```
<?php

require_once 'vendor/autoload.php'; // Adjust path if necessary

use App\Modulo\GenericModuloFSM;

try {
    // Create an FSM instance for modulo 3
    $mod3FSM = new GenericModuloFSM(3);
    echo "1101 mod 3 = " . $mod3FSM->mod("1101") . "\n"; // Output: 1

    // Create an FSM instance for modulo 5
    $mod5FSM = new GenericModuloFSM(5);
    echo "1111 mod 5 = " . $mod5FSM->mod("1111") . "\n"; // Output: 0  (15 % 5 = 0)
    echo "101 mod 5 = " . $mod5FSM->mod("101") . "\n";   // Output: 0 (5 % 5 = 0)

    // Example with invalid input
    // $mod3FSM->mod("10A1"); // This will throw an InvalidArgumentException
} catch (\InvalidArgumentException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (\RuntimeException $e) {
    echo "Runtime Error: " . $e->getMessage() . "\n";
}

?>
```

## Testing
The project includes comprehensive unit tests using PHPUnit to ensure the correctness of the FSM logic and modulo calculations.

To run the tests, navigate to the project's root directory in your terminal and execute:
```
vendor/bin/phpunit tests/GenericModuloFSMTest.php
```
This command will run all test cases defined in ```tests/GenericModuloFSMTest.php``` and report the results.