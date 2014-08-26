<?php
namespace SimplyAdmire\CR\Domain\Repository;

use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use SimplyAdmire\CR\Domain\Model\Event;
use SimplyAdmire\CR\Domain\Model\NodeWriteModel;
use SimplyAdmire\CR\EventBus;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * @Flow\Scope("singleton")
 */
class NodeWriteRepository extends AbstractNodeRepository {

	/**
	 * @Flow\Inject
	 * @var NodeReadRepository
	 */
	protected $nodeReadRepository;

	/**
	 * @Flow\Inject
	 * @var EventBus
	 */
	protected $eventBus;

	/**
	 * @Flow\Inject
	 * @var EventRepository
	 */
	protected $eventRepository;

	/**
	 * @param CreateNodeCommand $command
	 * @return NodeInterface
	 */
	public function createNode(CreateNodeCommand $command) {
		try {
			$nodeWriteModel = new NodeWriteModel(
				$command->parentNode,
				$command->suggestedNodeName,
				$this->nodeTypeManager->getNodeType($command->nodeTypeName),
				$command->properties,
				$command->parentNode->workspace,
				$command->dimensions
			);

			/** @var Event $event */
			foreach ($nodeWriteModel->getAndFlushEventsToEmit() as $event) {
				$event->setCorrelationId($command->correlationId);
				$this->eventRepository->add($event);
				$nodeWriteModel->applyNodeCreatedEvent($event->getEventObject());
				$this->eventBus->emit(get_class($event->getEventObject()), array('eventObject' => $event, 'correlationId' => $command->correlationId));
			}

			return TRUE;
		} catch (\Exception $exception) {
			// TODO: log something
			return FALSE;
		}
	}
}