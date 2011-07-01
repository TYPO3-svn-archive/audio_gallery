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
 * Controller for the Entry object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_AudioGallery_Controller_EntryController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var Tx_AudioGallery_Domain_Repository_FilterGroupRepository
	 */
	protected $filterGroupRepository;
	
	/**
	 * @var Tx_AudioGallery_Domain_Repository_EntryRepository
	 */
	protected $entryRepository;
	
	/**
	 * initialize action
	 */
	protected function initializeAction() {
		$this->filterGroupRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_FilterGroupRepository' );
		$this->entryRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_EntryRepository' );

		$extPath = t3lib_extMgm::siteRelPath ( 'jwplayer' );
		$file = $extPath . 'Resources/Public/Player/jwplayer.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsLibrary ( 'jwplayer', $file, 'text/javascript',TRUE ,TRUE);
	}
	
	/**
	 * index action
	 *
	 * @return string The rendered list action
	 */
	public function indexAction() {
		$filterGroups = $this->filterGroupRepository->findAll();
		$entries = $this->entryRepository->findAll();
		$entries = $this->addJwplayerConfig($entries);
		$codeGenerator = $this->objectManager->get ('Tx_Addthis_CodeGenerator');
		$this->view->assign('addthis_config',$codeGenerator->getConfigJs());
		$this->view->assign('addthis_jsurl',$codeGenerator->getJsImport());
		$this->view->assign ( 'filterGroups', $filterGroups );
		$this->view->assign ( 'entries', $entries );
	}
	/**
	 * show single entry
	 * @param Tx_AudioGallery_Domain_Model_Entry $entry
	 */
	public function showAction(Tx_AudioGallery_Domain_Model_Entry $entry) {
		$entries = $this->addJwplayerConfig(array($entry));
		$codeGenerator = $this->objectManager->get ('Tx_Addthis_CodeGenerator');
		$metaTags = '';
		$metaTags .= '<meta property="og:title" content="'.$entry->getTitle().'"/>'.PHP_EOL;
		$metaTags .= '<meta property="og:image" content="'.$entry->getPreviewImageSrc().'"/> '.PHP_EOL;
		$metaTags .= '<meta property="og:type" content="website"/> '.PHP_EOL;
		$GLOBALS['TSFE']->additionalHeaderData['audio_galery']  = $metaTags;
		$this->view->assign ( 'entry', $entry );
		$this->view->assign('addthis_config',$codeGenerator->getConfigJs());
		$this->view->assign('addthis_jsurl',$codeGenerator->getJsImport());
	}
	/**
	 * @param $entries
	 */
	protected function addJwplayerConfig($entries) {

		foreach ($entries as $entry) {
			$settings = array();
			$settings['player_id'] = 'player' . $entry->getUid();
			$settings['file'] = $entry->getAudioFileSrc();
			$settings['image'] = $entry->getPreviewImageSrc();
			$settings['volume'] = 15;
			$settings['height'] = 80;
			$settings['width'] = 123;
			$settings['skin'] = 'fileadmin/files_congstar/bekle/bekle.xml';
			
			$config = new Tx_Jwplayer_Config();
			$config->setSettings($settings);
			
			$entry->setJwplayerConfig($config->getJsConfig());
		}

		return $entries;
	}
	
}
?>