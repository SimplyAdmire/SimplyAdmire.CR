<?php
namespace SimplyAdmire\CR\Domain\Events;

use SimplyAdmire\CR\Domain\Dto\NodePointer;
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
	 * @param NodePointer $parentNodePointer
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodePointer $parentNodePointer, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array()) {
		parent::__construct($parentNodePointer, $workspace, $dimensions);
		$this->nodeName = $nodeName;
		$this->nodeType = $nodeType->getName();
		$this->properties = $properties;
	}

}
