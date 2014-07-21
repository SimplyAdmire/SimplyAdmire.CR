<?php
namespace SimplyAdmire\CR\Service;

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class NodeReadService extends AbstractNodeService {

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