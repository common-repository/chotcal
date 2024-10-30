<?php

include("messages.php");

$req_url0 = $_SERVER['REQUEST_URI']; // l'url appelée

$confirm_bd = false;

if ( $chotcal_context == "manage" ) {	// manage
	// on va faire d'éventuelles modifs en BD
	include("bdevent.php");
}

// recuperation du jour, mois, et année actuel
$jour_actuel = date("j", time());
$mois_actuel = date("m", time());
$an_actuel = date("Y", time());
$jour = $jour_actuel;

$can_edit = false;
 
global $current_user;
if ( is_user_logged_in() )
	{
	$user_info = get_currentuserinfo();
	if ( $current_user->user_level >= 10 && $chotcal_context == "manage" ) 
		$can_edit = true;
	}

include("fonctions.php");

if ( $chotcal_context == "manage" ) {	// manage
$html .= '
<script type="text/javascript">';

$html .= 'jQuery(document).ready(function(){';

if ( $confirm_bd )
{
$html .= 'jQuery("#confirm_form").submit();
});
</script>';
}
else
{
$html .= '
    jQuery("#b_create").hide();
    jQuery("#b_edit").hide();
    jQuery("#b_delete").hide();
    
    jQuery( "#slider" ).slider({
			range: "max",
			min: 1,
			max: 10,
			value: 1,
			slide: function( event, ui ) {
				jQuery( "#njours" ).val( ui.value );
				}
			});
	jQuery( "#njours" ).val( jQuery( "#slider" ).slider( "value" ) );
    
});';

$html .= 'function setSlider(mr)
{';
$html .= '
jQuery( "#slider" ).slider({
			range: "max",
			min: 1,
			max: mr,
			value: 1,
			slide: function( event, ui ) {
				jQuery( "#njours" ).val( ui.value );
				}
			});';
$html .= '
}';

$html .= 'function setEvent(ich,chambre,date,njours,infos,bc,be,bd,bdid)
{';

$html .= '
jQuery("#ich").val(ich);';
$html .= '
jQuery("#chambre").val(chambre);';
$html .= '
jQuery("#jour").val(date);';
$html .= '
jQuery("#njours").val(njours);
jQuery("#slider" ).slider( "value",njours );';
$html .= '
jQuery("#infos").val(infos);';
$html .= '
if ( bc ) jQuery("#b_create").show(); else jQuery("#b_create").hide();';
$html .= '
if ( be ) jQuery("#b_edit").show(); else jQuery("#b_edit").hide();';
$html .= '
if ( bd ) jQuery("#b_delete").show(); else jQuery("#b_delete").hide();';
$html .= '
jQuery("#bdid").val(bdid);';
$html .= '
}';
$html .= '</script>';
}
}

// si la variable mois n'existe pas, mois et année correspondent au mois et à l'année courante

if (isset($_GET['mois']))
 { $mois=$_GET['mois']; $an=$_GET['an']; }
else 
	{
	$mois = $mois_actuel;
	$an = $an_actuel;
	}

//definir le mois suivant 
$mois_suivant = $mois + 1;
$an_suivant = $an;
if ($mois_suivant == 13)
{
	$mois_suivant = 1;
	$an_suivant = $an + 1;
}

//defini le mois précédent
$mois_prec = $mois - 1;
$an_prec = $an;
if ($mois_prec == 0)
{
	$mois_prec = 12;
	$an_prec = $an - 1;
}


//affichage du mois et de l'année 

$mois_en_clair = $mois_de_annee[$mois - 1];
$moisan = $mois_en_clair." ".$an;

// paramètres du calendrier

include("getoptions.php");

// connexion à la bdd pour remplir $tab_jours[] et $tab_nch[]

include("fillmonth.php");

// l'url sans &mois et &an

$req_url = $req_url0;
$mk1 = strpos($req_url,"mois=");
$mk2 = strpos($req_url,"an=");
if ( $mk1 !== FALSE )
	{
	$req_url = substr($req_url,0,$mk1-1); // amputée de mois et année
	}
	
