<?php
namespace SimplyAdmire\CR\Service;

use TYPO3\Flow\Annotations as Flow;

abstract class AbstractNodeService {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

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