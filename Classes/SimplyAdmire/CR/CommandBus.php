<?php
namespace SimplyAdmire\CR;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Object\ObjectManagerInterface;
use TYPO3\Flow\Reflection\ReflectionService;

/**
 * @Flow\Scope("singleton")
 */
class CommandBus {

	/**
	 * @Flow\Inject
	 * @var ObjectManagerInterface
	 */
	protected $objectManager;

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
			$this->callCommandHandlerForCommand($command);
		} catch (\Exception $exception) {
			// Do something useful like throwing an exception or at least log
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param object $command
	 * @return boolean
	 */
	protected function callCommandHandlerForCommand($command) {
		$commandClassName = get_class($command);

		if (isset($this->commandHandlerMapping[$commandClassName])) {
			// TODO: add support for -> in the callable
			$this->handlers[$commandClassName] = $this->commandHandlerMapping[$commandClassName];
		} elseif ($this->reflectionService->isClassAnnotatedWith($commandClassName, 'SimplyAdmire\CR\Annotations\CommandHandler')) {
			$annotations = $this->reflectionService->getClassAnnotations($commandClassName, 'SimplyAdmire\CR\Annotations\CommandHandler');

			$commandHandler = array_shift($annotations);
			if (strpos($commandHandler->callable, '->') !== FALSE) {
				$commandHandlerParts = explode('->', $commandHandler->callable);
				$this->handlers[$commandClassName] = array(
					$this->objectManager->get($commandHandlerParts[0]),
					$commandHandlerParts[1]
				);
			} else {
				$this->handlers[$commandClassName] = $commandHandler->callable;
			}
		} else {
			$baseCommandHandlerName = $this->getBaseCommandHandlerName($command);
			$this->handlers[$commandClassName] = new $baseCommandHandlerName;
		}

		try {
			return (bool)call_user_func($this->handlers[$commandClassName], $command);
		} catch (\Exception $exception) {
			// TODO: log something
			return FALSE;
		}
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