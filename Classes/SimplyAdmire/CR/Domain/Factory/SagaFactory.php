<?php
namespace SimplyAdmire\CR\Domain\Factory;

use SimplyAdmire\CR\Domain\Commands\CreateAutoCreatedChildNodeCommand;
use SimplyAdmire\CR\Domain\Dto\NodeReference;
use SimplyAdmire\CR\Domain\Model\Saga\CreateAutoCreatedChildNodesSaga;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class SagaFactory {

	/**
	 * @param NodeReference $nodeReference
	 * @param NodeType $nodeType
	 * @param string $correlationId
	 * @return CreateAutoCreatedChildNodesSaga
	 */
	public function createCreateChildNodesSaga(NodeReference $nodeReference, NodeType $nodeType, $correlationId) {
		$saga = new CreateAutoCreatedChildNodesSaga();

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

			$saga->addCommand($newAutoCreatedChildNodeCommand);
		}

		return $saga;
	}
}