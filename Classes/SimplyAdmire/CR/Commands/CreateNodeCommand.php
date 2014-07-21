<?php
namespace SimplyAdmire\CR\Commands;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use SimplyAdmire\CR\Exceptions;

class CreateNodeCommand implements CommandInterface {

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
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

	/**
	 * @param NodeInterface $parentNode
	 * @param string $suggestedNodeName
	 * @param string|NodeType $nodeType
	 * @param array $properties
	 * @param array $dimensions
	 * @throws Exceptions\UnknownNodeTypeException
	 * @throws Exceptions\InvalidArgumentTypeException
	 */
	public function __construct(NodeInterface $parentNode, $suggestedNodeName, $nodeType, array $properties = array(), array $dimensions = array()) {
		$this->parentNode = $parentNode;
		$this->suggestedNodeName = $suggestedNodeName;
		$this->nodeType = $nodeType;
		$this->properties = $properties;
		$this->dimensions = $dimensions;
		$this->nodeType = $nodeType;
	}

	/**
	 * Initializes the command, used for methods that depend on injected objects
	 *
	 * @throws \SimplyAdmire\CR\Exceptions\InvalidArgumentTypeException
	 * @throws \SimplyAdmire\CR\Exceptions\UnknownNodeTypeException
	 */
	public function initializeObject() {
		if (is_string($this->nodeType)) {
			try {
				$this->nodeType = $this->nodeTypeManager->getNodeType($this->nodeType);
			} catch (\Exception $exception) {
				throw new Exceptions\UnknownNodeTypeException();
			}
		} else {
			throw new Exceptions\InvalidArgumentTypeException();
		}
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
	 * @return NodeTypeManager
	 */
	public function getNodeTypeManager() {
		return $this->nodeTypeManager;
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