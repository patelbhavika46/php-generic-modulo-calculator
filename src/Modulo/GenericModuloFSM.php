<?php
// src/Modulo/GenericModuloFSM.php
namespace App\Modulo;

use App\FSM\FiniteStateMachine;

/**
 * Implements a generic Modulo N Finite State Machine for binary strings.
 *
 * This FSM calculates the remainder when a binary string (representing
 * an unsigned integer) is divided by a given modulus N.
 * It dynamically constructs its states and transition function based on N.
 */
class GenericModuloFSM extends FiniteStateMachine
{
    private readonly int $modulus;
    private readonly array $alphabet;
    
    private array $states = [];
    private array $transitionFunction = [];
    

    /**
     * Constructor for GenericModuloFSM.
     *
     * @param int $modulus The modulus N for the calculation. Must be greater than 1.
     * @param array $alphabet The input alphabet. Defaults to ['0', '1'] for binary.
     * @throws \InvalidArgumentException If the modulus is less than or equal to 1.
     */
    public function __construct(int $modulus, array $alphabet = ['0', '1'])
    {
        if ($modulus <= 1) {
            throw new \InvalidArgumentException("Modulus must be greater than 1.");
        }
        $this->modulus = $modulus;
        $this->alphabet = $alphabet;
        $this->initializeFSM();
    }

    /**
     * Initializes the FSM by dynamically generating states and the transition function.
     */
    private function initializeFSM(): void
    {
        // States Q = {S0, S1, ..., S(modulus-1)}
        for ($i = 0; $i < $this->modulus; $i++) {
            $this->states[] = 'S' . $i;
        }

        // Transition function δ: Q × Σ → Q
        // For a binary string, each bit shift left is equivalent to multiplying by 2.
        // So, if current remainder is `r` and input bit is `b`, new remainder is `(r * 2 + b) % modulus`.
        foreach ($this->states as $state) {
            $remainder = (int) substr($state, 1); // Extract numerical remainder from state string (e.g., 'S0' -> 0)
            $this->transitionFunction[$state] = [];
            foreach ($this->alphabet as $inputSymbol) {
                if (!is_numeric($inputSymbol) || ($inputSymbol != '0' && $inputSymbol != '1')) {
                     throw new \RuntimeException("Only binary alphabet ('0', '1') is supported for this FSM logic.");
                }
                $bitValue = (int) $inputSymbol;
                $nextRemainder = ($remainder * 2 + $bitValue) % $this->modulus;
                $this->transitionFunction[$state][$inputSymbol] = 'S' . $nextRemainder;
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function getStates(): array
    {
        return $this->states;
    }

    /**
     * @inheritDoc
     */
    protected function getAlphabet(): array
    {
        return $this->alphabet;
    }

    /**
     * @inheritDoc
     */
    protected function getInitialState(): string
    {
        // The initial state is always S0 (representing a remainder of 0).
        return 'S0';
    }

    /**
     * @inheritDoc
     */
    protected function getFinalStates(): array
    {
        // All states are considered final, as the output is determined by the final state.
        return $this->states;
    }

    /**
     * @inheritDoc
     */
    protected function getTransitionFunction(): array
    {
        return $this->transitionFunction;
    }

    /**
     * Computes the modulo remainder for a given binary string and the configured modulus.
     *
     * This method runs the FSM with the provided binary string and then maps
     * the final state reached by the FSM to its corresponding numerical remainder.
     *
     * @param string $binaryString The binary string representing the unsigned integer.
     * @return int The remainder when the represented value is divided by the modulus.
     * @throws \InvalidArgumentException If the input string is invalid (empty or contains
     * non-binary characters), as thrown by the parent `run` method.
     * @throws \RuntimeException If an unexpected final state is reached.
     */
    public function mod(string $binaryString): int
    {
        // Run the FSM with the binary string to get the final state.
        $finalState = $this->run($binaryString);

        // Extract the numerical remainder from the final state string (e.g., 'S1' -> 1).
        $remainder = (int) substr($finalState, 1);

        // Basic validation to ensure the remainder is within expected bounds.
        if ($remainder < 0 || $remainder >= $this->modulus) {
            throw new \RuntimeException("Unexpected remainder value '{$remainder}' from final state '{$finalState}'.");
        }

        return $remainder;
    }
}
