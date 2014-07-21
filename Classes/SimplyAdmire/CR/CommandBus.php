<?php
namespace SimplyAdmire\CR;

use SimplyAdmire\CR\Exceptions\CommandHandlerForCommandNotFoundException;
use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Commands\CommandInterface;

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
	 * @var array<CommandHandlerInterface>
	 */
	protected $handlers = array();

	/**
	 * @param CommandInterface $command
	 * @return boolean
	 */
	public function handle(CommandInterface $command) {
		try {
			$commandHandlers = $this->getCommandHandlerInstancesForCommand($command);
			foreach ($commandHandlers as $commandHandler) {
				$commandHandler->handle($command);
			}
		} catch (\Exception $exception) {
			// Do something useful like throwing an exception or at least log
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * @param CommandInterface $command
	 * @throws CommandHandlerForCommandNotFoundException
	 */
	protected function getCommandHandlerInstancesForCommand(CommandInterface $command) {
		$commandClassName = get_class($command);
		if (isset($this->handlers[$commandClassName])) {
			return $this->handlers[$commandClassName];
		}

		$this->handlers[$commandClassName] = array();
		$baseCommandHandlerName = $this->getBaseCommandHandlerName($command);
		if (class_exists($baseCommandHandlerName)) {
			$this->handlers[$commandClassName][] = new $baseCommandHandlerName();
		}

		if (isset($this->commandHandlerMapping[get_class($command)]) && is_array($this->commandHandlerMapping[get_class($command)])) {
			foreach ($this->commandHandlerMapping[get_class($command)] as $mappedCommandHandlerClassName) {
				if (!class_exists($mappedCommandHandlerClassName)) {
					continue;
				}
				$this->handlers[$commandClassName][] = new $mappedCommandHandlerClassName();
			}
		}

		if (empty($this->handlers[$commandClassName])) {
			throw new CommandHandlerForCommandNotFoundException();
		}

		return $this->handlers[$commandClassName];
	}

	/**
	 * @param CommandInterface $command
	 * @return string
	 */
	protected function getBaseCommandHandlerName(CommandInterface $command) {
		return substr(str_replace('\\Commands\\', '\\CommandHandlers\\', get_class($command)), 0, -7) . 'CommandHandler';
	}

}