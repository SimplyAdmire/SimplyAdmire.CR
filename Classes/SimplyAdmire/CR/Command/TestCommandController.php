<?php
namespace SimplyAdmire\CR\Command;

use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use SimplyAdmire\CR\EventBus;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use SimplyAdmire\CR\Domain\Dto\NodeReference;
use TYPO3\Flow\Utility\Algorithms;

class TestCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\TYPO3CR\Domain\Service\ContextFactoryInterface
	 */
	protected $contextFactory;

	/**
	 * @Flow\Inject
	 * @var CommandBus
	 */
	protected $commandBus;

	/**
	 * @Flow\Inject
	 * @var EventBus
	 */
	protected $eventBus;

	/**
	 *
	 */
	public function createNodeCommand() {
		$contentContext = $this->createContext('live');
		$rootNode = $contentContext->getRootNode();
		$parentNodeReference = new NodeReference($rootNode->getIdentifier(), $rootNode->getWorkspace()->getName(), $rootNode->getDimensions());

		try {
			$newNodeCommand = new CreateNodeCommand(
				$parentNodeReference,
				Algorithms::generateUUID(),
				uniqid('node-command'),
				'SimplyAdmire.CR:Document',
				array(
					'title' => 'Test'
				)
			);

			$this->eventBus->on('SimplyAdmire\CR\Domain\Events\NodeCreatedEvent', function($eventObject, $correlationId) use ($newNodeCommand, $contentContext) {
				if (!$newNodeCommand->correlationId === $correlationId) {
					return;
				}
				$createdNode = $contentContext->getNodeByIdentifier($eventObject->getIdentifier());
				\TYPO3\Flow\var_dump(count($createdNode->getChildNodes()), $createdNode->getName());
			});

			$result = $this->commandBus->handle($newNodeCommand);

		} catch (\Exception $exception) {
			$result = FALSE;
		}

		if ($result === TRUE) {
			$this->outputLine('Create node command handled successfully');
		} else {
			$this->outputLine('Something went wrong for sure');
		}
	}

	/**
	 * @param string $workspaceName
	 * @param array $dimensions
	 * @return \TYPO3\TYPO3CR\Domain\Service\Context
	 */
	protected function createContext($workspaceName, array $dimensions = array()) {
		return $this->contextFactory->create(array(
			'workspaceName' => $workspaceName,
			'dimensions' => $dimensions
		));
	}

}