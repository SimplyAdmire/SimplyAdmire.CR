<?php
namespace SimplyAdmire\CR\Domain\Repository;

use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use SimplyAdmire\CR\Domain\Model\Node;
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
	 * @param CreateNodeCommand $command
	 * @return NodeInterface
	 */
	public function createNode(CreateNodeCommand $command) {
		try {
			return new Node(
				$command->parentNode,
				$command->suggestedNodeName,
				$this->nodeTypeManager->getNodeType($command->nodeTypeName),
				$command->properties
			);

			return TRUE;
		} catch (\Exception $exception) {
			// TODO: log something
			return FALSE;
		}
	}
}