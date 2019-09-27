<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2018 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\Readline;

use Psy\Exception\BreakException;
use function array_search;
use function array_slice;
use function array_values;
use function count;
use function feof;
use function fgets;
use function fopen;
use function rtrim;

/**
 * An array-based Readline emulation implementation.
 */
class Transient implements Readline
{
    private $history;
    private $historySize;
    private $eraseDups;
    private $stdin;

    /**
     * Transient Readline constructor.
     */
    public function __construct($historyFile = null, $historySize = 0, $eraseDups = false)
    {
        // don't do anything with the history file...
        $this->history = [];
        $this->historySize = $historySize;
        $this->eraseDups = $eraseDups;
    }

    /**
     * Transient Readline is always supported.
     *
     * {@inheritdoc}
     */
    public static function isSupported()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addHistory($line)
    {
        if ($this->eraseDups) {
            if (($key = array_search($line, $this->history)) !== false) {
                unset($this->history[$key]);
            }
        }

        $this->history[] = $line;

        if ($this->historySize > 0) {
            $histsize = count($this->history);
            if ($histsize > $this->historySize) {
                $this->history = array_slice($this->history, $histsize - $this->historySize);
            }
        }

        $this->history = array_values($this->history);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function clearHistory()
    {
        $this->history = [];

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function listHistory()
    {
        return $this->history;
    }

    /**
     * {@inheritdoc}
     */
    public function readHistory()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     * @throws BreakException if user hits Ctrl+D
     *
     */
    public function readline($prompt = null)
    {
        echo $prompt;

        return rtrim(fgets($this->getStdin()), "\n\r");
    }

    /**
     * Get a STDIN file handle.
     *
     * @return resource
     * @throws BreakException if user hits Ctrl+D
     *
     */
    private function getStdin()
    {
        if (!isset($this->stdin)) {
            $this->stdin = fopen('php://stdin', 'r');
        }

        if (feof($this->stdin)) {
            throw new BreakException('Ctrl+D');
        }

        return $this->stdin;
    }

    /**
     * {@inheritdoc}
     */
    public function redisplay()
    {
        // noop
    }

    /**
     * {@inheritdoc}
     */
    public function writeHistory()
    {
        return true;
    }
}