if ( strpos($req_url,"?") !== FALSE )
	$sep1 = "&";
else
	$sep1 = "?";
	
// la légende

if ( $chotcal_context == "page" ) {
$html .= '<div class="cal-legend">
<fieldset class="flegend"><legend>'.$legend.'</legend>';

for ( $j = 0 ; $j < $nchambres ; $j++ )
	{
	$html .= '<div style="background-color:'.$chambres_colors[$j].';" class="cell-legend">';
	$html .= $chambres_initiales[$j].'</div> : '.$ch.' '.$chambres_names[$j].'<br/>';
	}

$html .= '<img class="img-legend" src="'.plugins_url( "/img/libre.png", dirname(__FILE__) ).'" width="20" height="20" align="middle" /> : '.$libre.'<br/>
<img class="img-legend" src="'.plugins_url( "/img/occupe.png", dirname(__FILE__) ).'" width="20" height="20" align="middle" /> : '.$occupe.'<br/>
<img class="img-legend" src="'.plugins_url( "/img/cadenas.png", dirname(__FILE__) ).'" width="20" height="20" align="middle" /> : '.$fermeture.'

</fieldset>
</div>';

}  // fin legende


//  le calendrier
 
$html .= '
<div id="cal-main-'.$chotcal_context.'">
<table cellpadding="0" cellspacing="0" class="tab_cal">
<tr class="tab-titre">
<td class="noborder" colspan="8">';
//	lien vers avant
$html .= '
<a href="'.$req_url.$sep1.'mois='.$mois_prec.'&an='.$an_prec.'" rel="nofollow">';
$html .= '
<img id="month-prev" src="'.plugins_url( "/img/prec.png", dirname(__FILE__) ).'" title="'.$titmm1.'" border="0" />
</a>';
// lien vers après
$html .= '
<a href="'.$req_url.$sep1.'mois='.$mois_suivant.'&an='.$an_suivant.'" rel="nofollow">';
$html .= '
<img id="month-next" src="'.plugins_url( "/img/suiv.png", dirname(__FILE__) ).'" title="'.$titmp1.'" border="0" />
</a>';
// mois en cours
$html .= '
<p class="mois-en-cours">'.$mois_en_clair.' '.$an.'</p>
';
$html .= '<hr class="cal-hr"/>';
// légende lien avant
$html .= '<p class="month-prev">'.$mois_de_annee[$mois_prec-1].' '.$an_prec.'</p>';
// légende lien après
$html .= '<p class="month-next">'.$mois_de_annee[$mois_suivant-1].' '.$an_suivant.'</p>';



$html .= '</td>
</tr>

<tr align="center" class="jours">
<td class="noborder" width="30" id="idCalCh">'.$cha.'</td>
		<td class="noborder" width="45" id="idCalLu">'.$lundi.'</td>
		<td class="noborder" width="45" id="idCalMa">'.$mardi.'</td>
		<td class="noborder" width="45" id="idCalMe">'.$mercredi.'</td>
		<td class="noborder" width="45" id="idCalJe">'.$jeudi.'</td>
		<td class="noborder" width="45" id="idCalVe">'.$vendredi.'</td>
		<td class="noborder" width="45" id="idCalSa">'.$samedi.'</td>
		<td class="noborder" width="45" id="idCalDi">'.$dimanche.'</td>
</tr>';



function is_passe($jour,$mois,$an,$jour_actuel,$mois_actuel,$an_actuel)

{
if ( (int)$an < (int)$an_actuel ) return true;
if ( (int)$an > (int)$an_actuel ) return false;
if ( (int)$mois < (int)$mois_actuel ) return true;
if ( (int)$mois > (int)$mois_actuel ) return false;
if ( (int)$jour < (int)$jour_actuel ) return true;
return false;
}

$nfree = 0;
$noccu = 0;

//Détection du 1er et dernier jour du moiS
$nombre_date = mktime(0,0,0, $mois, 1, $an);
$premier_jour = date('w', $nombre_date);
if ( $premier_jour  == 0 ) $premier_jour = 7;
$dernier_jour = 28;
while (checkdate($mois, $dernier_jour + 1, $an))
	{ $dernier_jour++;}

