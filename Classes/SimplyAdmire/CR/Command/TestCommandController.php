<?php
namespace SimplyAdmire\CR\Command;

use SimplyAdmire\CR\CommandBus;
use SimplyAdmire\CR\Commands\CreateNodeCommand;
use SimplyAdmire\CR\Service\NodeReadService;
use SimplyAdmire\HarvestApi\Api;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

class TestCommandController extends CommandController {

	/**
	 * @Flow\Inject
	 * @var CommandBus
	 */
	protected $commandBus;

	/**
	 * @Flow\Inject
	 * @var NodeReadService
	 */
	protected $nodeReadService;

	/**
	 *
	 */
	public function createNodeCommand() {
		$newNodeCommand = new CreateNodeCommand(
			$this->nodeReadService->findRootNode(),
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