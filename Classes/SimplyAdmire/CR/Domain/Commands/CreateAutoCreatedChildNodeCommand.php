<?php
namespace SimplyAdmire\CR\Domain\Commands;

use TYPO3\Flow\Annotations as Flow;
use SimplyAdmire\CR\Annotations as CR;

/**
 * @CR\CommandHandler(callable="SimplyAdmire\CR\Domain\Repository\NodeWriteRepository->createAutoCreatedChildNode")
 * @Flow\Proxy(false)
 */
class CreateAutoCreatedChildNodeCommand extends CreateNodeCommand {

}