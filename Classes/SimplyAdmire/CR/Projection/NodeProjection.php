<?php
namespace SimplyAdmire\CR\Projection;

use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\Domain\Commands\CreateAutoCreatedChildNodeCommand;
use SimplyAdmire\CR\Domain\Dto\NodeReference;
use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use TYPO3\Flow\Utility\Algorithms;
use TYPO3\TYPO3CR\Domain\Model\NodeData;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

class NodeProjection {

	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @Flow\Inject
	 * @var CommandBus
	 */
	protected $commandBus;

	/**
	 * @param NodeCreatedEvent $event
	 * @param string $correlationId
	 * @throws \TYPO3\TYPO3CR\Exception\NodeTypeNotFoundException
	 * @return void
	 */
	public function onNodeCreated(NodeCreatedEvent $event, $correlationId) {
		$contentContext = $this->createContext($event->getNodeReference()->workspace, $event->getNodeReference()->dimensions);
		$referenceNode = $contentContext->getNodeByIdentifier($event->getNodeReference()->identifier);

		$referenceNodeData = $referenceNode->getNodeData();
		$newNodeData = $referenceNodeData->createSingleNodeData(
			$event->getNodeName(),
			$this->nodeTypeManager->getNodeType($event->getNodeType()),
			$event->getIdentifier(),
			$referenceNode->getWorkspace(),
			$event->getDimensions()
		);

		foreach ($event->getProperties() as $propertyName => $propertyValue) {
			$newNodeData->setProperty($propertyName, $propertyValue);
		}
	}

	/**
	 * @param string $workspaceName
	 * @param array $dimensions
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName, array $dimensions = array()) {
		return $this->contextFactory->create(array(
			'workspaceName' => $workspaceName,
			'dimensions' => $dimensions
		));
	}
}