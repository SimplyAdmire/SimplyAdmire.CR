<?php
namespace SimplyAdmire\CR\Command;

use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\Domain\Commands\CreateNodeCommand;
use SimplyAdmire\CR\Domain\Repository\NodeReadRepository;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;
use SimplyAdmire\CR\Domain\Dto\NodePointer;

class TestCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var CommandBus
	 */
	protected $commandBus;

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
		$nodeDto = new NodePointer($rootNode->getIdentifier(), $rootNode->getWorkspace()->getName(), $rootNode->getDimensions());

		$newNodeCommand = new CreateNodeCommand(
			$nodeDto,
			uniqid('node-command'),
			'My.Package:Person',
			array(
				'firstName' => 'Test'
			)
		);
		$result = $this->commandBus->handle($newNodeCommand);

		if ($result) {
			$this->outputLine('Create node command handled successfully');
		} else {
			$this->outputLine('Something went wrong for sure');
		}
	}

}