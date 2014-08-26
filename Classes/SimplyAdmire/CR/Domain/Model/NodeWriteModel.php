<?php
namespace SimplyAdmire\CR\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Dto\NodePointer;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use SimplyAdmire\CR\Domain\Repository\EventRepository;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class NodeWriteModel {

	/**
	 * @var array
	 */
	protected $eventEmitQueue = array();

	/**
	 * @var EventRepository
	 */
	protected $eventRepository;

	/**
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	protected $entityManager;

	/**
	 * @param NodePointer $parentNodePointer
	 * @param string $nodeName
	 * @param NodeType $nodeType
	 * @param array $properties
	 * @param string $workspace
	 * @param array $dimensions
	 * @param EventRepository $eventRepository
	 */
	public function __construct(NodePointer $parentNodePointer, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array(), EventRepository $eventRepository, \Doctrine\Common\Persistence\ObjectManager $entityManager) {
		try {
			$this->eventRepository = $eventRepository;
			$this->entityManager = $entityManager;

			$nodeCreatedEvent = new NodeCreatedEvent(
				$parentNodePointer,
				$nodeName,
				$nodeType,
				$properties,
				$workspace,
				$dimensions
			);

			$event = new Event($nodeCreatedEvent);
			$this->eventRepository->add($event);
			$this->persistAllEvents();
			$this->eventEmitQueue[] = $event;
		} catch (\Exception $exception) {
			// TODO: Add logging
		}
	}

	/**
	 * @return void
	 */
	protected function persistAllEvents() {
		foreach ($this->entityManager->getUnitOfWork()->getIdentityMap() as $className => $entities) {
			foreach ($entities as $entityToPersist) {
				if ($entityToPersist instanceof Event) {
					$this->entityManager->flush($entityToPersist);
				}
			}
		}
	}

	/**
	 * @return array
	 */
	public function getEventsToEmit() {
		return $this->eventEmitQueue;
	}

	/**
	 * @return void
	 */
	public function flushEventsToEmit() {
		$this->eventEmitQueue = [];
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
