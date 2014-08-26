<?php
namespace SimplyAdmire\CR\Domain\Factory;

use SimplyAdmire\CR\Domain\Commands\CreateAutoCreatedChildNodeCommand;
use SimplyAdmire\CR\Domain\Dto\NodeReference;
use SimplyAdmire\CR\Domain\Model\Saga;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class SagaFactory {

	/**
	 * @param NodeReference $nodeReference
	 * @param NodeType $nodeType
	 * @param string $correlationId
	 * @return Saga
	 */
	public function createCreateChildNodesSaga(NodeReference $nodeReference, NodeType $nodeType, $correlationId) {
		$saga = new Saga();

		foreach ($nodeType->getAutoCreatedChildNodes() as $childNodeName => $childNodeType) {
			$newAutoCreatedChildNodeCommand = new CreateAutoCreatedChildNodeCommand(
				$nodeReference,
				Algorithms::generateUUID(),
				$childNodeName,
				$childNodeType->getName(),
				array(),
				$nodeReference->dimensions,
				$correlationId
			);

			$saga->addCommand($newAutoCreatedChildNodeCommand, 'SimplyAdmire\CR\Domain\Events\AutoCreatedChildNodeCreatedEvent');
		}

		return $saga;
	}
}