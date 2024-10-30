<?php

include("messages.php");
include("fonctions.php");

// paramètres du calendrier

include("getoptions.php");

$confirm_bd = false;

// on va faire d'éventuelles modifs en BD
include("bdeventf.php");

$html .= '
<script type="text/javascript">';
$html .= '
jQuery(document).ready(function(){';

if ( $confirm_bd )
{
$html .= 'jQuery("#confirm_form").submit();
});
</script>';
}
else
{
$html .= '
jQuery.datepicker.setDefaults( jQuery.datepicker.regional[ "" ] );
jQuery( ".date" ).datepicker( jQuery.datepicker.regional[ "fr" ] );';

/* $html .= '
jQuery( ".date" ).datepicker(); */
$html .= '
jQuery( ".date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
setDates();
});
</script>';
}

$html .= '
<div class=wrap>  
<div class="icon32" id="icon-edit"><br /></div>  
<h2>'.$gferm.'</h2>';

if ( $confirm_bd )
{
$furl = admin_url('admin.php');
$html .= '<form name="confirm_form" id="confirm_form" class="wrap" method="get" action="'.$furl.'" >';
$html .= '<input name="page" type="hidden" value="chotcal_3" />';
$html .= '</form>';
}
else
{
global $wpdb;  

$furl = admin_url('admin.php?page=chotcal_3');

$table_name = $wpdb->prefix.'chotcal_fermetures';  
$sql = $wpdb->prepare("SELECT * FROM $table_name");  
$result = $wpdb->get_results($sql);  
for ( $i = 0 ; $i < count($result); $i++ ) {
	$html .= '
	<div class="postbox fermdiv">
	<form action="'.$furl.'" method="post" >';
	$html .= '<input name="page" type="hidden" value="chotcal_3" />';
	$html .= '<input name="id" type="hidden" value="'.$result[$i]->id.'" />';
	$html .= '<label for="from">'.$tfrom.'</label> : <input type="text" id="dt1'.$i.'" name="from" class="date" value=""/>';
	$html .= '<label for="to">'.$tto.'</label> : <input type="text" id="dt2'.$i.'" name="to" class="date" value=""/>';
	
	$mask = $result[$i]->nch;
	$m = 1;
	for ( $j = 0 ; $j < $nchambres; $j++ )
		{
		$name = 'ch['.$j.']';
		$html .= '<label for="'.$name.'"><input name="'.$name.'" type="checkbox" ';
		if ( $mask & $m ) $html .= ' checked="checked"';
		$html .= '/>'.$chambres_initiales[$j].'</label>';
		$m = $m << 1;
		}
		
	
	$html .= '<input class="button-primary bdbut" type="submit" name="bd_edit" id="b_edit" value="'.$t_modif.'" />';
	$html .= '<input class="button-primary bdbut" type="submit" name="bd_delete" id="b_delete" value="'.$t_delete.'" />';
	$html .= '</form></div>';
}
// le seup des dates en js

$nd  = count($result)*2;
$html .= '
<script type="text/javascript">
function setDates()

{
var nd = '.$nd.';';
for ( $i = 0 ; $i < count($result); $i++ ) {
$html .= '
jQuery("#dt1'.$i.'").datepicker( "setDate", "'.$result[$i]->ffrom.'" );
jQuery("#dt2'.$i.'").datepicker( "setDate", "'.$result[$i]->fto.'" );';
}

$html .= '} </script>';

// le créer

$html .= '
<div class="postbox fermdiv">
<form action="'.$furl.'" method="post" >';
$html .= '<input name="page" type="hidden" value="chotcal_3" />';
$html .= '<label for="from">'.$tfrom.'</label> : <input type="text" name="from" class="date" value=""/>';
$html .= '<label for="from">'.$tto.'</label> : <input type="text" name="to" class="date" value=""/>';

$m = 1;
for ( $j = 0 ; $j < $nchambres; $j++ )
	{
	$name = 'ch['.$j.']';
	$html .= '<label for="'.$name.'"><input name="'.$name.'" type="checkbox" ';
	$html .= '/>'.$chambres_initiales[$j].'</label>';
	$m = $m << 1;
	}
$html .= '<input class="button-primary bdbut" type="submit" name="bd_create" id="b_create" value="'.$t_create.'" />';
$html .= '</form></div>';
}
$html .= '</div>';
?>