// Affichage de 7 jours du calendrier

$nr= (int)(($premier_jour+$dernier_jour)/7);
$r = 0;
while ( true )
	{
	$html .= '<tr>';
	$html .= '<td width="30" class="tjour-first">';

	$html .= '<div class="head"></div>';
	for ( $j = 0 ; $j < $nchambres ; $j++ )
		{
		$html .= '<div style="background-color:'.$chambres_colors[$j].';" class="cell-header';
		if ( $j == 0 )
		  	$html .= ' cell-middle';
		else if ( $j < $nchambres-1 )
			$html .= ' cell-last';
		$html .= '">'.$chambres_initiales[$j].'</div>';
		}
	
    $html .= '</td>';
    
	for ($i = 1; $i < 8; $i++)
		{
		$ce_jour = $r * 7 + $i+1 - $premier_jour;
		if ( $ce_jour <= 0 || $ce_jour > $dernier_jour )
			{ 
			$html .= '<td width="30" class="out"></td>';
			}
		else
			{		
				
			$html .= '<td width="45" class="tjour">'; 
			
			$html .= '<div ';
			if ( ( $fete = getPublicHoliday((int)$ce_jour,(int)$mois,$an) ) != false )
				{ 
				$html .= 'class="headferie tooltip">';
				$html .= '<span>'.$fete.'</span>';
				}
			else if ( $i == 7 ) 
				{
				$html .= 'class="headdimanche">'; // dimanche simple
				}
			else 
				$html .= 'class="head">';
			$html .= $ce_jour.'</div>';

			for ( $j = 0 ; $j < $nchambres ; $j++ )
				{
				$html .= '<div class="cell-main ';
				
				$html .= $tab_interv[$j][$ce_jour];
				$html .= ' ';
				
				if ( $j == 0 )
		  			$html .= ' cell-middle';
				else if ( $j < $nchambres-1 )
					$html .= ' cell-last';
				$html .= '">';
					
				if ( is_passe($ce_jour,$mois,$an,$jour_actuel,$mois_actuel,$an_actuel) )
					$imgclass = "img-cal-passe";
				else
					$imgclass = "img-cal";
					
				if ( $tab_vacances[$j][$ce_jour] )	
					$html .= '<img class="'.$imgclass.'" src="'.plugins_url( "/img/cadenas.png", dirname(__FILE__) ).'" />'; 
				else
					{
					if ( $tab_reserv[$j][$ce_jour] )
						{
						$noccu++;
						if ( $imgclass == "img-cal" && $can_edit == true && $tab_bdid[$j][$ce_jour] != -1  ) 
							{
							$jour = ajout_zero($ce_jour, $mois, $an);
							$html .= '<a class="tooltip" ';
							$html .= '
							onclick="setEvent('.$j.',\''.$chambres_names[$j].'\',\''.$jour.'\','.$tab_njours[$j][$ce_jour].',\''.$tab_infos[$j][$ce_jour].'\',0,1,1,'.$tab_bdid[$j][$ce_jour].'); return false;" >
							';
							}
						$html .= '<img class="'.$imgclass.'" src="'.plugins_url( "/img/occupe.png", dirname(__FILE__) ).'" />'; 
						if ( $imgclass == "img-cal" && $can_edit == true && $tab_infos[$j][$ce_jour] )
							{
							$title = $tab_infos[$j][$ce_jour]; 
							$html .= ' />';
							$html .= '<span>'.$title.'</span>'; // tooltip en CSS
							$html .= '</a>';
							} 
						}
					else
						{
						$nfree++;
						
						$timestamp = mktime(8, 0, 0, $mois, $ce_jour, $an); 
						$strdate = strftime($tformat,$timestamp);
						
						if ( $imgclass == "img-cal" && $can_edit == true ) 
							{
							$jour = ajout_zero($ce_jour, $mois, $an);
							$html .= '<a class="tooltip" ';
							$html .= '
							onclick="setEvent('.$j.',\''.$chambres_names[$j].'\',\''.$jour.'\',1,\'\',1,0,0,-1); 
							setSlider('.$tab_maxstay[$j][$ce_jour].'); return false;" >
							';
							}
							
						$html .= '<img class="'.$imgclass.'" src="'.plugins_url( "/img/libre.png", dirname(__FILE__) ).'"';
						
						if ( $imgclass == "img-cal" && $can_edit == true) 
							{
							$title = $res1.' '.$chambres_names[$j].' '.$res2.' '.$strdate; 
							$html .= ' />';
							$html .= '<span>'.$title.'</span>'; // tooltip en CSS
							$html .= '</a>';
							}	
						else
							$html .= ' />';
						}
					}
				$html .= '</div>';
				}	
				
			$html .= '</td>';	
			}
		}
	$html .= '</tr>';
	if ( $ce_jour >= $dernier_jour )
		break;
	
	$html .= '<tr height="8" >';
	$html .= '<td width="30" class="out"></td>';
	for ( $j = 0 ; $j < 7 ; $j++ )
		$html .= '<td width="45" class="out"></td>';
	$html .= '</tr>';
	$r++;
	}


