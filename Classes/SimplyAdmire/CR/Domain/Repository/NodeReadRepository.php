<?php
namespace SimplyAdmire\CR\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodeReadRepository extends AbstractNodeRepository {

	/**
	 * @param string $identifier
	 * @param string $workspaceName
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface
	 */
	public function findByIdentifier($identifier, $workspaceName = 'live') {
		$context = $this->createContext($workspaceName);
		return $context->getNodeByIdentifier($identifier);
	}

	/**
	 * @param string $workspaceName
	 * @return \TYPO3\TYPO3CR\Domain\Model\NodeInterface
	 */
	public function findRootNode($workspaceName = 'live') {
		$context = $this->createContext($workspaceName);
		return $context->getRootNode();
	}

}