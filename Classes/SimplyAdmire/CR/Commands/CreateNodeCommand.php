<?php
namespace SimplyAdmire\CR\Commands;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use SimplyAdmire\CR\Exceptions;

class CreateNodeCommand {

	/**
	 * @var NodeInterface
	 */
	protected $parentNode;

	/**
	 * @var string
	 */
	protected $suggestedNodeName;

	/**
	 * @var NodeType
	 */
	protected $nodeType;

	/**
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @var array
	 */
	protected $dimensions = array();

	/**
	 * @param NodeInterface $parentNode
	 * @param string $suggestedNodeName
	 * @param string|NodeType $nodeType
	 * @param array $properties
	 * @param array $dimensions
	 */
	public function __construct(NodeInterface $parentNode, $suggestedNodeName, $nodeType, array $properties = array(), array $dimensions = array()) {
		if (is_string($nodeType)) {
			$nodeTypeManager = new NodeTypeManager();
			$nodeType = $nodeTypeManager->getNodeType($nodeType);
		}

		$this->parentNode = $parentNode;
		$this->suggestedNodeName = $suggestedNodeName;
		$this->nodeType = $nodeType;
		$this->properties = $properties;
		$this->dimensions = $dimensions;
		$this->nodeType = $nodeType;
	}

	/**
	 * @return array
	 */
	public function getDimensions() {
		return $this->dimensions;
	}

	/**
	 * @return NodeType
	 */
	public function getNodeType() {
		return $this->nodeType;
	}

	/**
	 * @return NodeInterface
	 */
	public function getParentNode() {
		return $this->parentNode;
	}

	/**
	 * @return array
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * @return string
	 */
	public function getSuggestedNodeName() {
		return $this->suggestedNodeName;
	}

}