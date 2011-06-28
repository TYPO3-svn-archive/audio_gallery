<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_audiogallery2_domain_model_entry'] = array(
	'ctrl' => $TCA['tx_audiogallery2_domain_model_entry']['ctrl'],
	'interface' => array(
		'showRecordFieldList'	=> 'title,author,audio_file_path,preview_image_path,category'
	),
	'types' => array(
		'1' => array('showitem'	=> 'title,author,audio_file_path,preview_image_path,category')
	),
	'palettes' => array(
		'1' => array('showitem'	=> '')
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude'			=> 1,
			'label'				=> 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config'			=> array(
				'type'					=> 'select',
				'foreign_table'			=> 'sys_language',
				'foreign_table_where'	=> 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value', 0)
				)
			)
		),
		'l18n_parent' => array(
			'displayCond'	=> 'FIELD:sys_language_uid:>:0',
			'exclude'		=> 1,
			'label'			=> 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config'		=> array(
				'type'			=> 'select',
				'items'			=> array(
					array('', 0),
				),
				'foreign_table' => 'tx_audiogallery2_domain_model_entry',
				'foreign_table_where' => 'AND tx_audiogallery2_domain_model_entry.uid=###REC_FIELD_l18n_parent### AND tx_audiogallery2_domain_model_entry.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'		=>array(
				'type'		=>'passthrough'
			)
		),
		't3ver_label' => array(
			'displayCond'	=> 'FIELD:t3ver_label:REQ:true',
			'label'			=> 'LLL:EXT:lang/locallang_general.php:LGL.versionLabel',
			'config'		=> array(
				'type'		=>'none',
				'cols'		=> 27
			)
		),
		'hidden' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config'	=> array(
				'type'	=> 'check'
			)
		),
		'title' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:audio_gallery2/Resources/Private/Language/locallang_db.xml:tx_audiogallery2_domain_model_entry.title',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			)
		),
		'author' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:audio_gallery2/Resources/Private/Language/locallang_db.xml:tx_audiogallery2_domain_model_entry.author',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			)
		),
		'audio_file_path' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:audio_gallery2/Resources/Private/Language/locallang_db.xml:tx_audiogallery2_domain_model_entry.audio_file_path',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			)
		),
		'preview_image_path' => array(
			'exclude'	=> 1,
			'label'		=> 'LLL:EXT:audio_gallery2/Resources/Private/Language/locallang_db.xml:tx_audiogallery2_domain_model_entry.preview_image_path',
			'config'	=> array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			)
		),
		'category' => array(
			'exclude'	=> 0,
			'label'		=> 'LLL:EXT:audio_gallery2/Resources/Private/Language/locallang_db.xml:tx_audiogallery2_domain_model_entry.category',
			'config'	=> array(
				'type' => 'inline',
				'foreign_table' => 'tx_audiogallery2_domain_model_category',
				'minitems' => 0,
				'maxitems' => 1,
				'appearance' => array(
					'collapse' => 0,
					'newRecordLinkPosition' => 'bottom',
				),
			)
		),
	),
);
?>