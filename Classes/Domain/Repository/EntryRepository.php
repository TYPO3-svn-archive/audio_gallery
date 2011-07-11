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
 * Repository for the Entry object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_AudioGallery_Domain_Repository_EntryRepository extends Tx_Extbase_Persistence_Repository {

	/**
	 * Gives entries with forwarded filter items
	 * @param Tx_AudioGallery_Domain_Model_FilterOneItem $filterOne
	 * @param Tx_AudioGallery_Domain_Model_FilterOneItem $filterTwo
	 * @return array
	 */
	public function findAllFiltered(Tx_AudioGallery_Domain_Model_FilterOneItem $filterOne = null, Tx_AudioGallery_Domain_Model_FilterTwoItem $filterTwo = null) {
		$query = $this->createQuery();
		
		if($filterOne !== NULL && $filterTwo !== NULL) {
			$constraints = array();
			$constraints[] = $query->equals('filter_one_item', $filterOne->getUid());
			$constraints[] = $query->equals('filter_two_item', $filterTwo->getUid());
			$query->matching($query->logicalAnd($constraints));
		} else if($filterOne !== NULL) {
			$query->matching($query->equals('filter_one_item', $filterOne->getUid()));
		} else if($filterTwo !== NULL) {
			$query->matching($query->equals('filter_two_item', $filterTwo->getUid()));
		}

		$query->setOrderings ( array ('title' => Tx_Extbase_Persistence_QueryInterface::ORDER_ASCENDING ) );

		return $query->execute();
	}

}