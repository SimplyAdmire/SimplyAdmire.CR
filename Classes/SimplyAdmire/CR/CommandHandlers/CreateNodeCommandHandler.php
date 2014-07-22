<?php
namespace SimplyAdmire\CR\CommandHandlers;

use SimplyAdmire\CR\Commands\CreateNodeCommand;

class CreateNodeCommandHandler {

	/**
	 * @param CreateNodeCommand $command
	 * @return void
	 */
	public function __invoke(CreateNodeCommand $command) {
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