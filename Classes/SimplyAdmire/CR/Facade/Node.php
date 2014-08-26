<?php
namespace SimplyAdmire\CR\Facade;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\TYPO3CR\Domain\Model\NodeType;

/**
 * Custom node implementation as a facade around the CR node to support CQRS / Event sourcing
 */
class Node extends \TYPO3\TYPO3CR\Domain\Model\Node {

	/**
	 * Creates, adds and returns a child node of this node. Also sets default
	 * properties and creates default subnodes.
	 *
	 * @param string $name Name of the new node
	 * @param NodeType $nodeType Node type of the new node (optional)
	 * @param string $identifier The identifier of the node, unique within the workspace, optional(!)
	 * @param array $dimensions Content dimension values to set on the node (Array of dimension names to array of values)
	 * @return NodeInterface
	 */
	public function createNode($name, NodeType $nodeType = NULL, $identifier = NULL, array $dimensions = NULL) {
		$newNode = $this->createSingleNode($name, $nodeType, $identifier, $dimensions);
		if ($nodeType !== NULL) {
			foreach ($nodeType->getDefaultValuesForProperties() as $propertyName => $propertyValue) {
				$newNode->setProperty($propertyName, $propertyValue);
			}

			// We do not automatically create the childnodes, but issue new comments. Comment code below is the original code from CR
//			foreach ($nodeType->getAutoCreatedChildNodes() as $childNodeName => $childNodeType) {
//				$childNodeIdentifier = $this->buildAutoCreatedChildNodeIdentifier($childNodeName, $newNode->getIdentifier());
//				$newNode->createNode($childNodeName, $childNodeType, $childNodeIdentifier, $dimensions);
//			}
		}

		$this->context->getFirstLevelNodeCache()->flush();
		$this->emitNodeAdded($newNode);

		return $newNode;
	}

}