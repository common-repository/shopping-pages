<?php
/*
Plugin Name: WP Shopping Pages
Plugin URI: http://wpshoppingpages.com/
Version: 1.14
Description: Add powerful affiliate shopping pages to your weblog with ease and earn commission.
Author: CMS Commander
Author URI: http://cmscommander.com/
*/

/*************************************************************
 * Copyright (c) 2010 Thomas Hoefter
 * www.cmscommander.com
 **************************************************************/

if (version_compare(PHP_VERSION, '5.0.0.', '<'))
{
	die("WP Shopping Pages requires php 5 or a greater version to work.");
}

if (!defined('WP_CONTENT_URL')) {
   define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}

function wpsp_default_options($update=0) {	
	$options = unserialize(get_option("wpsp_options"));	

		$options["amazon_desc_length"] = 300;
		$options["amazon_site"] = "com";
		$options["post_type"] = "page";
		$options["post_status"] = "publish";
		$options["post_comments"] = "open";
		$options["amazon_reviews"] = '<div style="clear:left;">&#13;<i><b>Review by {author}: {rating}</b></i><br/>&#13;<p>{thumbnail}{content}</p>&#13;</div>';
		$options["ebay_country"] = 0;
		$options["ebay_lang"] = "en-US";
		$options["amazon_skip_if"] = "nodesc";
		$options["post_author"] = 1;
		$options["amazon_search_method"] = "broad";
		$options["add_title_template"] = "Buy {keyword}";
		$options["add_page_template"] = "randomp";
		$options["add_ama_template"] = "randoma";
		$options["add_ebay_template"] = "randome";
		$options["ebay_template"] = '<div style="float:left;width:200px;height:150px;padding:10px;"><strong>{title}</strong><br/>&#13;<div style="float:left;margin: 0 10px 0 0;">{thumbnail}</div>&#13;<strong>Price:</strong><br/>{price}<br/>&#13;<a href="{url}" rel="nofollow"><strong>Buy Now</strong></a>&#13;</div>';	
		$options["add_ama_browsenode"] = 0;	
		$options["feat_links"] = 1;	
		$options["add_ama_si"] = "All";	
		$options["add_ebay_cat"] = "all";
		$options["ebay_cache_length"] = 100;
		$options["wpsp_auto_update"] = "No";	
		$options["amazon_noshortcode"] = "Yes";			
	if($update == 1) {
		update_option("wpsp_options", serialize($options));	
		return $options;
	} else {
		$options["amazon_apikey"] = "";
		$options["amazon_secret"] = "";
		$options["amazon_affid"] = "";	
		$options["ebay_campid"] = "";	
		add_option("wpsp_options", serialize($options));	
	}

}

