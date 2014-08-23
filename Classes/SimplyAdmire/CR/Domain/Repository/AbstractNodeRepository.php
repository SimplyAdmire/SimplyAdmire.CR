<?php
namespace SimplyAdmire\CR\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;

abstract class AbstractNodeRepository {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @param string $workspaceName
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName) {
		return $this->contextFactory->create(array(
			'workspaceName' => $workspaceName
		));
	}

}