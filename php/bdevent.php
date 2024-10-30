<?php

if ( isset($_POST['bd_create']) )
{ // on vient de créer un event

if ( isset($_POST['ich']) )
	$ich = (int)$_POST['ich'];
	
$mask = 1 << $ich;
	
if ( isset($_POST['jour']) )
	$arrivee = $_POST['jour'];

if ( isset($_POST['njours']) )
	$njours = (int)$_POST['njours'];
	
if ( isset($_POST['infos']) )
	$infos = $_POST['infos'];
	
// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_calendrier';   

$wpdb->insert( 
	$table_name, 
	array( 
		'jour' => $arrivee, 
		'njours' => $njours ,
		'nch' => $mask,
		'infos' => $infos
		)
	);

$confirm_bd = true;

} // fin create event

if ( isset($_POST['bd_delete']) )
{ // on vient de detruire un event

if ( isset($_POST['bdid']) )
	$bdid = (int)$_POST['bdid'];
	
// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_calendrier';   

$wpdb->query(
	"
	DELETE FROM $table_name
	WHERE id = $bdid
	"
);
$confirm_bd = true;

} // fin delete event

if ( isset($_POST['bd_edit']) )
{ // on vient de modifier un event

if ( isset($_POST['bdid']) )
	$bdid = (int)$_POST['bdid'];

if ( isset($_POST['njours']) )
	$njours = (int)$_POST['njours'];
	
if ( isset($_POST['infos']) )
	$infos = $_POST['infos'];
	
// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_calendrier';   

$wpdb->update( $table_name ,
		array( 
		'njours' => $njours ,
		'infos' => $infos
		),
		array(
		'id' => $bdid
		)
		);
$confirm_bd = true;

} // fin edit event

?>
