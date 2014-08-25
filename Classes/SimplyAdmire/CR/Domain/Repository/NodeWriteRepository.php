<?php
namespace SimplyAdmire\CR\Domain\Repository;

use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodeWriteRepository extends AbstractNodeRepository {

	/**
	 * @param CreateNodeCommand $command
	 */
	public function createNode(CreateNodeCommand $command) {
		// TODO: store event

		// TODO: Move code below to a method listening to the event
		$newNode = $command->parentNode->createNode(
			$command->suggestedNodeName,
			$this->nodeTypeManager->getNodeType($command->nodeTypeName),
			NULL,
			$command->dimensions
		);

		foreach ($command->properties as $propertyName => $propertyValue) {
			$newNode->setProperty($propertyName, $propertyValue);
		}

		return $newNode;
	}
}