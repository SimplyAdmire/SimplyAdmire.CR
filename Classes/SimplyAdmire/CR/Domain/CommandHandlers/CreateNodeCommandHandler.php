<?php
namespace SimplyAdmire\CR\Domain\CommandHandlers;

use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;

class CreateNodeCommandHandler {

	/**
	 * @param CreateNodeCommand $command
	 * @return void
	 */
	public function __invoke(CreateNodeCommand $command) {
		$newNode = $command->parentNode->createNode(
			$command->suggestedNodeName,
			$command->nodeType,
			NULL,
			$command->dimensions
		);

		foreach ($command->properties as $propertyName => $propertyValue) {
			$newNode->setProperty($propertyName, $propertyValue);
		}
	}

}