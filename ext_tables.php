<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'Audio Gallery'
);
$TCA['tt_content']['types']['list']['subtypes_excludelist'][  'audiogallery_pi1'] = 'layout,recursive,select_key';
$TCA['tt_content']['types']['list']['subtypes_addlist']['audiogallery_pi1'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue( 'audiogallery_pi1', 'FILE:EXT:audio_gallery/Configuration/FlexForms/AudioGallery.xml');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Audio Gallery');

t3lib_extMgm::addLLrefForTCAdescr('tx_audiogallery_domain_model_entry', 'EXT:audio_gallery/Resources/Private/Language/locallang_csh_tx_audiogallery_domain_model_entry.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_audiogallery_domain_model_entry');
$TCA['tx_audiogallery_domain_model_entry'] = array(
	'ctrl' => array(
		'title'						=> 'LLL:EXT:audio_gallery/Resources/Private/Language/locallang_db.xml:tx_audiogallery_domain_model_entry',
		'label'						=> 'title',
		'tstamp'					=> 'tstamp',
		'crdate'					=> 'crdate',
		'versioningWS'				=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid'					=> 't3_origuid',
		'languageField'				=> 'sys_language_uid',
		'transOrigPointerField'		=> 'l18n_parent',
		'transOrigDiffSourceField'	=> 'l18n_diffsource',
		'delete'					=> 'deleted',
		'enablecolumns'				=> array(
			'disabled'		=> 'hidden'
		),
		'dynamicConfigFile'			=> t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Entry.php',
		'iconfile'					=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_audiogallery_domain_model_entry.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_audiogallery_domain_model_filteroneitem', 'EXT:audio_gallery/Resources/Private/Language/locallang_csh_tx_audiogallery_domain_model_filteroneitem.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_audiogallery_domain_model_filteroneitem');
$TCA['tx_audiogallery_domain_model_filteroneitem'] = array(
	'ctrl' => array(
		'title'						=> 'LLL:EXT:audio_gallery/Resources/Private/Language/locallang_db.xml:tx_audiogallery_domain_model_filteroneitem',
		'label'						=> 'name',
		'tstamp'					=> 'tstamp',
		'crdate'					=> 'crdate',
		'versioningWS'				=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid'					=> 't3_origuid',
		'languageField'				=> 'sys_language_uid',
		'transOrigPointerField'		=> 'l18n_parent',
		'transOrigDiffSourceField'	=> 'l18n_diffsource',
		'delete'					=> 'deleted',
		'enablecolumns'				=> array(
			'disabled'		=> 'hidden'
		),
		'dynamicConfigFile'			=> t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/FilterOneItem.php',
		'iconfile'					=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_audiogallery_domain_model_filteroneitem.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_audiogallery_domain_model_filtertwoitem', 'EXT:audio_gallery/Resources/Private/Language/locallang_csh_tx_audiogallery_domain_model_filtertwoitem.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_audiogallery_domain_model_filtertwoitem');
$TCA['tx_audiogallery_domain_model_filtertwoitem'] = array(
	'ctrl' => array(
		'title'						=> 'LLL:EXT:audio_gallery/Resources/Private/Language/locallang_db.xml:tx_audiogallery_domain_model_filtertwoitem',
		'label'						=> 'name',
		'tstamp'					=> 'tstamp',
		'crdate'					=> 'crdate',
		'versioningWS'				=> 2,
		'versioning_followPages'	=> TRUE,
		'origUid'					=> 't3_origuid',
		'languageField'				=> 'sys_language_uid',
		'transOrigPointerField'		=> 'l18n_parent',
		'transOrigDiffSourceField'	=> 'l18n_diffsource',
		'delete'					=> 'deleted',
		'enablecolumns'				=> array(
			'disabled'		=> 'hidden'
		),
		'dynamicConfigFile'			=> t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/FilterTwoItem.php',
		'iconfile'					=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_audiogallery_domain_model_filtertwoitem.gif'
	)
);

?>