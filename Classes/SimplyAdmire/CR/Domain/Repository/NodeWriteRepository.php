<?php
namespace SimplyAdmire\CR\Domain\Repository;

use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
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

			foreach ($nodeWriteModel->getAndFlushEventsToEmit() as $event) {
				$this->eventRepository->add($event);
				$this->eventBus->emit(get_class($event), array('eventObject' => $event));
			}

			return TRUE;
		} catch (\Exception $exception) {
			// TODO: log something
			return FALSE;
		}
	}
}