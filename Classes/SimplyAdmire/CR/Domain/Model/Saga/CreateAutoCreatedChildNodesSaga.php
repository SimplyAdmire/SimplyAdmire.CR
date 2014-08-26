<?php
namespace SimplyAdmire\CR\Domain\Model\Saga;

use SimplyAdmire\CR\Domain\Events\AllAutoCreatedChildNodesCreatedEvent;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\EventBus;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

class CreateAutoCreatedChildNodesSaga {

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
	 * @var \Closure
	 */
	protected $eventListener;

	/**
	 * @param object $command
	 */
	public function addCommand($command) {
		$this->commands[] = $command;
	}

	/**
	 *
	 */
	public function handle() {
		$this->eventListener = function($event, $correlationId) {
			/** @var NodeCreatedEvent $event */
			$eventClassName = get_class($event);
			array_shift($this->commands[$eventClassName]);

			if ($this->commands[$eventClassName] === array()) {
				$this->eventBus->removeListener('SimplyAdmire\CR\Domain\Events\AutoCreatedChildNodeCreatedEvent', $this->eventListener);
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

				$this->eventBus->emit(get_class($allAutoCreatedChildNodesCreatedEvent), array('event' => $allAutoCreatedChildNodesCreatedEvent, 'correlationId' => $correlationId));
			}
		};

		$this->eventBus->on('SimplyAdmire\CR\Domain\Events\AutoCreatedChildNodeCreatedEvent', $this->eventListener);

		foreach ($this->commands as $command) {
			$this->commandBus->handle($command);
		}
	}
}