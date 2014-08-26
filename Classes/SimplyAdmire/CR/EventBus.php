<?php
namespace SimplyAdmire\CR;

use SimplyAdmire\CR\Projection\NodeProjection;
use TYPO3\Flow\Annotations as Flow;
use Evenement\EventEmitter;

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
	}
}