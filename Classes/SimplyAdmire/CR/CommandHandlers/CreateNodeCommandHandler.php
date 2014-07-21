<?php
namespace SimplyAdmire\CR\CommandHandlers;

use SimplyAdmire\CR\Commands\CommandInterface;
use SimplyAdmire\CR\Commands\CreateNodeCommand;
use SimplyAdmire\CR\Exceptions\InvalidArgumentTypeException;

class CreateNodeCommandHandler implements CommandHandlerInterface {

	/**
	 * @param CommandInterface $command
	 * @return void
	 * @throws InvalidArgumentTypeException
	 */
	public function handle(CommandInterface $command) {
		if (!$command instanceof CreateNodeCommand) {
			throw new InvalidArgumentTypeException();
		}

		$newNode = $command->getParentNode()->createNode(
			$command->getSuggestedNodeName(),
			$command->getNodeType(),
			NULL,
			$command->getDimensions()
		);

		foreach ($command->getProperties() as $propertyName => $propertyValue) {
			$newNode->setProperty($propertyName, $propertyValue);
		}
	}

}