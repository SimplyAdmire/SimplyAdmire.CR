<?php
namespace SimplyAdmire\CR\Command;

use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use SimplyAdmire\CR\Domain\Repository\NodeReadRepository;
use SimplyAdmire\CR\EventBus;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use SimplyAdmire\CR\Domain\Dto\NodeReference;

class TestCommandController extends CommandController {

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
	 * @Flow\Inject
	 * @var NodeReadRepository
	 */
	protected $nodeReadRepository;

	/**
	 *
	 */
	public function createNodeCommand() {
		$rootNode = $this->nodeReadRepository->findRootNode();
		$nodeDto = new NodeReference($rootNode->getIdentifier(), $rootNode->getWorkspace()->getName(), $rootNode->getDimensions());

		try {
			$newNodeCommand = new CreateNodeCommand(
				$nodeDto,
				uniqid('node-command'),
				'My.Package:Person',
				array(
					'firstName' => 'Test'
				)
			);
			$this->eventBus->once('SimplyAdmire\CR\Domain\Events\NodeCreatedEvent', function($eventObject, $correlationId) use ($newNodeCommand) {
				if (!$newNodeCommand->correlationId === $correlationId) {
					return;
				}

				\TYPO3\Flow\var_dump($eventObject);
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

}