<?php
namespace SimplyAdmire\CR\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Service\Context;
use TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

/**
 * @Flow\Scope("singleton")
 */
class NodeReadRepository {


	/**
	 * @Flow\Inject
	 * @var ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @param array $contextProperties
	 * @return Context
	 */
	protected function createContentContext($contextProperties = array()) {
		return $this->contextFactory->create($contextProperties);
	}

	/**
	 * @return NodeInterface
	 */
	public function getRootNode() {
		return $this->createContentContext()->getRootNode();
	}

	/**
	 * @param NodeInterface $parentNode
	 * @return array
	 */
	public function findAllChildNodes(NodeInterface $parentNode) {
		return $parentNode->getChildNodes();
	}

}