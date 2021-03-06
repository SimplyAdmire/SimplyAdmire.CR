<?php
namespace SimplyAdmire\CR\Domain\Events;

use SimplyAdmire\CR\Domain\Dto\NodeReference;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class NodeCreatedEvent extends AbstractEvent {

	/**
	 * @var string
	 */
	protected $identifier;

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
	 * @param string $nodeName
	 * @param string $identifier
	 * @param NodeType $nodeType
	 * @param array $properties
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodeReference $parentNodeReference, $identifier, $nodeName, NodeType $nodeType, array $properties = array(), $workspace, array $dimensions = array()) {
		parent::__construct($parentNodeReference, $workspace, $dimensions);
		$this->identifier = $identifier;
		$this->nodeName = $nodeName;
		$this->nodeType = $nodeType->getName();
		$this->properties = $properties;
	}

	/**
	 * @return string
	 */
	public function getIdentifier() {
		return $this->identifier;
	}

	/**
	 * @return string
	 */
	public function getNodeName() {
		return $this->nodeName;
	}

	/**
	 * @return string
	 */
	public function getNodeType() {
		return $this->nodeType;
	}

	/**
	 * @return array
	 */
	public function getProperties() {
		return $this->properties;
	}

}
