<?php

$from=$_POST['from'];
$to=$_POST['to'];

$mask = 0;
if ( isset($_POST['ch']) )
	{
	$m = 1;
	for ( $i = 0 ; $i < $nchambres ; $i++ )
		{
		if ( isset($_POST['ch'][$i]) )
			$mask |= $m;
		$m = $m << 1;
		}
	}

if ( isset($_POST['bd_create']) )
{ // on vient de créer un event

// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_fermetures';   

$wpdb->insert( 
	$table_name, 
	array( 
		'nch' => $mask, 
		'ffrom' => $from ,
		'fto' => $to
		)
	);

$confirm_bd = true;
} // fin create event

if ( isset($_POST['bd_delete']) )
{ // on vient de detruire un event

if ( isset($_POST['id']) )
	$id = (int)$_POST['id'];
	
// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_fermetures';   

$wpdb->query(
	"
	DELETE FROM $table_name
	WHERE id = $id
	"
);
$confirm_bd = true;
} // fin delete event

if ( isset($_POST['bd_edit']) )
{ // on vient de modifier un event

if ( isset($_POST['id']) )
	$id = (int)$_POST['id'];
	
// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_fermetures';   

$wpdb->update( $table_name ,
		array( 
		'nch' => $mask, 
		'ffrom' => $from ,
		'fto' => $to
		),
		array(
		'id' => $id
		)
		);
$confirm_bd = true;

} // fin edit event

?>
