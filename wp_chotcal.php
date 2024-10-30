<?php


/* 
Plugin Name: wp_chotcal
Plugin URI: http://www.jourdets.com/codage/fr_calendar.html
Description: wp_chotcal is a booking calendar for B&B
Version: 1.0 
Author: <a href="http://www.jourdets.com">Les Jourdets</a>
Author URI: http://www.jourdets.com
*/  

include('php/messages.php'); // messages

if (!class_exists("wp_chotcal")) {  
	
class wp_chotcal  
	{  
	/*** Constructor ***/  
	var $adminOptionsName = 'wp_chotcalAdminOptions'; 
	var $version = "1.0.0";
	function wp_chotcal()  
		{  
                  
		}  
	function getAdminOptions()   
    	{  
        $wp_chotcalAdminOptions = array(  
            'nbch' => 2  ,
       		'nom1' => 'chambre 1',
        	'nom2' => 'chambre 2',
        	'nom3' => 'chambre 3',
        	'nom4' => 'chambre 4',
        	'nom5' => 'chambre 5',
        	'abr1' => 'c1',
        	'abr2' => 'c2',
        	'abr3' => 'c3',
        	'abr4' => 'c4',
        	'abr5' => 'c5',
        	'col1' => 'FFFFFF',
        	'col2' => 'FFFFFF',
        	'col3' => 'FFFFFF',
        	'col4' => 'FFFFFF',
        	'col5' => 'FFFFFF'
            );  
        $wp_chotcalOptions = get_option($this->adminOptionsName);  
        if ( !empty($wp_chotcalOptions) )  
        	{  
            foreach ($wp_chotcalOptions as $key => $option)  
            	{
                $wp_chotcalAdminOptions[$key] = $option;  
            	}
        	}  
        update_option($this->adminOptionsName, $wp_chotcalAdminOptions);  
        return $wp_chotcalAdminOptions;  
    	}  

/**************************/
    	
    function init()   
    	{  
        $this->getAdminOptions();  
    	}  
    
/*** Installation du plugin wp_chotcal ***/  
    function wp_chotcal_install()  
    	{  
        global $wpdb;  
           
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 

        //création des tables  
        $table_name = $wpdb->prefix.'chotcal_calendrier';  
        $sql = "CREATE TABLE `$table_name` (  
        	`id` int(1) NOT NULL AUTO_INCREMENT,
  			`jour` date NOT NULL,
  			`njours` int(1) NOT NULL DEFAULT '1',
  			`nch` int(1) NOT NULL DEFAULT '0',
  			`infos` longtext CHARACTER SET latin1 NOT NULL,
  			PRIMARY KEY (`id`)
             ) ;";  
        
        dbDelta($sql);  
        
        $table_name = $wpdb->prefix.'chotcal_fermetures';  
        $sql = "CREATE TABLE `$table_name` (  
        	`id` int(1) NOT NULL AUTO_INCREMENT,
  			`ffrom` date NOT NULL,
  			`fto` date NOT NULL,
  			`nch` int(1) NOT NULL DEFAULT '255',
  			PRIMARY KEY (`id`)
             ) ;";  
        
        dbDelta($sql);  
           
        $option['wp_chotcal_version'] = $this->version;  
        add_option('wp_chotcal_version',$option);  
    	}   
/*** Désinstallation du plugin wp_chotcal ***/  
    	
    function wp_chotcal_uninstall()  
    {  
        global $wpdb;  
        
        
        // drop des tables  
        $table_name = $wpdb->prefix.'chotcal_calendrier';   
        $sql = "DROP TABLE `$table_name`";  
        $wpdb->query($sql);  
        $table_name = $wpdb->prefix.'chotcal_fermetures';  
        $sql = "DROP TABLE `$table_name`";  
        $wpdb->query($sql); 
        
        delete_option('wp_chotcal_version');  
        delete_option($this->adminOptionsName);  
        
        remove_menu_page('chotcal_1');
        remove_menu_page('chotcal_1','chotcal_2');
    }  
    
/****************/
    
    function optionsPage() 
    	{  
        $options = $this->getAdminOptions();  
        if (isset($_POST['update_wp_chotcalSettings'])) 
        	{  
         	if (isset($_POST['nbch'])) 
                $options['nbch'] = $_POST['nbch'];   
            for ( $i = 1 ; $i <= 5 ; $i++ )
            	{
            	$ind = 'nom'.$i;
            	if (isset($_POST[$ind])) 
                	$options[$ind] = $_POST[$ind];
                $ind = 'abr'.$i;
            	if (isset($_POST[$ind])) 
                	$options[$ind] = $_POST[$ind];  
                $ind = 'col'.$i;
            	if (isset($_POST[$ind])) 
                	$options[$ind] = $_POST[$ind];    
            	}  

            update_option($this->adminOptionsName, $options);  
            print '<div class="updated"><p><strong>';  
            global $pmaj;
            print $pmaj;
            print '</strong></p></div>';    
        	}  
        include('php/options.php'); // include du formulaire HTML  
    	} 
    	
    	
   	
