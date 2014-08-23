<?php
namespace SimplyAdmire\CR\Domain\Commands;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use SimplyAdmire\CR\Exceptions;

/**
 *
 */
class CreateNodeCommand {

	/**
	 * @var NodeInterface
	 */
	public $parentNode;

	/**
	 * @var string
	 */
	public $suggestedNodeName;

	/**
	 * @var NodeType
	 */
	public $nodeType;

	/**
	 * @var array
	 */
	public $properties = array();

	/**
	 * @var array
	 */
	public $dimensions = array();

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

}