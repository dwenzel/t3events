<?php
namespace Webfox\T3events\Resource;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class VectorImage extends \DOMDocument
{
    /**
     * @var \DOMXPath
     */
    protected $xPath;

    /**
     * Replaces text node children of a node
     *
     * @param string $nodeId
     * @param string $content
     */
    public function replaceNodeText($nodeId, $content)
    {
        $element = $this->getElementById($nodeId);
        if ($element === null) {
            return;
        }

        while ($element->hasChildNodes()) {
            $element->removeChild($element->firstChild);
        }
        $textNode = $this->createTextNode($content);
        $element->appendChild($textNode);
    }

    /**
     * Sets an attribute of a set of elements
     * to a common value
     *
     * @param array $elementIds Array of IDs of elements
     * @param string $attributeName Name of attribute to set
     * @param string $attributeValue Value to set
     */
    protected function setElementsAttribute(array $elementIds, $attributeName, $attributeValue)
    {
        foreach ($elementIds as $elementId) {
            $element = $this->getElementById($elementId);
            if ($element === null) {
                continue;
            }
            $element->setAttribute($attributeName, $attributeValue);
        }
    }

    /**
     * Hides elements by id
     *
     * @param array $elementIds
     */
    public function hideElements(array $elementIds)
    {
        $this->setElementsAttribute($elementIds, 'style', 'display:none');
    }

    /**
     * Shows elements by id
     *
     * @param array $elementIds
     */
    public function showElements(array $elementIds)
    {
        $this->setElementsAttribute($elementIds, 'style', 'display:inline');
    }

    /**
     * Gets an element by id
     * We overwrite parents method in order to
     * avoid having to validate the document against a DTD
     * which is quite slow
     *
     * @param string $elementId
     * @return \DOMNode | null
     */
    public function getElementById($elementId)
    {
        return $this->getXPath()->query("//*[@id='" . $elementId . "']")->item(0);
    }

    /**
     * @return \DOMXPath
     */
    public function getXPath()
    {
        if(!$this->xPath instanceof \DOMXPath) {
            $this->xPath = new \DOMXPath($this);
        }

        return $this->xPath;
    }
}
