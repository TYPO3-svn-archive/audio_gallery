<?php
########################################################################
# Extension Manager/Repository config file for ext: "audio_gallery"
#
# Auto generated by Extbase Kickstarter 2011-06-28
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Audio Gallery',
	'description' => 'Extension to display a gallery of audio files.',
	'category' => 'plugin',
	'author' => 'Max Beer',
	'author_email' => 'max.beer@aoemedia.de',
	'author_company' => 'AOE media GmbH',
	'shy' => '',
	'dependencies' => 'extbase,fluid,addthis,jwplayer',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'typo3' => '4.3.0',
			'php' => '5.2.0',
			'extbase' => '1.3.0',
			'fluid' => '1.3.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
);

?>