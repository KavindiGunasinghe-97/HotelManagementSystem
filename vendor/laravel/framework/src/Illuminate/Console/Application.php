<?php

namespace Illuminate\Console;

use Closure;
use Illuminate\Contracts\Console\Application as ApplicationContract;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ProcessUtils;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\PhpExecutableFinder;

class Application extends SymfonyApplication implements ApplicationContract
{
    /**
     * The console application bootstrappers.
     *
     * @var array
     */
    protected static $bootstrappers = [];
    /**
     * The Laravel application instance.
     *
     * @var Container
     */
    protected $laravel;
    /**
     * The output from the previous command.
     *
     * @var BufferedOutput
     */
    protected $lastOutput;
    /**
     * The Event Dispatcher.
     *
     * @var Dispatcher
     */
    protected $events;

    /**
     * Create a new Artisan console application.
     *
     * @param Container $laravel
     * @param Dispatcher $events
     * @param string $version
     * @return void
     */
    public function __construct(Container $laravel, Dispatcher $events, $version)
    {
        parent::__construct('Laravel Framework', $version);

        $this->laravel = $laravel;
        $this->events = $events;
        $this->setAutoExit(false);
        $this->setCatchExceptions(false);

        $this->events->dispatch(new Events\ArtisanStarting($this));

        $this->bootstrap();
    }

    /**
     * Bootstrap the console application.
     *
     * @return void
     */
    protected function bootstrap()
    {
        foreach (static::$bootstrappers as $bootstrapper) {
            $bootstrapper($this);
        }
    }

    /**
     * Format the given command as a fully-qualified executable command.
     *
     * @param string $string
     * @return string
     */
    public static function formatCommandString($string)
    {
        return sprintf('%s %s %s', static::phpBinary(), static::artisanBinary(), $string);
    }

    /**
     * Determine the proper PHP executable.
     *
     * @return string
     */
    public static function phpBinary()
    {
        return ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));
    }

    /**
     * Determine the proper Artisan executable.
     *
     * @return string
     */
    public static function artisanBinary()
    {
        return defined('ARTISAN_BINARY') ? ProcessUtils::escapeArgument(ARTISAN_BINARY) : 'artisan';
    }

    /**
     * Register a console "starting" bootstrapper.
     *
     * @param Closure $callback
     * @return void
     */
    public static function starting(Closure $callback)
    {
        static::$bootstrappers[] = $callback;
    }

    /**
     * Clear the console application bootstrappers.
     *
     * @return void
     */
    public static function forgetBootstrappers()
    {
        static::$bootstrappers = [];
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param string $command
     * @param array $parameters
     * @param OutputInterface|null $outputBuffer
     * @return int
     *
     * @throws CommandNotFoundException
     */
    public function call($command, array $parameters = [], $outputBuffer = null)
    {
        [$command, $input] = $this->parseCommand($command, $parameters);

        if (!$this->has($command)) {
            throw new CommandNotFoundException(sprintf('The command "%s" does not exist.', $command));
        }

        return $this->run(
            $input, $this->lastOutput = $outputBuffer ?: new BufferedOutput
        );
    }

    /**
     * Parse the incoming Artisan command and its input.
     *
     * @param string $command
     * @param array $parameters
     * @return array
     */
    protected function parseCommand($command, $parameters)
    {
        if (is_subclass_of($command, SymfonyCommand::class)) {
            $callingClass = true;

            $command = $this->laravel->make($command)->getName();
        }

        if (!isset($callingClass) && empty($parameters)) {
            $command = $this->getCommandName($input = new StringInput($command));
        } else {
            array_unshift($parameters, $command);

            $input = new ArrayInput($parameters);
        }

        return [$command, $input ?? null];
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $commandName = $this->getCommandName(
            $input = $input ?: new ArgvInput
        );

        $this->events->dispatch(
            new Events\CommandStarting(
                $commandName, $input, $output = $output ?: new ConsoleOutput
            )
        );

        $exitCode = parent::run($input, $output);

        $this->events->dispatch(
            new Events\CommandFinished($commandName, $input, $output, $exitCode)
        );

        return $exitCode;
    }

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output()
    {
        return $this->lastOutput && method_exists($this->lastOutput, 'fetch')
            ? $this->lastOutput->fetch()
            : '';
    }

    /**
     * Resolve an array of commands through the application.
     *
     * @param array|mixed $commands
     * @return $this
     */
    public function resolveCommands($commands)
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        foreach ($commands as $command) {
            $this->resolve($command);
        }

        return $this;
    }

    /**
     * Add a command, resolving through the application.
     *
     * @param string $command
     * @return SymfonyCommand
     */
    public function resolve($command)
    {
        return $this->add($this->laravel->make($command));
    }

    /**
     * Add a command to the console.
     *
     * @param SymfonyCommand $command
     * @return SymfonyCommand
     */
    public function add(SymfonyCommand $command)
    {
        if ($command instanceof Command) {
            $command->setLaravel($this->laravel);
        }

        return $this->addToParent($command);
    }

    /**
     * Add the command to the parent instance.
     *
     * @param SymfonyCommand $command
     * @return SymfonyCommand
     */
    protected function addToParent(SymfonyCommand $command)
    {
        return parent::add($command);
    }

    /**
     * Get the Laravel application instance.
     *
     * @return \Illuminate\Contracts\Foundation\Application
     */
    public function getLaravel()
    {
        return $this->laravel;
    }

    /**
     * Get the default input definition for the application.
     *
     * This is used to add the --env option to every available command.
     *
     * @return InputDefinition
     */
    protected function getDefaultInputDefinition()
    {
        return tap(parent::getDefaultInputDefinition(), function ($definition) {
            $definition->addOption($this->getEnvironmentOption());
        });
    }

    /**
     * Get the global environment option for the definition.
     *
     * @return InputOption
     */
    protected function getEnvironmentOption()
    {
        $message = 'The environment the command should run under';

        return new InputOption('--env', null, InputOption::VALUE_OPTIONAL, $message);
    }
}