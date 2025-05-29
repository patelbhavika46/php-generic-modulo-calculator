#!/usr/bin/env php
<?php

// bin/modulo-cli.php

// This script provides a command-line interface for the generic modulo calculator.
// It interactively asks for the binary string and modulus, then outputs the remainder.

require_once dirname(__DIR__) . '/vendor/autoload.php'; // Autoload Composer dependencies

use App\Modulo\GenericModuloFSM;

// --- Prompt for Modulus with Validation and Reprompt ---
$modulus = 0; // Initialize modulus
while (true) {
    echo "Enter the modulus (must be an integer greater than 1, e.g., 3): ";
    $modulusInput = trim((string)fgets(STDIN)); // Read modulus input

    // Check if input is a valid number and greater than 1
    if (is_numeric($modulusInput) && (int)$modulusInput > 1 && (string)(int)$modulusInput === $modulusInput) {
        $modulus = (int)$modulusInput;
        break; // Exit loop if valid
    } else {
        echo "Invalid modulus entered. Please enter an integer greater than 1.\n";
    }
}

// --- Prompt for Binary String with Validation and Reprompt ---
$binaryString = ''; // Initialize binary string
while (true) {
    echo "Enter the binary string (must contain only '0's and '1's, e.g., 1101): ";
    $binaryStringInput = trim((string)fgets(STDIN)); // Read binary string from standard input

    // Check if input is not empty and contains only '0' or '1'
    if ($binaryStringInput !== '' && preg_match('/^[01]+$/', $binaryStringInput)) {
        $binaryString = $binaryStringInput;
        break; // Exit loop if valid
    } else {
        echo "Invalid binary string entered. Please enter a non-empty string containing only '0's and '1's.\n";
    }
}

try {
    // Instantiate the GenericModuloFSM with the provided modulus.
    $fsm = new GenericModuloFSM($modulus);

    // Calculate the modulo remainder.
    $result = $fsm->mod($binaryString);

    // Output the result.
    echo "\nThe remainder of binary '{$binaryString}' modulo {$modulus} is: {$result}\n";

} catch (\InvalidArgumentException $e) {
    // Handle invalid arguments (e.g., empty string, non-binary characters, invalid modulus).
    fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
    exit(1);
} catch (\RuntimeException $e) {
    // Handle runtime errors (e.g., FSM configuration issues).
    fwrite(STDERR, "Runtime Error: " . $e->getMessage() . "\n");
    exit(1);
} catch (\Throwable $e) {
    // Catch any other unexpected errors.
    fwrite(STDERR, "An unexpected error occurred: " . $e->getMessage() . "\n");
    exit(1);
}

exit(0); // Exit successfully