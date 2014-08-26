<?php
namespace SimplyAdmire\CR\Domain\Events;

use SimplyAdmire\CR\Domain\Dto\NodeReference;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class NodeCreatedEvent extends AbstractEvent {

	/**
	 * @var string
	 */
	protected $nodeName;

	/**
	 * @var string
	 */
	protected $nodeType;

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @param NodeReference $parentNodeReference
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodeReference $parentNodeReference, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array()) {
		parent::__construct($parentNodeReference, $workspace, $dimensions);
		$this->nodeName = $nodeName;
		$this->nodeType = $nodeType->getName();
		$this->properties = $properties;
	}

}
