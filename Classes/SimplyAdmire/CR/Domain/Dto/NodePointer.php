<?php
namespace SimplyAdmire\CR\Domain\Dto;

class NodePointer {

	/**
	 * @var string
	 */
	public $identifier;

	/**
	 * @var string
	 */
	public $workspace;

	/**
	 * @var array
	 */
	public $dimensions = array();

	/**
	 * @param string $identifier
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct($identifier, $workspace, array $dimensions = array()) {
		$this->identifier = $identifier;
		$this->workspace = $workspace;
		$this->dimensions = $dimensions;
	}
}