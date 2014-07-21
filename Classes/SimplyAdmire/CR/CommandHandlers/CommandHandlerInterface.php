<?php
namespace SimplyAdmire\CR\CommandHandlers;

use SimplyAdmire\CR\Commands\CommandInterface;

interface CommandHandlerInterface {

	public function handle(CommandInterface $command);

}