<?php
// conversion de la date (aaaa-mm-jj) en deux variables $mois (mm) et $an (aa)
function convertion($date){
// recup�re les 2 caract�re apr�s le 5eme caract�re de $ date (aaaa-mm-jj donne mm)
$mois = substr($date, 5, 2);
// recup�re les 4 premiers carat�res de $ date (aaaa-mm-jj donne aaaa)
$an  = substr($date, 0, 4);
// on retourne un tableau contanant les deux variables
return array( $mois, $an);
}

function inc_jour($jour) // aaaa-mm-jj
{
	$j = (int)substr($jour, 8, 2);
	$m = (int)substr($jour, 5, 2);
	$a = (int)substr($jour, 0, 4);
	if (checkdate($m, $j + 1, $a))
		$j++;
	else {
		$j = 1;
		$m++;
		if ( $m > 12 ) {
			$m=1;
			$a++;
		}
	}
		
return ajout_zero($j, $m, $a);
}

function dec_jour($jour) // aaaa-mm-jj
{
	$j = (int)substr($jour, 8, 2);
	$m = (int)substr($jour, 5, 2);
	$a = (int)substr($jour, 0, 4);
	if (checkdate($m, $j - 1, $a))
		$j--;
	else 
		{
		$m--;
		if ( $m < 1 ) 
			{
			$m=12;
			$a--;
			}
		$j = 31;
		while ( !checkdate($m, $j, $a) )
			$j--;
		}
		
return ajout_zero($j, $m, $a);
}

// fonction permetant de retourner la date au format aaaa-mm-jj
function n2dig($jj){
	// ajoute un 0 quand le jour ne contient pas de 0 et qu'il est inferieur à 10 (8 donne 08)
	if($jj <= 9 && substr($jj, 0, 1)!= 0)
		$jj  = '0'.$jj;
return $jj;
}

// fonction permetant de retourner la date au format aaaa-mm-jj
function ajout_zero($jj, $mm, $aa){
	// ajoute un 0 quand le jour ne contient pas de 0 et qu'il est inferieur � 10 (8 donne 08)
	if($jj <= 9 && substr($jj, 0, 1)!= 0){
		$jj  = '0'.$jj;
	}	
	// ajoute un 0 quand le mois ne contient pas de 0 et qu'il est inferieur � 10 (8 donne 08)
	if($mm <= 9 && substr($mm, 0, 1)!= 0){
		$mm  = '0'.$mm;
	}
// on retourne le tout sous la forme aaaa-mm-jj
$retour = (string)$aa.'-'.$mm.'-'.$jj;
return $retour;
}

// fonction permetant de retourner la date au format aaaa-mm-jj
// a compléter manuellement chaque année ... bof 

function isferie($jj, $mm, $an){
$ferie=(bool)false;
	if ( $mm == 1 && $jj == 1 ) $ferie=(bool)true;
	if ( $mm == 5 && $jj == 1 ) $ferie=(bool)true;
	if ( $mm == 5 && $jj == 8 ) $ferie=(bool)true;
	if ( $mm == 7 && $jj == 14 ) $ferie=(bool)true;
	if ( $mm == 8 && $jj == 15 ) $ferie=(bool)true;
	if ( $mm == 11 && $jj == 1 ) $ferie=(bool)true;
	if ( $mm == 11 && $jj == 11 ) $ferie=(bool)true;
	if ( $mm == 12 && $jj == 25 ) $ferie=(bool)true;
	if ( $an == 2008 )
		{
		if ( $mm == 3 && $jj == 24 ) $ferie=(bool)true;
		if ( $mm == 5 && $jj == 12 ) $ferie=(bool)true;
		}
	if ( $an == 2009 )
		{
		if ( $mm == 4 && $jj == 13 ) $ferie=(bool)true;
		if ( $mm == 5 && $jj == 21 ) $ferie=(bool)true;
		if ( $mm == 6 && $jj == 1 ) $ferie=(bool)true;
		}
	if ( $an == 2010 )
		{
		if ( $mm == 4 && $jj == 5 ) $ferie=(bool)true;
		if ( $mm == 5 && $jj == 5 ) $ferie=(bool)true;
		if ( $mm == 5 && $jj == 24 ) $ferie=(bool)true;
		}
if ( $an == 2011 )
		{
		if ( $mm == 4 && $jj == 25 ) $ferie=(bool)true;
		if ( $mm == 6 && $jj == 2 ) $ferie=(bool)true;
		if ( $mm == 6 && $jj == 13 ) $ferie=(bool)true;
		}
return $ferie;
}

// récupéré sur phpcs : http://www.phpcs.com/codes/LISTE-JOURS-FERIES-ANNEE_32791.aspx
// une version dans les commentaires !!!

function getPublicHoliday($day,$month,$year,$departement = false) {
    $array = array();
    for ($i = 1; $i < 13; ++$i) {
      $indice = ($i < 10) ? '0'.$i : $i;
      $array["$indice"] = array();
    }
    // National public holidays
    $array['01']['01']  = 'Jour de l\'an';
    $array['05']['01']  = 'Fête du travail';
    $array['05']['08']  = 'Armistice 39-45';
    $array['07']['14']  = 'Fête nationale';
    $array['08']['15']  = 'Assomption';
    $array['11']['01']  = 'Toussaint';
    $array['11']['11']  = 'Armistice 14-18';
    $array['12']['25']  = 'Noël';
    $timestamp = mktime(0, 0, 0, 03, 21 + easter_days($year) + 1, $year);
    $array[date('m', $timestamp)][date('d', $timestamp)]  = 'Lundi de Pâques';
    $timestamp = mktime(0, 0, 0, 03, 21 + easter_days($year) + 39, $year);
    $array[date('m', $timestamp)][date('d', $timestamp)]  = 'Jeudi de l\'ascension';
    $timestamp = mktime(0, 0, 0, 03, 21 + easter_days($year) + 50, $year);
    $array[date('m', $timestamp)][date('d', $timestamp)]  = 'Lundi de Pentecôte';
    // Spécial Public holidays
    if ($departement && ($departement == '57' || $departement == '67' || $departement == '68')) {
      $timestamp = mktime(0, 0, 0, 03, 21 + easter_days($year) - 2, $year);
      $array[date('m', $timestamp)][date('d', $timestamp)]  = 'Vendredi saint';
      $array['12']['26']  = 'Lendemain de Noël';
    }
    if ($departement && $departement == '971')
      $array['05']['27']  = 'Abolition de l\'esclavage';
    if ($departement && $departement == '972')
      $array['05']['22']  = 'Abolition de l\'esclavage';
    if ($departement && $departement == '973')
      $array['06']['10']  = 'Abolition de l\'esclavage';
    if ($departement && $departement == '974')
      $array['12']['20']  = 'Abolition de l\'esclavage';
    // Check if the date is a public holiday
    $monthToPrint = ($month < 10) ? '0'.$month : $month;
    $dayToPrint   = ($day < 10)   ? '0'.$day   : $day;
    if (isset($array["$monthToPrint"]) && isset($array["$monthToPrint"]["$dayToPrint"]))
      return $array["$monthToPrint"]["$dayToPrint"];
    return false;
  }

?>