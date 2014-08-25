<?php
namespace SimplyAdmire\CR\Domain\Model;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Domain\Dto\NodePointer;
use SimplyAdmire\CR\Domain\Repository\NodeReadRepository;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class Node {

	/**
	 * @Flow\Inject
	 * @var NodeReadRepository
	 */
	protected $nodeReadRepository;

	/**
	 * @var NodeInterface
	 */
	protected $facadeNode;

	/**
	 * @param NodePointer $parentNodePointer
	 * @param string $nodeName
	 * @param NodeType $nodeType
	 * @param array $properties
	 */
	public function __construct(NodePointer $parentNodePointer, $nodeName, NodeType $nodeType, array $properties = array()) {
		$this->nodeReadRepository = new NodeReadRepository();

		$parentNode = $this->nodeReadRepository->findByIdentifier(
			$parentNodePointer->identifier,
			$parentNodePointer->workspace,
			$parentNodePointer->dimensions
		);

		$this->facadeNode = $parentNode->createNode(
			$nodeName,
			$nodeType,
			NULL,
			$parentNodePointer->dimensions,
			$properties
		);
	}

}