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
		$this->addOpenGraphMetaTags($entry);
		$entries = $this->addJwplayerConfig(array($entry));
		$this->view->assign ( 'entry', $entry );
		$codeGenerator = $this->objectManager->get ('Tx_Addthis_CodeGenerator');
		$this->view->assign('addthis_config',$codeGenerator->getConfigJs());
		$this->view->assign('addthis_jsurl',$codeGenerator->getJsImport());
	}
	/**
	 * @param Tx_AudioGallery_Domain_Model_Entry $entry
	 */
	private function addOpenGraphMetaTags(Tx_AudioGallery_Domain_Model_Entry $entry){
		$flashConfigGenerator	= $this->objectManager->get ('Tx_Jwplayer_FlashConfigGenerator');
		$jwPid					= $this->getJWPlayerSinglePageId();
		
		/* generate the expected arguments for the jwplayer controller*/
		$arguments = array();
		$arguments['tx_jwplayer_pi1'] = array();
		$arguments['tx_jwplayer_pi1']['action'] = 'showVideo';
		$arguments['tx_jwplayer_pi1']['controller'] = 'Player';
		
		$settings['autostart']	= TRUE;
		$settings['audio']		= $entry->getAudioFileUrl();
		
		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $flashConfigGenerator->encode($settings, $entry->getPreviewImageUrl());
		$url = $this->uriBuilder->setTargetPageUid($jwPid)->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();
		
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:type" content="video"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta name="medium" content="video"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video" content="'.$url.'"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:type" content="application/x-shockwave-flash"/>' );
	}
	/**
	 * Read the configured pageid where the jwplayer plugin for facebook redirects is installed.
	 * 
	 * @return int
	 */
	protected function getJWPlayerSinglePageId() {
		if(array_key_exists('single_view_jwplayer',$this->settings) && $this->settings['single_view_jwplayer'] != 0){
			$pid = $this->settings['single_view_jwplayer'];
		} else {
			throw new Exception('No jwplayer single view configured');
		}
		return $pid;
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
	/**
	 * Method for displaying custom error flash messages, or to display no flash message at all on errors.
	 * 
	 * INFO:
	 *  - Flash-Messages (will be put in session of FE-user) in extbase 1.3.x only works together with TYPO3 4.5.x (because only
	 *    TYPO3 4.5.x supports FE-context for flashMessages)! This extension should work with TYPO3 4.3.x, so we must deactive this messages!
	 *
	 * @return boolean
	 */
	protected function getErrorFlashMessage() {
		return FALSE;
	}
}