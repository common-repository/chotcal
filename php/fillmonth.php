<?php
// il faut avoir défini $mois et $an
// output : 
// $tab_reserv[chambre][jour] true/false
// $tab_njours[chambre][jour] ( seulement pour le premier jour )
// $tab_infos[chambre][jour]  ( seulement pour le premier jour )
// $tab_vacances[chambre][jour]  true/false ; true si la chambre est fermée ce jour
// creation d'un tableau à 31 entrées (1 pour chaque jour) et on dit qu'aucun jour n'est reservé

// paramètres chambres
// $nchambres = 3;
// $chambres_names = array("Atacama","Punakaiki","Curruhue");
// $chambres_initiales = array("At","Pu","Cu");
// $chambres_colors = array ( '#FF9900' ,'#666633',"#5588B9" );

//Détection du 1er et dernier jour du moiS

$premier_jour = 1;
$dernier_jour = 28;
while (checkdate($mois, $dernier_jour + 1, $an) )
	$dernier_jour++;

for($j = 1; $j <= $dernier_jour; $j++) {
	for ( $k = 0 ; $k < $nchambres ; $k++ ) {
		$tab_njours[$k][$j] = 0;
		$tab_reserv[$k][$j] = (bool)false;
		$tab_vacances[$k][$j] = (bool)false;
		$tab_interv[$k][$j] = '';
		$tab_infos[$k][$j] = '';
		$tab_bdid[$k][$j] = -1;
		$tab_maxstay[$k][$j] = 1;
		}
	}

for($j = $dernier_jour; $j < $dernier_jour+31; $j++)
	for ( $k = 0 ; $k < $nchambres ; $k++ ) 
		$tab_reserv[$k][$j] = (bool)false;


// connexion à la bdd
global $wpdb;  
$table_name = $wpdb->prefix.'chotcal_calendrier';   


$sql = $wpdb->prepare("SELECT * FROM $table_name WHERE YEAR(jour) = %d	AND MONTH(jour) = %d", $an,$mois);  
$result = $wpdb->get_results($sql);  
for ( $i = 0 ; $i < count($result); $i++ ) {
	// recupération du jour ou il y a la reservation
	$jour = $result[$i]->jour;
	// transforme aaaa-mm-jj en jj
	$jour_reserve = (int)substr($jour, 8, 2);
	// insertion des jours reservé dans le tableau
	for ( $j = 1 ; $j <= (int)$result[$i]->njours ; $j++ )
		{
		$mask = 1;
		for ( $k = 0 ; $k < $nchambres ; $k++ )
			{
			if ( (int)$result[$i]->nch & $mask )
				{
				$tab_reserv[$k][$jour_reserve] = (bool)true;	
				if ( $j == 1 )
					{ if ( $j != (int)$result[$i]->njours ) $tab_interv[$k][$jour_reserve] = 'fd1'; }
				else if ( $j == (int)$result[$i]->njours )
					{ if ( $j != 1 ) $tab_interv[$k][$jour_reserve] = 'fd3'; }
				else
					$tab_interv[$k][$jour_reserve] = 'fd2';
				if ( $j == 1 )
					{ // le 1er jour de la resa a ces infos
					$tab_njours[$k][$jour_reserve] = (int)$result[$i]->njours;
					$tab_infos[$k][$jour_reserve] = ereg_replace('(\r\n)|(\n)|(\r)',' ',$result[$i]->infos); 
					$tab_bdid[$k][$jour_reserve] = $result[$i]->id;
					}
				}
			$mask = $mask << 1;
			}	
		$jour_reserve++;
		}
	}

// et les réservations du mois d'avant qui débordent ...

$amois = $mois-1;
$aan = $an;
if ( $mois == 0 )
	{
	$aan--;
	$amois = 12;
	}

