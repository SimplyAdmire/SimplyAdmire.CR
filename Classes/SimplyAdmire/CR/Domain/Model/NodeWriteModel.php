<?php
namespace SimplyAdmire\CR\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Dto\NodeReference;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

class NodeWriteModel {

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
	 * @var array
	 */
	protected $events = array();

	/**
	 * @param NodeReference $parentNodeReference
	 * @param string $nodeName
	 * @param NodeType $nodeType
	 * @param array $properties
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodeReference $parentNodeReference, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array()) {
		try {
			$nodeCreatedEvent = new NodeCreatedEvent(
				$parentNodeReference,
				$nodeName,
				$nodeType,
				$properties,
				$workspace,
				$dimensions
			);

			$event = new Event($nodeCreatedEvent);
			$this->events[] = $event;
		} catch (\Exception $exception) {
			// TODO: Add logging
		}
	}

	/**
	 * @param NodeCreatedEvent $event
	 * @return NodeInterface
	 */
	public function applyNodeCreatedEvent(NodeCreatedEvent $event) {
		$contentContext = $this->createContext($event->getNodeReference()->workspace, $event->getNodeReference()->dimensions);
		$referenceNode = $contentContext->getNodeByIdentifier($event->getNodeReference()->identifier);
		$newNode = $referenceNode->createNode(
			$event->getNodeName(),
			$this->nodeTypeManager->getNodeType($event->getNodeType()),
			NULL,
			$event->getDimensions()
		);

		foreach ($event->getProperties() as $propertyName => $propertyValue) {
			$newNode->setProperty($propertyName, $propertyValue);
		}

		return $newNode;
	}

	/**
	 * @return array
	 */
	public function getEventsToEmit() {
		return $this->events;
	}

	/**
	 * @return void
	 */
	public function flushEventsToEmit() {
		$this->events = [];
	}

	/**
	 * @return array
	 */
	public function getAndFlushEventsToEmit() {
		$eventsToEmit = $this->getEventsToEmit();
		$this->flushEventsToEmit();
		return $eventsToEmit;
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