/****************/
    	
    function managePage()
    	{
    	$html = '';  
    	$chotcal_context = "manage";
		include('php/page.php'); 
		echo $html;
    	}
    	
/****************/
    	
    function vacancesPage()
    	{
    	$html = '';  
		include('php/fermetures.php'); 
		echo $html;
    	}
    	
/****************/
    	
    function initPageStyles()
    	{
    	$file = plugins_url( "wp_chotcal/css/wp_chotcal.css", dirname(__FILE__) );
    	wp_register_style('wp_chotcal_css', $file);  
    	wp_enqueue_style( 'wp_chotcal_css');
    	}
    	
/****************/
    	
 	function initAdminStyles()
    	{
    	$this->initPageStyles();
    	$file = plugins_url( "wp_chotcal/css/jquery-ui-1.8.18.custom.css", dirname(__FILE__) );
    	wp_register_style('wp_slider_css',$file);  
    	wp_enqueue_style( 'wp_slider_css');
    	}	
    	
/*** Affichage du calendrier sur la page via les shortcodes ***/
	
    function pageCalendar($attributes, $initialContent = '')  
    	{  
        $options = get_option($this->adminOptionsName);   
        
        $html = '';  
        $chotcal_context = "page";
		include('php/page.php');  
        return $html;  
        } 
        
	}  
}

if (class_exists("wp_chotcal"))  
    {  
    $inst_wp_chotcal = new wp_chotcal();  
    }  

add_action('activate_wp_chotcal/wp_chotcal.php',  array(&$inst_wp_chotcal, 'init'));  

/* Function to add the javascript to the admin header */

function wp_chotcal_admin_javascripts()

{ 
  $file = plugins_url( "wp_chotcal/jscolor/jscolor.js", dirname(__FILE__) );
  wp_register_script('jscolor', $file);
  wp_enqueue_script('jscolor');
  $file = plugins_url( "wp_chotcal/js/jquery-ui-1.8.18.custom.min.js", dirname(__FILE__) );
  wp_register_script('jquery-slider', $file, array('jquery','jquery-ui-core','jquery-ui-widget','jquery-ui-mouse'));
  wp_enqueue_script('jquery-slider'); 
}

/****** fonction pour admin ( paramètres ) */

if ( !function_exists("wp_chotcal_admin") )   
    {  
    function wp_chotcal_admin() 
    	{  
    	global $inst_wp_chotcal;  
    	if (!isset($inst_wp_chotcal))
    		return;  
		
    	if (function_exists('add_menu_page')) 
     		{
     		global $menu1;
       		add_menu_page('wp_chotcal',$menu1, 'administrator', 'chotcal_1' , array(&$inst_wp_chotcal, 'managePage'));
     		}
   		if (function_exists('add_submenu_page')) 
     		{
     		global $smenu1,$smenu2,$smenu3;
       		add_submenu_page('chotcal_1', 'wp_chotcal', $smenu1,'administrator', 'chotcal_1' , array(&$inst_wp_chotcal, 'managePage'));
       		add_submenu_page('chotcal_1', 'wp_chotcal', $smenu2,'administrator', 'chotcal_2' , array(&$inst_wp_chotcal, 'optionsPage'));
       		add_submenu_page('chotcal_1', 'wp_chotcal', $smenu3,'administrator', 'chotcal_3' , array(&$inst_wp_chotcal, 'vacancesPage'));
       		}

        }  
    }  

add_action('admin_menu', 'wp_chotcal_admin');
add_action('admin_enqueue_scripts', 'wp_chotcal_admin_javascripts' );



/*****************************************/

if ( function_exists('add_shortcode') )  
    {  
    add_shortcode('chotcal',array(&$inst_wp_chotcal, 'pageCalendar'));  
    } 

add_action('wp_print_styles',  array(&$inst_wp_chotcal, 'initPageStyles'));
add_action('admin_print_styles',  array(&$inst_wp_chotcal, 'initAdminStyles'));


register_activation_hook(__FILE__, array(&$inst_wp_chotcal, 'wp_chotcal_install'));  
register_deactivation_hook(__FILE__, array(&$inst_wp_chotcal, 'wp_chotcal_uninstall'));  


?>