$sql = $wpdb->prepare("SELECT * FROM $table_name WHERE YEAR(jour) = %d	AND MONTH(jour) = %d", $aan,$amois);
$result = $wpdb->get_results($sql);  
for ( $i = 0 ; $i < count($result); $i++ ) {
	// recupération du jour ou il y a la reservation
	$jour = $result[$i]->jour;
	// transforme aaaa-mm-jj en jj
	$jour_reserve = (int)substr($jour, 8, 2);
	// insertion des jours reservé dans le tableau
	$doit = false;
	for ( $j = 1 ; $j <= (int)$result[$i]->njours ; $j++ )
		{
		if ( $doit )
			{
			$mask = 1;
			for ( $k = 0 ; $k < $nchambres ; $k++ )
				{
				if ( (int)$result[$i]->nch & $mask )
					{
					$tab_reserv[$k][$jour_reserve] = (bool)true;
					if ( $j == 1 )
						{ if ( $j != (int)$result[$i]->njours ) $tab_interv[$k][$jour_reserve] = 'fd1'; }
					else if ( $j == (int)$result[$i]->njours )
						{ if ( $j != 1 ) $tab_interv[$k][$jour_reserve] = 'fd3'; }
					else
						$tab_interv[$k][$jour_reserve] = 'fd2';
					if ( $j == 1 )
						{
						$tab_njours[$k][$jour_reserve] = (int)$result[$i]->njours;
						$tab_infos[$k][$jour_reserve] = ereg_replace("(\r\n)|(\n)|(\r)", " ", $result[$i]->infos); 
						}
					}
				$mask = $mask << 1;
				}
			}
		if (checkdate($amois, $jour_reserve + 1, $aan))
			$jour_reserve++;
		else 
			{
			$doit = true; // ah ah !
			$jour_reserve = 1;
			$amois++;
			if ( $amois > 12 ) 
				{
				$amois=1;
				$aan++;	
				}
			} 
		} 
	}
	
// les débordements pour maxstay ...
	
$amois = $mois+1;
$aan = $an;
if ( $mois == 13 )
	{
	$aan++;
	$amois = 1;
	}

$sql = $wpdb->prepare("SELECT * FROM $table_name WHERE YEAR(jour) = %d	AND MONTH(jour) = %d", $aan,$amois);
$result = $wpdb->get_results($sql);  
for ( $i = 0 ; $i < count($result); $i++ ) 
	{
	// recupération du jour ou il y a la reservation
	$jour = $result[$i]->jour;
	// transforme aaaa-mm-jj en jj
	$jour_reserve = (int)substr($jour, 8, 2);
	
	$mask = 1;
	for ( $k = 0 ; $k < $nchambres ; $k++ )
		{
		if ( (int)$result[$i]->nch & $mask )
			{
			$tab_reserv[$k][$jour_reserve+$dernier_jour] = (bool)true;
			} 
		$mask = $mask << 1;
		} 
	}
	
// remplissage de $tab_maxstay

for ( $k = 0 ; $k < $nchambres ; $k++ ) 
	{
	for($j = 1; $j < $dernier_jour; $j++ )
		{
		if ( $tab_reserv[$k][$j] == false )
			{
			for ( $i = $j+1 ; $i < $dernier_jour+32 ; $i++  )
				if ( $tab_reserv[$k][$i] == false )
					$tab_maxstay[$k][$j]++;
				else
					break;
			}
		}			
	}

// et les fermetures

$table_name = $wpdb->prefix.'chotcal_fermetures';   

$sql = $wpdb->prepare("SELECT * FROM $table_name");  
$result = $wpdb->get_results($sql);  
for ( $i = 0 ; $i < $dernier_jour; $i++ ) {
	$from = $result[$i]->ffrom;
	$nch = $result[$i]->nch;
	// transforme aaaa/mm/jj en jj
	$mfrom = (int)substr($from, 5, 2);
	$jfrom = (int)substr($from, 8, 2);
	$to = $result[$i]->fto;
	// transforme aaaa/mm/jj en jj
	$mto = (int)substr($to, 5, 2);
	$jto = (int)substr($to, 8, 2);
	// insertion des jours reservés dans le tableau
	for( $j = 1; $j < 32; $j++ ){
		{
		$today = ajout_zero($j,$mois,$an);
		if ( $from <= $today && $today <= $to )
			{
			$mask = 1;
			for ( $k = 0 ; $k < $nchambres ; $k++ )
				{
				if ( $nch & $mask )
					$tab_vacances[$k][$j] = true;
				$mask = $mask << 1;
				}
			}
		}
	}		
}

?>