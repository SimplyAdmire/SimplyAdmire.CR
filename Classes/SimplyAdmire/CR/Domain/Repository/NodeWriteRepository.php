<?php
namespace SimplyAdmire\CR\Domain\Repository;

use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
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
			// TODO: store event

			// TODO: Move code below to a method listening to the event
			$parentNode = $this->nodeReadRepository->findByIdentifier(
				$command->parentNode->identifier,
				$command->parentNode->workspace,
				$command->parentNode->dimensions
			);

			$newNode = $parentNode->createNode(
				$command->suggestedNodeName,
				$this->nodeTypeManager->getNodeType($command->nodeTypeName),
				NULL,
				$command->dimensions
			);

			foreach ($command->properties as $propertyName => $propertyValue) {
				$newNode->setProperty($propertyName, $propertyValue);
			}

			return $newNode instanceof NodeInterface;
		} catch (\Exception $exception) {
			// TODO: log something
			return FALSE;
		}
	}
}