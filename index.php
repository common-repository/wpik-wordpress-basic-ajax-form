<?php
/*
Plugin Name: Wpik WordPress Basic Ajax Form
Plugin URI: http://imran1.com/wpik-wordpress-basic-ajax-form/
Description: This is a Basic AJAX WordPress Form Plugin
Version: 1.0
Author: Imran Khan
Author URI: http://www.imran1.com
License: GPL2
*/

/**
 * Copyright (c) 2018 Imran1 (email: info@imran1.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */


 
// function to create the DB / Options / Defaults					
function wpik_install() {
       	global $wpdb;
      	$table_name = $wpdb->prefix . 'ajax_demo ';
    
     
    	// create the ajax_demo database table
    	if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
    	{
    		$sql = "CREATE TABLE " . $table_name . " (
    		id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
            name VARCHAR(255) NOT NULL,
    		UNIQUE KEY id (id)
    		);";
     
    		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    		dbDelta($sql);
	}
 
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'wpik_install');



register_deactivation_hook( __FILE__, 'wpik_uninstall' );

function wpik_uninstall() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'ajax_demo';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     //delete_option("my_plugin_db_version");
}   



//Include Javascript library
wp_enqueue_script('imran1', plugins_url( '/js/demo.js' , __FILE__ ) , array( 'jquery' ));
// including ajax script in the plugin Myajax.ajaxurl
wp_localize_script( 'imran1', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));



function wpik_post_word_count(){
        $name = $_POST['dname'];
        global $wpdb;
        $wpdb->insert( 
        	$wpdb->prefix.'ajax_demo', 
        	array( 
        		'name' => $name
        	), 
        	array( 
        		'%s'
        	) 
        );
        
        //$thepost = $wpdb->get_row( "SELECT * FROM ". $wpdb->prefix."ajax_demo order by id desc" );
        /* Add Data Jquery code starts */
        //echo "  <div id='show' align='left'><b>Successfully Added:</b> $thepost->name</div>";	
        
        $get_thepost = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix."ajax_demo order by id desc");
        
        echo "<div id='show' align='left3'>
                <h2>All Names:</h2>";
        foreach ( $get_thepost as $getpost ) 
        {
        echo "<div> $getpost->name </div>";
        }
        echo "</div>";
        die();
        return true;
}


// wp_ajax_function_name –> it allow function calling from admin dashborad only
add_action('wp_ajax_wpik_post_word_count', 'wpik_post_word_count');  // Call when user logged in

// wp_ajax_nopriv_function_name –> it allow function calling from admin as well as all pages 
add_action('wp_ajax_nopriv_wpik_post_word_count', 'wpik_post_word_count'); // Call when user in not logged in




function wpik_show_form(){
global $wpdb;
//$thepost = $wpdb->get_row( "SELECT * FROM wp_ajax_demo order by id desc" );

$thepost = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix."ajax_demo order by id desc");
echo "<div id='get_name' align='left'>
<h2>All Names:</h2>";
foreach ( $thepost as $getpost ) 
{
echo "<div>$getpost->name</div>";
 
}
echo "</div>";

echo "<div id='show' align='left'></div>";
	

echo "<form>";
echo "<label>Name: </label>";
echo "<input type='text' id='dname' name='dname' value=''/> &nbsp;&nbsp;";
echo "<input type='button' id='submit' name='submit' value='Submit'/>";
echo "</form>";
}

add_shortcode( 'ajax_form', 'wpik_show_form' );

//add_filter('the_content', 'wpik_show_form');

//Display Form before page contents
function wpik_output_shortcode_before_content( $content ) {
        $output_shortcode = do_shortcode( '[ajax_form]' );
        $output_shortcode .= $content;
	
        return $output_shortcode;
}
add_filter( 'the_content', 'wpik_output_shortcode_before_content' );
?>