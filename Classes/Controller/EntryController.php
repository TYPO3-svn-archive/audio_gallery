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
	 * @var Tx_AudioGallery_Domain_Repository_EntryRepository
	 */
	protected $entryRepository;
	
	/**
	 * @var Tx_AudioGallery_Domain_Repository_FilterOneItemRepository
	 */
	protected $filterOneItemRepository;
	
	/**
	 * @var Tx_AudioGallery_Domain_Repository_FilterTwoItemRepository
	 */
	protected $filterTwoItemRepository;
	
	/**
	 * initialize action
	 */
	protected function initializeAction() {
		$this->entryRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_EntryRepository' );
		$this->filterOneItemRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_FilterOneItemRepository' );
		$this->filterTwoItemRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_FilterTwoItemRepository' );
		
		$file = t3lib_extMgm::siteRelPath ( 'jwplayer' ) . 'Resources/Public/Player/jwplayer.js';
		$GLOBALS ['TSFE']->getPageRenderer ()->addJsFile ( $file );
	}
	
	/**
	 * index action
	 *
	 * @return string The rendered list action
	 */
	public function indexAction() {
		if($this->useFilters()) {
			$filters = $this->getFilters();	
			$entries = $this->entryRepository->findAllFiltered($filters['filterOne']['selectedItem'], $filters['filterTwo']['selectedItem']);
		} else {
			$entries = $this->entryRepository->findAll();
		}
		
		$codeGenerator = $this->objectManager->get ('Tx_Addthis_CodeGenerator');

		$this->view->assign('jwplayer_config',$this->getJwplayerConfig());
		$this->view->assign('addthis_config',$codeGenerator->getConfigJs());
		$this->view->assign('addthis_jsurl',$codeGenerator->getJsImport());

		$this->view->assign ('filterOne', $filters['filterOne'] );
		$this->view->assign ('filterTwo', $filters['filterTwo'] );
		$this->view->assign ('entries', $entries );
	}
	
	/**
	 * show single entry
	 * @param Tx_AudioGallery_Domain_Model_Entry $entry
	 */
	public function showAction(Tx_AudioGallery_Domain_Model_Entry $entry) {
		$this->addOpenGraphMetaTags($entry);

		$this->view->assign ( 'entry', $entry );
		$codeGenerator = $this->objectManager->get ('Tx_Addthis_CodeGenerator');
		
		$this->view->assign('jwplayer_config',$this->getJwplayerConfig());
		$this->view->assign('addthis_config',$codeGenerator->getConfigJs());
		$this->view->assign('addthis_jsurl',$codeGenerator->getJsImport());
	}
	
	/**
	 * download audio file
	 * @param Tx_AudioGallery_Domain_Model_Entry $entry
	 */
	public function downloadAction(Tx_AudioGallery_Domain_Model_Entry $entry) {
		$filename = $entry->getTitle().'_'.$entry->getAuthor().'.mp3';
		$filename = str_replace(' ', '-', $filename);
		$binaryContent = file_get_contents($entry->getAudioFileUrl());
		
		ob_clean ();
		header ( 'Content-Type: audio/mpeg' );
		header ( 'Content-Disposition: attachment; filename='.$filename );
		header ( 'Content-Length: ' . strlen ( $binaryContent ) );
		header ( 'Cache-Control: private' );
		header ( 'Pragma: private' );
		echo $binaryContent;
		exit ();
	}
	
	private function addOpenGraphMetaTags(Tx_AudioGallery_Domain_Model_Entry $entry){
		$flashConfigGenerator	= $this->objectManager->get ('Tx_Jwplayer_FlashConfigGenerator');
		$jwPid					= $this->getjwPlayerRedirectPageId();
		
		/* generate the expected arguments for the jwplayer controller*/
		$arguments = array();
		$arguments['tx_jwplayer_pi1'] = array();
		$arguments['tx_jwplayer_pi1']['action'] = 'showVideo';
		$arguments['tx_jwplayer_pi1']['controller'] = 'Player';
		
		$settings['autostart']	= TRUE;
		$settings['audio']		= $entry->getAudioFileUrl();
		
		$arguments['tx_jwplayer_pi1']['flash_player_config'] = $flashConfigGenerator->encode($settings, $entry->getPreviewImageUrl());
		$videourl = $this->uriBuilder->setTargetPageUid($jwPid)->setArguments($arguments)->setCreateAbsoluteUri(TRUE)->buildFrontendUri();
		$title = $this->getFacebookTitle();
		$image = $entry->getPreviewImageUrl();
		
		$argumentsSingleView = array();
		$argumentsSingleView['tx_audiogallery_pi1'] = array();
		$argumentsSingleView['tx_audiogallery_pi1']['action'] = 'show';
		$argumentsSingleView['tx_audiogallery_pi1']['controller'] = 'Entry';	
		$argumentsSingleView['tx_audiogallery_pi1']['entry'] = $entry->getUid();
		$singleViewPid = $this->getSingleViewPageId();	
		$singleViewUrl = $this->uriBuilder->setTargetPageUid($singleViewPid)->setArguments($argumentsSingleView)->setCreateAbsoluteUri(TRUE)->buildFrontendUri(); 

		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta name="medium" content="video"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:type" content="video"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:url" content="'.$singleViewUrl.'"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:site_name" content="andywillwechseln.de"/>' );
		
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:title" content="'.$title.'"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:image" content="'.$image.'" />');
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video" content="'.$videourl.'"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:type" content="application/x-shockwave-flash"/>' );
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:width" content="123">');
		$GLOBALS['TSFE']->getPageRenderer()->addMetaTag( '<meta property="og:video:height" content="80">');
	}
	
	/**
	 * Returns the configured facebookTitle of the plugin.
	 * 
	 * @return string
	 */
	protected function getFacebookTitle() {
		$facebookTitle = '';

		if(array_key_exists('facebookTitle',$this->settings) && $this->settings['facebookTitle'] != ''){
			$facebookTitle = $this->settings['facebookTitle'];
		}
		
		return $facebookTitle;
	}
	
	/**
	 * Returns array with filter data
	 * @return array $selectedFilterItems
	 */
	protected function getFilters() {
		$filters = array();
		
		$filters['filterOne'] = array();
		$filters['filterOne']['items'] = $this->filterOneItemRepository->findAll();
		if ($this->request->hasArgument ( 'filterOneItem' )) {
			$filterOneSelectedItemUid =  intval($this->request->getArgument ( 'filterOneItem' ));
			$filters['filterOne']['selectedItem'] = $this->filterOneItemRepository->findByUid($filterOneSelectedItemUid);
		}
		
		$filters['filterTwo'] = array();
		$filters['filterTwo']['items'] = $this->filterTwoItemRepository->findAll();
		if ($this->request->hasArgument ( 'filterTwoItem' )) {
			$filterTwoSelectedItemUid =  intval($this->request->getArgument ( 'filterTwoItem' ));
			$filters['filterTwo']['selectedItem'] = $this->filterTwoItemRepository->findByUid($filterTwoSelectedItemUid);
		}

		return $filters;
	}

	/**
	 * Read the configured pageid where the jwplayer plugin for facebook redirects is installed.
	 * 
	 * @return int
	 */
	protected function getjwPlayerRedirectPageId() {
		if(array_key_exists('jwPlayerRedirect',$this->settings) && $this->settings['jwPlayerRedirect'] != 0){
			$pid = $this->settings['jwPlayerRedirect'];
		} 
		
		return $pid;
	}
	
	/**
	 * 
	 * @throws Exception
	 */
	public function getSingleViewPageId() {
		if(array_key_exists('singleView',$this->settings) && $this->settings['singleView'] != 0){
			$pid = $this->settings['singleView'];
		} 
		return $pid;		
	}
	
	/**
	 * Returns cofnig for jwplayer
	 * @return array $config
	 */
	protected function getJwplayerConfig() {
		$settings = array();
		$settings['volume'] = intval($this->settings['jwplayer']['volume']);
		$settings['height'] = intval($this->settings['jwplayer']['height']);
		$settings['width'] = intval($this->settings['jwplayer']['width']);
		$settings['skin'] = $this->settings['jwplayer']['skin'];
		$settings['flashplayer'] = $this->settings['jwplayer']['flashplayer'];
		$settings['backcolor'] = $this->settings['jwplayer']['backcolor'];
		$settings['fontcolor'] = $this->settings['jwplayer']['fontcolor'];
		$settings['lightcolor'] = $this->settings['jwplayer']['lightcolor'];
		$settings['screencolor'] = $this->settings['jwplayer']['screenscolor'];
		$settings['bufferlength'] = intval($this->settings['jwplayer']['bufferlength']);
		$settings['autostart'] = $this->settings['jwplayer']['autostart'];
		$settings['mute'] = $this->settings['jwplayer']['mute'];
		$settings['stretching'] = $this->settings['jwplayer']['stretching'];
		$settings['repeat'] = $this->settings['jwplayer']['repeat'];
		$settings['controlbar'] = $this->settings['jwplayer']['controlbar'];
		
		$config = new Tx_Jwplayer_Config();
		$config->setSettings($settings);
		
		return $config->getJsConfig();
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
	/**
	 * @return boolean
	 */
	protected function useFilters() {
		return (boolean) $this->settings['filters_display'];
	}
}