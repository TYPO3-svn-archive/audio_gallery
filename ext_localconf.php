<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Pi1',
	array(
		'Entry' => 'index, show, new, create, edit, update, delete',
	),
	array(
		'Entry' => 'create, update, delete',
	)
);

?>