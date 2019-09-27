<?php

namespace Dotenv\Environment\Adapter;

use PhpOption\Option;

interface AdapterInterface
{
    /**
     * Determines if the adapter is supported.
     *
     * @return bool
     */
    public function isSupported();

    /**
     * Get an environment variable, if it exists.
     *
     * @param string $name
     *
     * @return Option
     */
    public function get($name);

    /**
     * Set an environment variable.
     *
     * @param string $name
     * @param string|null $value
     *
     * @return void
     */
    public function set($name, $value = null);

    /**
     * Clear an environment variable.
     *
     * @param string $name
     *
     * @return void
     */
    public function clear($name);
}