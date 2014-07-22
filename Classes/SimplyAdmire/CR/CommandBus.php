<?php
namespace SimplyAdmire\CR;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ReflectionService;

/**
 * @Flow\Scope("singleton")
 */
class CommandBus {

	/**
	 * @Flow\Inject(setting="commandHandlers")
	 * @var array
	 */
	protected $commandHandlerMapping;

	/**
	 * @var array<callable>
	 */
	protected $handlers = array();

	/**
	 * @Flow\Inject
	 * @var ReflectionService
	 */
	protected $reflectionService;

	/**
	 * @param object $command
	 * @return boolean
	 */
	public function handle($command) {
		try {
			$commandHandler = $this->getCommandHandlerForCommand($command);
			$commandHandler($command);
		} catch (\Exception $exception) {
			// Do something useful like throwing an exception or at least log
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param object $command
	 * @return callable
	 * @throws \Exception
	 */
	protected function getCommandHandlerForCommand($command) {
		$commandClassName = get_class($command);

		if (isset($this->handlers[$commandClassName])) {
			return $this->handlers[$commandClassName];
		}

		if (isset($this->commandHandlerMapping[$commandClassName])) {
			$commandHandlerClassName = $this->commandHandlerMapping[$commandClassName];
		} elseif ($this->reflectionService->isClassAnnotatedWith($commandClassName, 'SimplyAdmire\CR\Annotations\CommandHandler')) {
			throw new \Exception('TODO: support mapping by annotation');
		} else {
			$commandHandlerClassName = $this->getBaseCommandHandlerName($command);
		}

		if (!isset($commandHandlerClassName)) {
			throw new \Exception('TODO: make a nice exception that the classname for the command handler could not be resolved');
		} elseif (!class_exists($commandHandlerClassName)) {
			throw new \Exception('TODO: make a nice exception that the class for the command handler does not exist');
		}

		$commandHandler = new $commandHandlerClassName;
		if (!is_callable($commandHandler)) {
			throw new \Exception('TODO: make a nice exception that the commandhandler should be a callable');
		}

		$this->handlers[$commandClassName] = $commandHandler;
		return $this->handlers[$commandClassName];
	}

	/**
	 * @param object $command
	 * @return string
	 * @throws \Exception
	 */
	protected function getBaseCommandHandlerName($command) {
		if (!is_object($command)) {
			throw new \Exception('TODO: Add a nice exception for the command not being an object');
		}
		return substr(str_replace('\\Commands\\', '\\CommandHandlers\\', get_class($command)), 0, -7) . 'CommandHandler';
	}

}