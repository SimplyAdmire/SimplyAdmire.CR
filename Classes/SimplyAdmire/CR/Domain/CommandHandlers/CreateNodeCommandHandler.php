<?php
namespace SimplyAdmire\CR\Domain\CommandHandlers;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

class CreateNodeCommandHandler {

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @param CreateNodeCommand $command
	 * @return void
	 */
	public function __invoke(CreateNodeCommand $command) {
		$newNode = $command->parentNode->createNode(
			$command->suggestedNodeName,
			$this->nodeTypeManager->getNodeType($command->nodeTypeName),
			NULL,
			$command->dimensions
		);

		foreach ($command->properties as $propertyName => $propertyValue) {
			$newNode->setProperty($propertyName, $propertyValue);
		}
	}

}