<?php
namespace SimplyAdmire\CR\Domain\Model;

use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

class Node extends \TYPO3\TYPO3CR\Domain\Model\Node {

	/**
	 * Creates, adds and returns a child node of this node. Also sets default
	 * properties and creates default subnodes.
	 *
	 * @param string $name Name of the new node
	 * @param NodeType $nodeType Node type of the new node (optional)
	 * @param string $identifier The identifier of the node, unique within the workspace, optional(!)
	 * @param array $dimensions Content dimension values to set on the node (Array of dimension names to array of values)
	 * @param array $properties
	 * @return NodeInterface
	 */
	public function createNode($name, NodeType $nodeType = NULL, $identifier = NULL, array $dimensions = NULL, array $properties = array()) {
		
	}

}
