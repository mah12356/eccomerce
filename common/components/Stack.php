<?php

namespace common\components;

class Stack
{
    protected $stack;

    public function __construct($initial = array()) {
        $this->stack = $initial;
    }

    /**
     * add an item to top of the stack
     * @param array $item
     * @param bool $duplicate
     * @return void
     */
    public function push(array $item, bool $duplicate = true)
    {
        array_unshift($this->stack, $item);
    }

    /**
     * remove an item from top of the stack
     * @return void
     */
    public function pop()
    {
        if (!$this->isEmpty()) {
            array_shift($this->stack);
        }
    }

    /**
     * returns the value at the top of the stack
     * @return false|mixed
     */
    public function peek()
    {
        return current($this->stack);
    }

    /**
     * returns whether the stack is empty
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->stack);
    }

    /**
     * removes all values
     * @return void
     */
    public function clear()
    {
        $this->stack = array_diff($this->stack, $this->stack);
    }

    public function print()
    {
        return $this->stack;
    }
}