<?php
namespace SimplyAdmire\CR;

use SimplyAdmire\CR\Domain\Dto\NodeReference;
use SimplyAdmire\CR\Domain\Events\NodeCreatedEvent;
use SimplyAdmire\CR\Domain\Factory\SagaFactory;
use SimplyAdmire\CR\Projection\NodeProjection;
use TYPO3\Flow\Annotations as Flow;
use Evenement\EventEmitter;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

/**
 * @Flow\Scope("singleton")
 */
class EventBus extends EventEmitter {

	public function __construct() {
		// Invent a convention
		$this->on(
			'SimplyAdmire\CR\Domain\Events\NodeCreatedEvent',
			array(
				new NodeProjection(),
				'onNodeCreated'
			)
		);
		$this->on(
			'SimplyAdmire\CR\Domain\Events\AutoCreatedChildNodeCreatedEvent',
			array(
				new NodeProjection(),
				'onNodeCreated'
			)
		);
		$this->on(
			'SimplyAdmire\CR\Domain\Events\NodeCreatedEvent', function(NodeCreatedEvent $event, $correlationId) {
				$nodeTypeManager = new NodeTypeManager();

				$sagaFactory = new SagaFactory();
				$saga = $sagaFactory->createCreateChildNodesSaga(
					new NodeReference(
						$event->getIdentifier(),
						$event->getWorkspace(),
						$event->getDimensions()
					),
					$nodeTypeManager->getNodeType($event->getNodeType()),
					$correlationId
				);

				$saga->handle();
			}
		);
	}
}