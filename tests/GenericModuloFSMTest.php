<?php

// tests/GenericModuloFSMTest.php
namespace Tests;

use App\Modulo\GenericModuloFSM;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the GenericModuloFSM class.
 *
 * This class extends PHPUnit's TestCase and provides comprehensive tests
 * for the Generic Modulo Finite State Machine, ensuring its accuracy
 * for various moduli and proper error handling.
 */
class GenericModuloFSMTest extends TestCase
{
    /**
     * Tests the `mod` method with various binary string inputs and
     * different moduli, and their expected remainders.
     *
     * This test uses a data provider to run multiple assertions with different inputs.
     *
     * @dataProvider genericModExamplesProvider
     * @param string $input The binary string input.
     * @param int $modulus The modulus value.
     * @param int $expectedOutput The expected modulo remainder.
     */
    public function testGenericModExamples(string $input, int $modulus, int $expectedOutput): void
    {
        $fsm = new GenericModuloFSM($modulus);
        $this->assertEquals($expectedOutput, $fsm->mod($input));
    }

    /**
     * Provides a set of test cases for the `testGenericModExamples` method.
     *
     * Includes examples from the original problem (modulo 3), as well as
     * modulo 2, modulo 5, and other values to demonstrate generic functionality.
     *
     * @return array An array of arrays, where each inner array contains
     * [input_string, modulus, expected_output].
     */
    public function genericModExamplesProvider(): array
    {
        return [
            // Modulo 3 Examples (from original problem)
            'Thirteen (1101) mod 3 should be 1' => ['1101', 3, 1], // 13 % 3 = 1
            'Fourteen (1110) mod 3 should be 2' => ['1110', 3, 2], // 14 % 3 = 2
            'Fifteen (1111) mod 3 should be 0' => ['1111', 3, 0], // 15 % 3 = 0
            'Example 1 (110) mod 3 should be 0' => ['110', 3, 0], // 6 % 3 = 0
            'Example 2 (1010) mod 3 should be 1' => ['1010', 3, 1], // 10 % 3 = 1
            'Binary 0 (0) mod 3 should be 0' => ['0', 3, 0],
            'Binary 1 (1) mod 3 should be 1' => ['1', 3, 1],
            'Binary 2 (10) mod 3 should be 2' => ['10', 3, 2],
            'Binary 3 (11) mod 3 should be 0' => ['11', 3, 0],
            'Long string of zeros (00000) mod 3 should be 0' => ['00000', 3, 0],
            'Long string of ones (11111) mod 3 should be 1' => ['11111', 3, 1], // 31 % 3 = 1

            // Modulo 2 Examples
            'Binary 0 (0) mod 2 should be 0' => ['0', 2, 0],
            'Binary 1 (1) mod 2 should be 1' => ['1', 2, 1],
            'Binary 2 (10) mod 2 should be 0' => ['10', 2, 0],
            'Binary 3 (11) mod 2 should be 1' => ['11', 2, 1],
            'Binary 4 (100) mod 2 should be 0' => ['100', 2, 0],
            'Binary 5 (101) mod 2 should be 1' => ['101', 2, 1],
            'Binary 10 (1010) mod 2 should be 0' => ['1010', 2, 0],

            // Modulo 5 Examples
            'Binary 0 (0) mod 5 should be 0' => ['0', 5, 0],
            'Binary 1 (1) mod 5 should be 1' => ['1', 5, 1],
            'Binary 2 (10) mod 5 should be 2' => ['10', 5, 2],
            'Binary 3 (11) mod 5 should be 3' => ['11', 5, 3],
            'Binary 4 (100) mod 5 should be 4' => ['100', 5, 4],
            'Binary 5 (101) mod 5 should be 0' => ['101', 5, 0], // 5 % 5 = 0
            'Binary 6 (110) mod 5 should be 1' => ['110', 5, 1], // 6 % 5 = 1
            'Binary 13 (1101) mod 5 should be 3' => ['1101', 5, 3], // 13 % 5 = 3
            'Binary 25 (11001) mod 5 should be 0' => ['11001', 5, 0], // 25 % 5 = 0
            'Binary 31 (11111) mod 5 should be 1' => ['11111', 5, 1], // 31 % 5 = 1

            // Modulo 7 Examples
            'Binary 1000 (8) mod 7 should be 1' => ['1000', 7, 1], // 8 % 7 = 1
            'Binary 10000 (16) mod 7 should be 2' => ['10000', 7, 2], // 16 % 7 = 2
            'Binary 100000 (32) mod 7 should be 4' => ['100000', 7, 4], // 32 % 7 = 4
            'Binary 1000000 (64) mod 7 should be 1' => ['1000000', 7, 1], // 64 % 7 = 1
            'Binary 101010 (42) mod 7 should be 0' => ['101010', 7, 0], // 42 % 7 = 0
        ];
    }

    /**
     * Tests that an `InvalidArgumentException` is thrown when the input string is empty.
     */
    public function testEmptyInputThrowsException(): void
    {
        $fsm = new GenericModuloFSM(3); // Modulus doesn't matter for this test
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Input string cannot be empty.");
        $fsm->mod('');
    }

    /**
     * Tests that an `InvalidArgumentException` is thrown when the input string
     * contains characters not in the defined alphabet (i.e., not '0' or '1').
     */
    public function testInvalidCharacterInputThrowsException(): void
    {
        $fsm = new GenericModuloFSM(3);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid character '2' found in input. Allowed characters are: 0, 1");
        $fsm->mod('10120');
    }

    /**
     * Tests that an `InvalidArgumentException` is thrown even if invalid characters
     * are mixed with valid binary characters.
     */
    public function testMixedInvalidCharacterInputThrowsException(): void
    {
        $fsm = new GenericModuloFSM(3);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid character 'a' found in input. Allowed characters are: 0, 1");
        $fsm->mod('10a10');
    }

    /**
     * Tests that an `InvalidArgumentException` is thrown when the modulus is less than 2.
     */
    public function testInvalidModulusThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modulus must be greater than 1.");
        new GenericModuloFSM(1);
    }

    /**
     * Tests that an `InvalidArgumentException` is thrown when the modulus is zero.
     */
    public function testZeroModulusThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modulus must be greater than 1.");
        new GenericModuloFSM(0);
    }

    /**
     * Tests that an `InvalidArgumentException` is thrown when the modulus is negative.
     */
    public function testNegativeModulusThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Modulus must be greater than 1.");
        new GenericModuloFSM(-5);
    }
}
