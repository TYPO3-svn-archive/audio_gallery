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
	 * @param array $selectedFilterItems selectedFilters
	 * @return Tx_Extbase_Persistence_ObjectStorage<Tx_AudioGallery_Domain_Model_FilterItem>
	 */
	public function findAllFiltered($selectedFilterItems) {

		$extbaseFrameworkConfiguration = Tx_Extbase_Dispatcher::getExtbaseFrameworkConfiguration();
		$pidList = implode(', ', t3lib_div::intExplode(',', $extbaseFrameworkConfiguration['persistence']['storagePid']));

		$sql = "SELECT DISTINCT e.*
				FROM tx_audiogallery_domain_model_entry e
					JOIN tx_audiogallery_entry_filteritem_mm m
						ON e.uid = m.uid_local
					JOIN tx_audiogallery_domain_model_filteritem f
						ON m.uid_foreign = f.uid
				WHERE e.deleted + f.deleted = 0
					AND e.pid IN(".$pidList.")
					AND f.pid IN(".$pidList.")
				ORDER BY e.title DESC";

		foreach ($selectedFilterItems as $selectedFilterItem) {
			$sql = "SELECT e.*
					FROM (".$sql.") e
						JOIN tx_audiogallery_entry_filteritem_mm m
							ON e.uid = m.uid_local
						JOIN tx_audiogallery_domain_model_filteritem f
							ON m.uid_foreign = f.uid
					WHERE f.uid = ".$selectedFilterItem->getUid();
		}
		
		$query = $this->createQuery();
		$query->statement($sql);
		
		return $query->execute();
		
	}

}