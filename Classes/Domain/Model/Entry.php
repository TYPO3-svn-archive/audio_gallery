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
 * Entry for audio file
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_AudioGallery_Domain_Model_Entry extends Tx_Extbase_DomainObject_AbstractEntity {
	
	const UPLOAD_FOLDER = 'uploads/tx_audiogallery/';
	
	/**
	 * Title of audio record
	 * @var string
	 */
	protected $title;
	
	/**
	 * Author of audio record
	 * @var string
	 */
	protected $author;
	
	/**
	 * Path to audio file
	 * @var string
	 * @validate NotEmpty
	 */
	protected $audioFilePath;
	
	/**
	 * Path to preview image
	 * @var string
	 */
	protected $previewImagePath;
	
	/**
	 * filterItem
	 * @var Tx_Extbase_Persistence_ObjectStorage<Tx_AudioGallery_Domain_Model_FilterItem>
	 */
	protected $filterItem;
	
	/**
	 * Jwplayer config
	 * @var array
	 */
	protected $jwplayerConfig;
	
	/**
	 * Setter for title
	 *
	 * @param string $title Title of audio record
	 * @return void
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Getter for title
	 *
	 * @return string Title of audio record
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Setter for author
	 *
	 * @param string $author Author of audio record
	 * @return void
	 */
	public function setAuthor($author) {
		$this->author = $author;
	}

	/**
	 * Getter for author
	 *
	 * @return string Author of audio record
	 */
	public function getAuthor() {
		return $this->author;
	}
	
	/**
	 * Setter for audioFilePath
	 *
	 * @param string $audioFilePath Path to audio file
	 * @return void
	 */
	public function setAudioFilePath($audioFilePath) {
		$this->audioFilePath = $audioFilePath;
	}

	/**
	 * Getter for audioFilePath
	 *
	 * @return string Path to audio file
	 */
	public function getAudioFilePath() {
		return $this->audioFilePath;
	}
	
	/**
	 * Setter for previewImagePath
	 *
	 * @param string $previewImagePath Path to preview image
	 * @return void
	 */
	public function setPreviewImagePath($previewImagePath) {
		$this->previewImagePath = $previewImagePath;
	}

	/**
	 * Getter for previewImagePath
	 *
	 * @return string Path to preview image
	 */
	public function getPreviewImagePath() {
		return $this->previewImagePath;
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
	
	/**
	 * Setter for jwplayer config
	 *
	 * @param array $jwplayerConfig jwplayerConfig
	 * @return void
	 */
	public function setJwplayerConfig($jwplayerConfig) {
		$this->jwplayerConfig = $jwplayerConfig;
	}

	/**
	 * Getter for jwplayer config
	 *
	 * @return array
	 */
	public function getJwplayerConfig() {
		return $this->jwplayerConfig;
	}
	
	/**
	 * Returns source of audio file
	 *
	 * @return string Src to audio file
	 */
	public function getAudioFileSrc() {
		return self::UPLOAD_FOLDER . $this->audioFilePath;
	}
	
	/**
	 * Returns the absolute url of the audio file.
	 * 
	 * @return string
	 */
	public function getAudioFileUrl() {
		return t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->getAudioFileSrc();
	}
	
	/**
	 * Returns source of preview image
	 *
	 * @return string Src to preview image
	 */
	public function getPreviewImageSrc() {
		return self::UPLOAD_FOLDER . $this->previewImagePath;
	}
	
	/**
	 * Returns the external url of the preview image.
	 * 
	 * @return string
	 */
	public function getPreviewImageUrl() {
		return t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->getPreviewImageSrc();
	}
	
}
?>