function wpsp_default_templates() {
    global $wpdb;

	// PAGE TEMPLATES: mixed, ama, ls, cj				
	$sql = "INSERT INTO `wp_wpsp_templates` VALUES (NULL, 'Page Template 2 (Amazon only)', '<strong>Featured {keyword}:</strong>\r\n{nav}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n\r\n<p style=\"clear:both;\">Find more {keyword} products on <a href=\"{amazonsearch}\"><strong>Amazon</strong></a>!</p>', 'page');";	
	$wpdb->query($sql);			
	$sql = "INSERT INTO `wp_wpsp_templates` VALUES (NULL, 'Page Template 1 (Mixed)', '<strong>On this page you will find the following popular {keyword}:</strong>\r\n{nav}\r\n{ebay}\r\n{ebay}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n{amazon}\r\n<h3>{keyword} Auctions</h3>\r\n{ebay}\r\n{ebay}\r\n{ebay}\r\n{ebay}\r\n\r\n<p style=\"clear:both;\">Find more {keyword} products on <a href=\"{amazonsearch}\"><strong>Amazon</strong></a>!</p>', 'page');";	
	$wpdb->query($sql);
	$sql = "INSERT INTO `wp_wpsp_templates` VALUES (NULL, 'Amazon Single Template ', '<div style=\"border: 1px solid #ccc;padding:5px;margin-top:5px;clear:left;\">\r\n\r\n <h3 style=\"margin-top:5px;\">{title}</h3>\r\n\r\n{thumbnail}\r\n{description}\r\n{features}\r\n\r\n<p>\r\n<div style=\"float:right;\">{buynow-big}</div>\r\n[has_listprice]\r\nList Price: {listprice}<br/>\r\n[/has_listprice]\r\n<strong>Price: {price}</strong><br/>\r\n</p>\r\n\r\n<div style=\"clear:both;\"></div>\r\n{reviews-iframe}</div>\r\n', 'amazon');";
	$wpdb->query($sql);		
	//$sql = "INSERT INTO `wp_wpsp_templates` VALUES (NULL, 'Amazon Single Template 1', '<div style=\"border: 1px solid #ccc;padding:5px;margin-top:5px;clear:left;\">\r\n\r\n <h3>{title}</h3>\r\n\r\n{thumbnail}\r\n{description}\r\n{features}\r\n\r\n[has_rating]\r\n<p>\r\n<strong>Rating:</strong> {rating} (out of {totalReviews} reviews)\r\n</p>\r\n[/has_rating]\r\n\r\n<p>\r\n<div style=\"float:right;\">{buynow-big}</div>\r\n[has_listprice]\r\nList Price: {listprice}<br/>\r\n[/has_listprice]\r\n<strong>Price: {price}</strong><br/>\r\n</p>\r\n\r\n[has_reviews]\r\n<h4>{title} Reviews</h4>\r\n{reviews:2}\r\n<p><strong>Buy {link} now for only {price}!</strong></p>\r\n[/has_reviews]\r\n\r\n</div>', 'amazon');";
	//$wpdb->query($sql);	
	
}

function wpsp_install() {
    global $wpdb;
	
	if ( !empty($wpdb->charset) )
		$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";	

    $isql[] = "CREATE TABLE `wp_wpsp_pages` (
	`ID` int(11) NOT NULL auto_increment,
	`pageID` int(11) NOT NULL,
	`keyword` varchar(255) NOT NULL,
	`pageTmpl` int(11) NOT NULL,
	`pageTitle` varchar(255) NOT NULL, 
	`amazonTmpl` varchar(255) NOT NULL,
	`ebayTmpl` varchar(255) NOT NULL,
	PRIMARY KEY  (`ID`)
	) {$charset_collate};";
	
    $isql[] = "CREATE TABLE `wp_wpsp_templates` (  
	`ID` int(11) NOT NULL auto_increment,  
	`tmpl_title` varchar(255) NOT NULL,  
	`tmpl_code` text NOT NULL,  
	`tmpl_type` varchar(255) NOT NULL,  
	PRIMARY KEY  (`ID`)
	) {$charset_collate};";	

    $isql[] = "CREATE TABLE `wp_wpsp_ebaycache` (  
	`id` int(11) NOT NULL auto_increment, 
	`adid` int(11) NOT NULL, 
	`ebay_Key` varchar(255) NOT NULL,  
	`ebay_Content` text,  
	`views_count` int(11) NOT NULL,  
	PRIMARY KEY  (`ID`)
	) {$charset_collate};";		
	
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($isql);	

	if (get_option("wpsp_options") == false || get_option("wpsp_options") == "") {	
		wpsp_default_options();
	}
	
	wpsp_default_templates();
}

function wpsp_uninstall() {
	global $wpdb;
	$sql = "DROP TABLE `wp_wpsp_pages`, `wp_wpsp_templates`, `wp_wpsp_ebaycache`;";
	$wpdb->query($sql);
	delete_option("wpsp_options");
}
register_activation_hook(__FILE__,'wpsp_install');
register_deactivation_hook(__FILE__,'wpsp_uninstall');

require_once 'wpsp.class.php';
$amazon = new wpspclass(); //ABSPATH,PLUGINDIR
$i=0;
if(isset ($_POST['action'])) {
    $action = $_POST['action'];
} else {
    $action  = $_GET['action'];
}