$html .= '</table>';
/** merci de ne pas retirer le lien qui est la contrepartie à l'utilisation de ce code **/

$html .= '<p class="nota">'.$bylink.' <a href="http://www.jourdets.com">les chambres d\'hôtes des Jourdets</a></p></div>';


if ( $chotcal_context == "manage" ) {	// manage

$html .= '<div class="wrap cal-event">';




$html .= '<div id="icon-options-general" class="icon32">
<br/>
</div>
<h2>'.$event.'</h2>';

$html .= '
<p>'.$intro.'</p>';


if ( $confirm_bd )
{
$furl = admin_url('admin.php');
$html .= '<form name="confirm_form" id="confirm_form" class="wrap" method="get" action="'.$furl.'" >';
$html .= '<input name="page" type="hidden" value="chotcal_1" />';
// $html .= '<input class="button-primary" type="submit" name="bd_confirm" id="b_confirm" value="Confirm" />';
}
else
{
$furl = admin_url('admin.php?page=chotcal_1');
$html .= '<form name="eventform" id="eventform" class="wrap" method="post" action="'.$furl.'" >';
$html .= '<input name="ich" id="ich" type="hidden" value="" />';
$html .= '<input name="bdid" id="bdid" type="hidden" value="" />';
// $html .= '<input name="admin_page" id="bdid" type="hidden" value="'.$req_url0.'" />';

$html .= '<table class="chotcal-event-table">';

$html .= '<tr><th scope="row">'.$field1.'</th>';
$html .= '<td><input class="regular-text" type="text" name="chambre" id="chambre" value="" readonly="readonly"></td>';
$html .= '</tr>';

$html .= '<tr><th scope="row">'.$field2.'</th>';
$html .= '<td><input class="regular-text" type="text" name="jour" id="jour" value="" readonly="readonly"></td>';
$html .= '</tr>';

$html .= '<tr><th scope="row">'.$field3.'</th>';
$html .= '
<td><input class="regular-text" type="text" size="2" name="njours" id="njours" readonly="readonly" ></td>';
$html .= '</tr>';

$html .= '<tr><th scope="row"></th>';
$html .= '
<td class="slider"><div id="slider"></div></td>';
$html .= '</tr>';

$html .= '<tr><th scope="row">'.$field4.'</th>';
$html .= '<td><textarea cols="40" rows="5" name="infos" id="infos" ></textarea></td>';
$html .= '</tr>';



$html .= '<tr><td colspan="2">';
$html .= '<input class="button-primary butpag" type="submit" name="bd_create" id="b_create" value="'.$t_create.'" />';
$html .= '<input class="button-primary butpag" type="submit" name="bd_edit" id="b_edit" value="'.$t_modif.'" />';
$html .= '<input class="button-primary butpag" type="submit" name="bd_delete" id="b_delete" value="'.$t_delete.'" />';
$html .= '</td></tr></table>';
}
$html .= '</form>';

$html.= '</div>';
} // fin manage

?>