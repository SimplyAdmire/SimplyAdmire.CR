<?php
namespace SimplyAdmire\CR\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Dto\NodePointer;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class NodeWriteModel {

	/**
	 * @var array
	 */
	protected $events = array();

	/**
	 * @param NodePointer $parentNodePointer
	 * @param string $nodeName
	 * @param NodeType $nodeType
	 * @param array $properties
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodePointer $parentNodePointer, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array()) {
		try {
			$nodeCreatedEvent = new NodeCreatedEvent(
				$parentNodePointer,
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
