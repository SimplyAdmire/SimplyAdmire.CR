<?php
namespace SimplyAdmire\CR\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Dto\NodeReference;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class NodeWriteModel {

	/**
	 * @var array
	 */
	protected $events = array();

	/**
	 * @param NodeReference $parentNodeReference
	 * @param string $identifier
	 * @param string $nodeName
	 * @param NodeType $nodeType
	 * @param array $properties
	 * @param string $workspace
	 * @param array $dimensions
	 * @param boolean $autoCreated
	 */
	public function __construct(NodeReference $parentNodeReference, $identifier, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array(), $autoCreated = FALSE) {
		try {
			$newNodeEventName = ($autoCreated === TRUE) ? 'SimplyAdmire\CR\Domain\Events\AutoCreatedChildNodeCreatedEvent' : 'SimplyAdmire\CR\Domain\Events\NodeCreatedEvent';

			$nodeCreatedEvent = new $newNodeEventName(
				$parentNodeReference,
				$identifier,
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
	 * @return void
	 */
	public function applyNodeCreatedEvent(NodeCreatedEvent $event) {

	}

	/**
	 * @param NodeCreatedEvent $event
	 * @return void
	 */
	public function applyAutoCreatedChildNodeCreatedEvent(NodeCreatedEvent $event) {
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
}
