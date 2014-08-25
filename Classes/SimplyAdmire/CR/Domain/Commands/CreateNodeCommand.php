<?php
namespace SimplyAdmire\CR\Domain\Commands;

use SimplyAdmire\CR\Domain\Dto\Node as NodeDto;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use SimplyAdmire\CR\Annotations as CR;

/**
 * @CR\CommandHandler(callable="SimplyAdmire\CR\Domain\Repository\NodeWriteRepository->createNode")
 */
class CreateNodeCommand {

	/**
	 * @var NodeDto
	 */
	public $parentNode;

	/**
	 * @var string
	 */
	public $suggestedNodeName;

	/**
	 * @var string
	 */
	public $nodeTypeName;

	/**
	 * @var array
	 */
	public $properties = array();

	/**
	 * @var array
	 */
	public $dimensions = array();

	/**
	 * @param NodeDto $parentNode
	 * @param string $suggestedNodeName
	 * @param string|NodeType $nodeTypeName
	 * @param array $properties
	 * @param array $dimensions
	 */
	public function __construct(NodeDto $parentNode, $suggestedNodeName, $nodeTypeName, array $properties = array(), array $dimensions = array()) {
		$this->parentNode = $parentNode;
		$this->suggestedNodeName = $suggestedNodeName;
		$this->nodeTypeName = $nodeTypeName;
		$this->properties = $properties;
		$this->dimensions = $dimensions;
	}

}