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
	 * @var Tx_AudioGallery_Domain_Repository_FilterItemRepository
	 */
	protected $filterItemRepository;
	
	/**
	 * @var Tx_AudioGallery_Domain_Repository_EntryRepository
	 */
	protected $entryRepository;
	
	/**
	 * initialize action
	 */
	protected function initializeAction() {
		$this->filterGroupRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_FilterGroupRepository' );
		$this->filterItemRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_FilterItemRepository' );
		$this->entryRepository = $this->objectManager->get ( 'Tx_AudioGallery_Domain_Repository_EntryRepository' );
	}
	
	/**
	 * index action
	 *
	 * @return string The rendered list action
	 */
	public function indexAction() {
		$filterGroups = $this->filterGroupRepository->findAll();
		$selectedFilterItems = $this->getSelectedFilterItems();
		$filterGroups = $this->addSelectedFilterItems($filterGroups, $selectedFilterItems);

		$entries = $this->entryRepository->findAllFiltered($selectedFilterItems);
		
		$codeGenerator = $this->objectManager->get ('Tx_Addthis_CodeGenerator');

		$this->view->assign('jwplayer_config',$this->getJwplayerConfig());
		$this->view->assign('addthis_config',$codeGenerator->getConfigJs());
		$this->view->assign('addthis_jsurl',$codeGenerator->getJsImport());
		$this->view->assign ('filterGroups', $filterGroups );
		$this->view->assign ('selectedFilterItems', $this->getUidList($selectedFilterItems) );
		$this->view->assign ('entries', $entries );
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
	 * Returns active filter items
	 * @return array $selectedFilterItems
	 */
	protected function getSelectedFilterItems() {
	
		$selectedFilterItems = array();
		if ($this->request->hasArgument ( 'filterGroup' ) && $this->request->hasArgument ( 'selectedFilterItems' )) {
			$filterGroupUid = $this->request->getArgument ( 'filterGroup' );
			$filterGroup = $this->filterGroupRepository->findByUid($filterGroupUid);
			
			$selectedFilterItemsUids = $this->request->getArgument ( 'selectedFilterItems' );
			
			//Remove filters which aren't selected anymore
			if (strlen($selectedFilterItemsUids) !== 0) {
				$selectedFilterItemsUids = explode(',', $selectedFilterItemsUids);
				foreach ($selectedFilterItemsUids as $selectedFilterItemsUid) {
					$selectedFilterItems[] = $this->filterItemRepository->findByUid($selectedFilterItemsUid);
				}
	
				foreach ($selectedFilterItems as $key => $selectedFilterItem) {
					if ($filterGroup->getFilterItem()->contains($selectedFilterItem)) {
						unset($selectedFilterItems[$key]);
					}
				}
			}
			
			if ($this->request->hasArgument ( 'filterItem' )) {
				$filterItemUid = $this->request->getArgument ( 'filterItem' );
				$filterItem = $this->filterItemRepository->findByUid($filterItemUid);
				$selectedFilterItems[] = $filterItem;
			}
			
		}

		return $selectedFilterItems;
	}
	/**
	 * Add selected filter items
	 * @param array $filterGroups
	 * @param array $selectedFilterItems
	 * @return array $filterGroups
	 */
	protected function addSelectedFilterItems($filterGroups, $selectedFilterItems) {

		foreach ($filterGroups as $filterGroup) {
			foreach ($selectedFilterItems as $selectedFilterItem) {
				if ($filterGroup->getFilterItem()->contains($selectedFilterItem)) {
					$filterGroup->setSelectedFilterItem($selectedFilterItem);
				}
			}
		}
		

		return $filterGroups;
	}
	/**
	 * Returns comma seperated list with uids
	 * @param array $array array of objects
	 * @return string $list comma seperated list
	 */
	protected function getUidList($array) {
		
		$listArray = '';
		foreach($array as $item) {
			$listArray[] = $item->getUid();
		}
		$list = implode(",", $listArray);
		
		return $list;
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
}