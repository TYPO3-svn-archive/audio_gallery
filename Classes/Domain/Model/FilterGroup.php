<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Max Beer <max.beer@aoemedia.de>, AOE media GmbH
*  			
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Container for categories
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_AudioGallery_Domain_Model_FilterGroup extends Tx_Extbase_DomainObject_AbstractEntity {
	
	/**
	 * Name of filter
	 * @var string
	 * @validate NotEmpty
	 */
	protected $name;
	
	/**
	 * filterItem
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_AudioGallery_Domain_Model_FilterItem>
	 */
	protected $filterItem;
	/**
	 * The constructor. Initializes all Tx_Extbase_Persistence_ObjectStorage instances.
	 */
	public function __construct() {
		$this->filterItem = new Tx_Extbase_Persistence_ObjectStorage();
	}
	/**
	 * Setter for name
	 *
	 * @param string $name Name of filter
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Getter for name
	 *
	 * @return string Name of filter
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Setter for filterItem
	 *
	 * @param Tx_Extbase_Persistence_ObjectStorage<Tx_AudioGallery_Domain_Model_FilterItem> $filterItem filterItem
	 * @return void
	 */
	public function setFilterItem(Tx_Extbase_Persistence_ObjectStorage $filterItem) {
		$this->filterItem = $filterItem;
	}

	/**
	 * Getter for filterItem
	 *
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_AudioGallery_Domain_Model_FilterItem> filterItem
	 */
	public function getFilterItem() {
		return $this->filterItem;
	}
	
	/**
	 * Adds a FilterItem
	 *
	 * @param Tx_AudioGallery_Domain_Model_FilterItem the FilterItem to be added
	 * @return void
	 */
	public function addFilterItem(Tx_AudioGallery_Domain_Model_FilterItem $filterItem) {
		$this->filterItem->attach($filterItem);
	}
	
	/**
	 * Removes a FilterItem
	 *
	 * @param Tx_AudioGallery_Domain_Model_FilterItem the FilterItem to be removed
	 * @return void
	 */
	public function removeFilterItem(Tx_AudioGallery_Domain_Model_FilterItem $filterItem) {
		$this->filterItem->detach($filterItem);
	}
	
}
?>