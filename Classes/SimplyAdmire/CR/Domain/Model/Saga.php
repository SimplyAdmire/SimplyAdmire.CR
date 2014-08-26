<?php
namespace SimplyAdmire\CR\Domain\Model;

use SimplyAdmire\CR\Domain\Events\AllAutoCreatedChildNodesCreatedEvent;
use SimplyAdmire\CR\Domain\Repository\EventRepository;
use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\EventBus;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

class Saga {

	/**
	 * @var array
	 */
	protected $commands = array();

	/**
	 * @Flow\Inject
	 * @var CommandBus
	 */
	protected $commandBus;

	/**
	 * @Flow\Inject
	 * @var EventBus
	 */
	protected $eventBus;

	/**
	 * @param object $command
	 * @param string $readyEvent
	 */
	public function addCommand($command, $readyEvent) {
		if (!isset($this->commands[$readyEvent])) {
			$this->commands[$readyEvent] = [];
		}

		$this->commands[$readyEvent][] = $command;
	}

	/**
	 *
	 */
	public function handle() {
		foreach (array_keys($this->commands) as $readyEvent) {
			$this->eventBus->on($readyEvent, function($event, $correlationId) {
				/** @var \SimplyAdmire\CR\Domain\Events\AutoCreatedChildNodeCreatedEvent $event */
				$eventClassName = get_class($event);
				array_shift($this->commands[$eventClassName]);

				if ($this->commands[$eventClassName] === array()) {
					$nodeTypeManager = new NodeTypeManager();

					$allAutoCreatedChildNodesCreatedEvent = new AllAutoCreatedChildNodesCreatedEvent(
						$event->getNodeReference(),
						$event->getIdentifier(),
						$event->getNodeName(),
						$nodeTypeManager->getNodeType($event->getNodeType()),
						$event->getProperties(),
						$event->getWorkspace(),
						$event->getDimensions()
					);

					$domainEvent = new Event($allAutoCreatedChildNodesCreatedEvent);
					$domainEvent->setCorrelationId($correlationId);

					$eventRepository = new EventRepository();
					$eventRepository->add($domainEvent);

					$this->eventBus->emit(get_class($domainEvent->getEventObject()), array('event' => $domainEvent->getEventObject(), 'correlationId' => $correlationId));
				}
			});
		}

		foreach ($this->commands as $readyEvent => $commands) {
			foreach ($commands as $command) {
				$this->commandBus->handle($command);
			}
		}
	}
}