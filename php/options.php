<?php include("messages.php"); ?>
<div class=wrap>  
    <div class="icon32" id="icon-edit"><br /></div>  
    <h2><?php echo $titopts; ?></h2>  
    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">  
        <div class="postbox " >  
            <h3 class="opt-title"><span><?php echo $lnch; ?></span></h3> 
            <label for="nbch"><?php echo $lnch; ?> </label>
            	<select name="nbch" >
				<option value="1" <?php if ($options['nbch']==1) echo'selected="selected"'; ?>>1</option>
				<option value="2" <?php if ($options['nbch']==2) echo'selected="selected"'; ?>>2</option>
				<option value="3" <?php if ($options['nbch']==3) echo'selected="selected"'; ?>>3</option>
				<option value="4" <?php if ($options['nbch']==4) echo'selected="selected"'; ?>>4</option>
				<option value="5" <?php if ($options['nbch']==5) echo'selected="selected"'; ?>>5</option>
				</select>
              
  
        </div>  
        <?php 
        $opt1 = "nom";
		$opt2 = "abr";
		$opt3 = "col";
        for ( $i = 0 ; $i < (int)$options['nbch'] ; $i++ )
        	{
        	$j = $i + 1;
        	echo '<div class="postbox">';  
        	$titre = $opts.$j; 
        	$name = $opt1.$j;
        	$abrg = $opt2.$j;
        	$color = $opt3.$j;
            echo '<h3 class="opt-title"><span>';
            _e($titre);
            echo '</span></h3>'; 
            echo '<p>';
            echo '<label for="'.$name.'">'.$lopt1.' : </label><input type="text" name="'.$name.'" size="20" value="'.$options[$name].'" >';
            echo '<label for="'.$abrg.'">'.$lopt2.' : </label><input type="text" name="'.$abrg.'" size="2" value="'.$options[$abrg].'" >';
            echo '<label for="'.$color.'">'.$lopt3.' : </label><input class="color" name="'.$color.'" value="'.$options[$color].'" >';
            echo '</p>';
        	echo '</div>';
        	}
        ?>
        
        <div class="submit">  
            <input type="submit" name="update_wp_chotcalSettings" value="<?php echo $dmaj; ?>" />  
        </div>  
</div>  