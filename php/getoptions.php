<?php
// paramètres du calendrier

$options = get_option($this->adminOptionsName);

$nchambres = (int)$options['nbch'];
for ( $i = 1 ; $i <= $nchambres ; $i++ )
	{
	$chambres_names[$i-1] = $options['nom'.$i];
	$chambres_initiales[$i-1] = $options['abr'.$i];
	$chambres_colors[$i-1] = '#'.$options['col'.$i];
	}
?>