switch ($action) {
    case "addtemplate" :
		if($_POST['readd_default']){
			wpsp_default_templates();
			$amazon->_showmsg = true;
			$amazon->_message = 'Default templates have been added.';	
		} else {
			if(!$_POST['tmpl'] || !$_POST['tmpl_code'] || !$_POST['tmpl_type']) {
				$amazon->_showmsg = true;
				$amazon->_message = 'Error: Please enter all required information.';		
			} else {
				$result = $amazon->add_template($_POST['tmpl'], $_POST['tmpl_code'],$_POST['tmpl_type']);
				if($result == false) {
					$amazon->_showmsg = true;
					$amazon->_message = 'Error: Template could not be added.';
				} else {
					$amazon->_showmsg = true;
					$amazon->_message = 'Template has been added.';		
				}
			}
		}
        break;
    case "updatetmpl" :
		if(!$_POST['tmpl_ID'] || !$_POST['tmpl'] || !$_POST['tmpl_code'] || !$_POST['tmpl_type']) {
			$amazon->_showmsg = true;
			$amazon->_message = 'Error: Please enter all required information.';		
		} else {	
			$result = $amazon->update_template($_POST['tmpl_ID'], $_POST['tmpl'], $_POST['tmpl_code'], $_POST['tmpl_type']);
			if($result == false) {
				$amazon->_showmsg = true;
				$amazon->_message = 'Error: Template could not be updated.';
			} else {
				$amazon->_showmsg = true;
				$amazon->_message = 'Template has been updated.';		
			}
		}	
        break;
    case "deletetmpls" :
        $pages = $amazon->get_templates();
        //print_r($_POST);
        foreach ($pages as $page){
            if($_POST['p-'.$page['ID']]=='on'){
                //echo $page['ID'].' - '.$page['keyword'];
                $amazon->delete_template($page['ID']);
            }
        }
        $amazon->_showmsg = true;
        $amazon->_message = 'Templates deleted.';
        break;
    case "deletetmpl":
        $result = $amazon->delete_template($_GET['tmpl']);
			if($result == false) {
				$amazon->_showmsg = true;
				$amazon->_message = 'Error: Template could not be deleted.';
			} else {
				$amazon->_showmsg = true;
				$amazon->_message = 'Template has been deleted.';		
			}
        break;
    case "editoption" :
	/*	if($_POST['reset_options']){
			$check = wpsp_default_options(1);
			if($check) {
				$amazon->populate();
				$amazon->_showmsg = true;
				$amazon->_message = 'Settings have been reset.';	
			} else {
				$amazon->_showmsg = true;
				$amazon->_message = 'Error: Settings could not be reset.';				
			}
		} else {	
			//$amazon->edit_option($_POST["amazon-key"], $_POST["secret-key"], $_POST["camp-id"],$_POST["amazon-desc"],$_POST["amazon-affilated"]);
			$amazon->_showmsg = true;
			$amazon->_message = 'Settings have been updated.';
		}*/
        break;
    case "updatepage" :
        $pages = $amazon->get_pages();
        //print_r($_POST);
        foreach ($pages as $page){
            if($_POST['p-'.$page['ID']]=='on'){
                //echo $page['ID'].' - '.$page['keyword'];
                $result = $amazon->update_page($page['ID'], $page['pageID']);
				if($result == false) {
					$amazon->_showmsg = true;
					$amazon->_message = 'Error: Page could not be updated (either because the found content was the same or the SQL query failed).';
				} else {
					$amazon->_showmsg = true;
					$amazon->_message = 'Page has been updated.';		
				}								
            }
        }
        break;
    case "deletepage" :
        $pages = $amazon->get_pages();
        //print_r($_POST);
        foreach ($pages as $page){
            if($_POST['p-'.$page['ID']]=='on'){
                //echo $page['ID'].' - '.$page['keyword'];
                $amazon->delete_page($page['ID'], $page['pageID'], $page['keyword']);
            }
        }
        $amazon->_showmsg = true;
        $amazon->_message = 'Pages deleted.';
        break;
    default :
        break;
}

?>