<?php

// src/FSM/FiniteStateMachine.php
namespace App\FSM;

/**
 * Abstract class for a Finite State Machine (FSM).
 *
 * This class defines the fundamental components of a Finite Automaton (FA):
 * Q (finite set of states), Σ (finite input alphabet), q0 (initial state),
 * F (set of accepting/final states), and δ (transition function).
 * It also provides a concrete `run` method to simulate the FSM's operation
 * given an input string.
 */
abstract class FiniteStateMachine
{
    /**
     * Get the finite set of states (Q) for the FSM.
     *
     * @return string[] An array of strings, where each string represents a unique state.
     */
    abstract protected function getStates(): array;

    /**
     * Get the finite input alphabet (Σ) for the FSM.
     *
     * @return string[] An array of strings, where each string represents a valid input symbol.
     */
    abstract protected function getAlphabet(): array;

    /**
     * Get the initial state (q0) of the FSM.
     *
     * @return string The string identifier of the initial state.
     */
    abstract protected function getInitialState(): string;

    /**
     * Get the set of accepting/final states (F) for the FSM.
     *
     * @return string[] An array of strings, where each string represents an accepting/final state.
     */
    abstract protected function getFinalStates(): array;

    /**
     * Get the transition function (δ) for the FSM.
     *
     * This function dictates how the FSM transitions from one state to another
     * based on the current state and the input symbol.
     *
     * @return array<string, array<string, string>> An associative array where:
     * - Keys are current state identifiers (string).
     * - Values are another associative array where:
     * - Keys are input symbols (string).
     * - Values are the next state identifiers (string).
     * Example: ['S0' => ['0' => 'S0', '1' => 'S1'], ...]
     */
    abstract protected function getTransitionFunction(): array;

    /**
     * Runs the Finite State Machine with the given input string.
     *
     * The FSM starts at its initial state and processes the input string
     * character by character, transitioning between states according to
     * its defined transition function.
     *
     * @param string $input The input string to be processed by the FSM.
     * @return string The final state reached after processing the entire input string.
     * @throws \InvalidArgumentException If the input string is empty or contains characters
     * not defined in the FSM's alphabet.
     * @throws \RuntimeException If the initial state is not a valid state, or if a transition
     * is not defined for the current state and input symbol.
     */
    public function run(string $input): string
    {
        // Ensure the input string is not empty.
        // Changed from empty($input) to $input === '' to allow '0' as a valid input.
        if ($input === '') {
            throw new \InvalidArgumentException("Input string cannot be empty.");
        }

        // Initialize the current state to the FSM's initial state.
        $currentState = $this->getInitialState();
        $alphabet = $this->getAlphabet();
        $transitionFunction = $this->getTransitionFunction();
        $allStates = $this->getStates();

        // Validate that the initial state is indeed one of the defined states.
        if (!in_array($currentState, $allStates)) {
            throw new \RuntimeException("Initial state '{$currentState}' is not a valid state defined in Q.");
        }

        // Iterate through each character of the input string.
        foreach (str_split($input) as $char) {
            // Validate that the current input character is part of the FSM's alphabet.
            if (!in_array($char, $alphabet)) {
                throw new \InvalidArgumentException(
                    "Invalid character '{$char}' found in input. Allowed characters are: " . implode(', ', $alphabet)
                );
            }

            // Check if a transition is defined for the current state and input character.
            if (!isset($transitionFunction[$currentState][$char])) {
                throw new \RuntimeException(
                    "No transition defined for state '{$currentState}' with input '{$char}'. " .
                    "Please check the transition function (δ)."
                );
            }

            // Update the current state based on the transition function.
            $currentState = $transitionFunction[$currentState][$char];
        }

        // Return the final state after all input characters have been processed.
        return $currentState;
    }
}
