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
	 * Path to image for single view.
	 * @var string
	 */
	protected $singleViewImagePath;
	
	/**
	 * filterOneItem
	 * @var Tx_AudioGallery_Domain_Model_FilterOneItem
	 */
	protected $filterOneItem;
	
	/**
	 * filterTwoItem
	 * @var Tx_AudioGallery_Domain_Model_FilterTwoItem
	 */
	protected $filterTwoItem;
	
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
	 * Returns the escaped title for the openGraph metatags
	 * @return string
	 */
	public function getMetaTitle() {
		$title = str_replace('"','',$this->getTitle());
		$title = str_replace("'",'',$title);
		$title = htmlspecialchars($title);
		
		return $title;
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
	
	/**
	 * This method is used to return an image for the single view.
	 * 
	 * @return string
	 */
	public function getSingleViewImageSrc() {
		$image = $this->singleViewImagePath;
		
		if($image == '') {
			$image = $this->previewImagePath;
		}
		
		return self::UPLOAD_FOLDER .$image;
	}
	
	/**
	 * This method is sued to return the url to the single view image.
	 * 
	 * @return string
	 */
	public function getSingleViewImageUrl() {
		return t3lib_div::getIndpEnv('TYPO3_SITE_URL').$this->getSingleViewImageSrc();
	}
	
	/**
	 * Setter for filterOneItem
	 *
	 * @param Tx_AudioGallery_Domain_Model_FilterOneItem $filterOneItem filterOneItem
	 * @return void
	 */
	public function setFilterOneItem(Tx_AudioGallery_Domain_Model_FilterOneItem $filterOneItem) {
		$this->filterOneItem = $filterOneItem;
	}

	/**
	 * Getter for filterOneItem
	 *
	 * @return Tx_AudioGallery_Domain_Model_FilterOneItem filterOneItem
	 */
	public function getFilterOneItem() {
		return $this->filterOneItem;
	}
	
	/**
	 * Setter for filterTwoItem
	 *
	 * @param Tx_AudioGallery_Domain_Model_FilterTwoItem $filterTwoItem filterTwoItem
	 * @return void
	 */
	public function setFilterTwoItem(Tx_AudioGallery_Domain_Model_FilterTwoItem $filterTwoItem) {
		$this->filterTwoItem = $filterTwoItem;
	}

	/**
	 * Getter for filterTwoItem
	 *
	 * @return Tx_AudioGallery_Domain_Model_FilterTwoItem filterTwoItem
	 */
	public function getFilterTwoItem() {
		return $this->filterTwoItem;
	}
	
}
?>