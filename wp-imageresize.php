<?php
/*
Plugin Name: wp-imageresize
Plugin URI: http://www.nyxsoftware.it/
Description: manage image resize direct on template file
Version: 1.0
Author: NyxSoftware s.r.l.
Author URI: http://www.nyxsoftware.it/
License: GPL2
*/

require_once(__DIR__."/wideimage/WideImage.php");
require_once(__DIR__."/template-functions.php");

define("IMAGERESIZE_DIR",WP_CONTENT_DIR."/images");
define("IMAGERESIZE_URL",WP_CONTENT_URL."/images");

register_activation_hook( __FILE__, "imageresize_register_activation_hook" );

#add_action('admin_menu', 'imageresize_register_custom_menu_page');

function imageresize_register_custom_menu_page() {
   #add_menu_page('Develop', '{Develop}', 'add_users', 'infodataweb-develop-menu','imageresize_infodataweb_develop_info');   
   #add_submenu_page( 'infodataweb-develop-menu', 'wp-image-resize', 'wp-image-resize', 'add_users', 'wp-image-resize-menu', 'imageresize_admin_page' ); 
}

function imageresize_register_activation_hook() {
	global $wpdb;
	
	$tbl = "{$wpdb->prefix}imageresize";
	
	## delete table
	$sql = "DROP TABLE `{$tbl}`";
	$wpdb->query($sql);	
	
	## create table
	$sql = "
		CREATE TABLE IF NOT EXISTS `{$tbl}` (
			`id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`src` varchar(255) NOT NULL,
			`method` varchar(20) NOT NULL,
			`width` int(11) NOT NULL,
			`height` int(11) NOT NULL,
			`cache` varchar(255) NOT NULL
		) COMMENT=''
	";
	$wpdb->query($sql);	
	
	## prepare cache directory
	if(!is_dir(IMAGERESIZE_DIR)){mkdir(IMAGERESIZE_DIR,0777);}
}

function imageresize_cache_filename($src,$method,$width,$height) {
	$info	= pathinfo($src);
	$ext	= $info["extension"];
	$base	= $src."-".$method."-".$width."-".$height;
	return md5($base).'.'.$ext;
}

function imageresize_build_cache_image($src,$method,$width,$height) {
	$file	= imageresize_cache_filename($src,$method,$width,$height);
	$image	= WideImage::load($src);

	if ($method == 'crop') {
		$image = $image->resize($width, $height, 'outside');		
		$image = $image->crop('center', 'center', $width, $height);		
	}		
	
	$image->saveToFile(IMAGERESIZE_DIR."/".$file);		
	
	return IMAGERESIZE_URL."/".$file;
}

function imageresize_src_get_from_db($src,$method,$width,$height) {
	global $wpdb;
	$tbl = "{$wpdb->prefix}imageresize";
	$sql = "
		SELECT cache FROM {$tbl} 
			WHERE method='{$method}' 
			AND height='{$height}' 
			AND width='{$width}' 
			AND src='{$src}' 
		LIMIT 1
	";
	$row = $wpdb->get_row($sql);
	if ($row) {
		return $row->cache;
	} else {
		return false;
	}
}

function imageresize_src_set_into_db($src,$method,$width,$height,$cache) {
	global $wpdb;
	$tbl = "{$wpdb->prefix}imageresize";
	$sql = "
		INSERT INTO {$tbl} (method,height,width,src,cache) 
		VALUES ('{$method}','{$height}','{$width}','{$src}','{$cache}')
	";
	$wpdb->query($sql);
}

