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
//$TCA['tt_content']['types']['list']['subtypes_addlist'][  'audiogallery_pi1'] = 'pi_flexform';
//t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_pi1.xml');

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

t3lib_extMgm::addLLrefForTCAdescr('tx_audiogallery_domain_model_filteritem', 'EXT:audio_gallery/Resources/Private/Language/locallang_csh_tx_audiogallery_domain_model_filteritem.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_audiogallery_domain_model_filteritem');
$TCA['tx_audiogallery_domain_model_filteritem'] = array(
	'ctrl' => array(
		'title'						=> 'LLL:EXT:audio_gallery/Resources/Private/Language/locallang_db.xml:tx_audiogallery_domain_model_filteritem',
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
		'dynamicConfigFile'			=> t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/FilterItem.php',
		'iconfile'					=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_audiogallery_domain_model_filteritem.gif'
	)
);

t3lib_extMgm::addLLrefForTCAdescr('tx_audiogallery_domain_model_filtergroup', 'EXT:audio_gallery/Resources/Private/Language/locallang_csh_tx_audiogallery_domain_model_filtergroup.xml');
t3lib_extMgm::allowTableOnStandardPages('tx_audiogallery_domain_model_filtergroup');
$TCA['tx_audiogallery_domain_model_filtergroup'] = array(
	'ctrl' => array(
		'title'						=> 'LLL:EXT:audio_gallery/Resources/Private/Language/locallang_db.xml:tx_audiogallery_domain_model_filtergroup',
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
		'dynamicConfigFile'			=> t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/FilterGroup.php',
		'iconfile'					=> t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_audiogallery_domain_model_filtergroup.gif'
	)
);

?>