<?php

/*************************************************************
 * Copyright (c) 2010 Thomas Hoefter
 * www.cmscommander.com
 **************************************************************/

class wpspclass {
    var $_tmpl_action = 'add';
    var $_pluginpath;
	var $_imagepath;
    var $_tmpl_id;
    var $_awspublic;
    var $_awssecret;
    var $_campID;
    var $_showmsg;
    var $_message;
    var $_affilateID;

    function wpspclass() { 
		$options = unserialize(get_option("wpsp_options"));
		$this->settings = $options;
        $this->_pluginpath = ABSPATH.PLUGINDIR."/".basename(dirname(__FILE__))."/";
		$this->_imagepath = get_option('siteurl').'/wp-content/plugins/'.basename(dirname(__FILE__)).'/images/';
        $this->_awssecret = $options['amazon_secret'];
        $this->_awspublic = $options['amazon_apikey'];
        $this->_affilateID = $options['amazon_affid'];
        $this->_campID = $options['ebay_campid'];
        add_action('admin_menu', array(&$this, 'add_admin_menu'));
		register_activation_hook(basename(__FILE__),'wpsp_activation');
        add_shortcode('ebayrss', array (&$this, 'ebayrss_handler') );
		add_shortcode('wpspamareviews', array (&$this, 'wpsp_ama_reviews') );
		add_shortcode('wpspamazon', array (&$this, 'wpsp_ama_handler') );
		add_action( "wpsphook", array (&$this, 'update_pages') );
		$this->_showmsg = false;
        $this->_message='';
		//error_reporting(NULL);
		//ini_set('display_errors', 0);		

    }
	
	function populate() {
		$options = unserialize(get_option("wpsp_options"));
		$this->settings = $options;
        $this->_awssecret = $options['amazon_secret'];
        $this->_awspublic = $options['amazon_apikey'];
        $this->_affilateID = $options['amazon_affid'];
        $this->_campID = $options['ebay_campid'];
	}
	
	function populate2($options) {
		$this->settings = $options;
        $this->_awssecret = $options['amazon_secret'];
        $this->_awspublic = $options['amazon_apikey'];
        $this->_affilateID = $options['amazon_affid'];
        $this->_campID = $options['ebay_campid'];
	}	

    function add_admin_menu() {
		add_menu_page('ShoppingPages', 'ShoppingPages', 'activate_plugins', 'amtp-admin-handle',array($this,  'wpsp_addpages_page'));
        add_submenu_page('amtp-admin-handle','Options', 'Options', 'activate_plugins', 'amtp-addpages-page',array($this, 'wpsp_settings_page'));
        add_submenu_page('amtp-admin-handle', 'Templates', 'Templates', 'activate_plugins', 'amtp-templates-page',array($this,  'wpsp_templates_page'));
    }

    function wpsp_settings_page() {
	
		if($_POST['reset_options']){
			$options = wpsp_default_options(1);
			//print_r($options);
			if($options) {
				$this->populate2($options);
				$this->_showmsg = true;
				$this->_message = 'Settings have been reset.';	
			} else {
				$this->_showmsg = true;
				$this->_message = 'Error: Settings could not be reset.';				
			}
		} else {	
			if(empty($options)) {
				$options = unserialize(get_option("wpsp_options"));
				//print_r($options);
			}
		
		}
		
		if($_POST['submitoptions']){

			$_POST['ebay_template'] = str_replace('\"', '"', $_POST['ebay_template']);
			$_POST['amazon_reviews'] = str_replace('\"', '"', $_POST['amazon_reviews']);
			
			$options = unserialize(get_option("wpsp_options"));	
			$options["amazon_apikey"] = $_POST['amazon_apikey'];
			$options["amazon_secret"] = $_POST['amazon_secret'];
			$options["amazon_affid"] = $_POST['amazon_affid'];
			$options["amazon_desc_length"] = $_POST['amazon_desc_length'];
			$options["ebay_campid"] = $_POST['ebay_campid'];
			$options["amazon_site"] = $_POST['amazon_site'];
			$options["post_type"] = $_POST['wpsp_post_type'];		
			$options["post_status"] = $_POST['post_status'];	
			$options["post_comments"] = $_POST['post_comments'];	
			$options["amazon_reviews"] = $_POST['amazon_reviews'];	
			$options["ebay_country"] = $_POST['ebay_country'];	
			$options["ebay_lang"] = $_POST['ebay_lang'];
			$options["amazon_skip_if"] = $_POST['amazon_skip_if'];	
			$options["post_author"] = $_POST['post_author'];	
			$options["ebay_template"] = $_POST['ebay_template'];	
			$options["amazon_search_method"] = $_POST['amazon_search_method'];
			$options["feat_links"] = $_POST['feat_links'];	
			$options["ebay_cache_length"] = $_POST['ebay_cache_length'];	
			$options["wpsp_auto_update"] = $_POST['wpsp_auto_update'];
			$options["amazon_noshortcode"] = $_POST['amazon_noshortcode'];				
			update_option("wpsp_options", serialize($options));	
			$this->populate2($options);		
			
			if($_POST['wpsp_auto_update'] == "Yes") {
				$timestamp = wp_next_scheduled( 'wpsphook' );
				wp_unschedule_event($timestamp, 'wpsphook' );			
				wp_schedule_event( time()+36000, "weekly", "wpsphook" );	
			} else {
				$timestamp = wp_next_scheduled( 'wpsphook' );
				wp_unschedule_event($timestamp, 'wpsphook' );				
			}	
		}			
		
		//$options = unserialize(get_option("wpsp_options"));
		//$options = $this->settings;	
		
		include $this->_pluginpath.'includes/options.php';

    }

    function edit_option($token,$associd,$campid,$amazondesc,$affilate) {

		$_POST['ebay_template'] = str_replace('\"', '"', $_POST['ebay_template']);
		$_POST['amazon_reviews'] = str_replace('\"', '"', $_POST['amazon_reviews']);
		
		$options = unserialize(get_option("wpsp_options"));	
		$options["amazon_apikey"] = $_POST['amazon_apikey'];
		$options["amazon_secret"] = $_POST['amazon_secret'];
		$options["amazon_affid"] = $_POST['amazon_affid'];
		$options["amazon_desc_length"] = $_POST['amazon_desc_length'];
		$options["ebay_campid"] = $_POST['ebay_campid'];
		$options["amazon_site"] = $_POST['amazon_site'];
		$options["post_type"] = $_POST['post_type'];		
		$options["post_status"] = $_POST['post_status'];	
		$options["post_comments"] = $_POST['post_comments'];	
		$options["amazon_reviews"] = $_POST['amazon_reviews'];	
		$options["ebay_country"] = $_POST['ebay_country'];	
		$options["ebay_lang"] = $_POST['ebay_lang'];
		$options["amazon_skip_if"] = $_POST['amazon_skip_if'];	
		$options["post_author"] = $_POST['post_author'];	
		$options["ebay_template"] = $_POST['ebay_template'];	
		$options["amazon_search_method"] = $_POST['amazon_search_method'];
		$options["feat_links"] = $_POST['feat_links'];	
		$options["ebay_cache_length"] = $_POST['ebay_cache_length'];
		$options["wpsp_auto_update"] = $_POST['wpsp_auto_update'];
		$options["amazon_noshortcode"] = $_POST['amazon_noshortcode'];		
		update_option("wpsp_options", serialize($options));
		$this->settings = $options;
		//$this->populate();
		return $options;
    }

    function wpsp_templates_page() {
        switch ($this->_tmpl_action) {
            case "add":
				if($_GET['template']) {$templ = $this->get_template($_GET['template']);}
                include $this->_pluginpath.'includes/add_template_form.php';
                break;
            /*case "edit":
                $tmpl = $this->get_template($this->_tmpl_id);
                include $this->_pluginpath.'includes/edit_template_form.php';
                break;*/
            default :
                break;
        }
    }

    function wpsp_addpages_page() {
		global $wpdb;
		
		$options = unserialize(get_option("wpsp_options"));	
			
		if($_POST['addpages']) {
			$options["add_title_template"] = $_POST['add_title'];
			$options["add_page_template"] = $_POST['page_tmpl'];
			$options["add_ama_template"] = $_POST['amazon_tmpl'];		
			$options["add_ebay_template"] = $_POST['ebay_tmpl'];
			$options["add_ama_si"] = $_POST['ama_searchindex'];	
			$options["add_ebay_cat"] = $_POST['ebay_cat'];		
			update_option("wpsp_options", serialize($options));	
			$this->populate2($options);
	
			$this->create_pages($_POST['add_keys'],$_POST['page_tmpl'],$_POST['amazon_tmpl'],$_POST['add_title'],$_POST['ama_searchindex'],$_POST['ama_browsenode'],$_POST['ebay_cat'],$_POST['post_cat'],0); 
		}
	
        $option_templates ='';
        $option_page_templates='';
        $option_amazon_templates='';
        //$option_ebay_templates = '';
		if(count($this->get_templates('page'))>0) {
			foreach ($this->get_templates('page') as $template) {
				if($this->settings["add_page_template"]==$template['ID']) {$selected = 'selected';} else {$selected = "";}
				$option_page_templates.='<option value="'.$template['ID'].'" '.$selected.'>'.$template['tmpl_title'].'</option>';
			}
		}
		
		$atc = count($this->get_templates('amazon'));	
		if($atc == 1) {$amazon_display = 0;} else {$amazon_display = 1;}
		if($atc > 0) {
			foreach ($this->get_templates('amazon') as $template) {
				if($this->settings["add_ama_template"]==$template['ID']) {$selected = 'selected';} else {$selected = "";}		
				$option_amazon_templates.='<option value="'.$template['ID'].'" '.$selected.'>'.$template['tmpl_title'].'</option>';
			}
		}
		
		/*if(count($this->get_templates('ebay'))>0) {
			foreach ($this->get_templates('ebay') as $template) {
				if($this->settings["add_ebay_template"]==$template['ID']) {$selected = 'selected';} else {$selected = "";}		
				$option_ebay_templates.='<option value="'.$template['ID'].'" '.$selected.'>'.$template['tmpl_title'].'</option>';
			}
		}*/
        include $this->_pluginpath.'includes/add_pages_form.php';
    }
	
    function get_star_rating($rating) {
	
		if($rating>=0 && $rating <= 0.7) {
			$image = '<img src="'.$this->_imagepath.'0-5.png" >';
		}
		if($rating>=1.3 && $rating <= 1.7) {
			$image = '<img src="'.$this->_imagepath.'1-5.png" >';
		}
		if($rating>=2.3 && $rating <= 2.7) {
			$image = '<img src="'.$this->_imagepath.'2-5.png" >';
		}
		if($rating>=3.3 && $rating <= 3.7) {
			$image = '<img src="'.$this->_imagepath.'3-5.png" >';
		}
		if($rating>=4.3 && $rating <= 4.7) {
			$image = '<img src="'.$this->_imagepath.'4-5.png" >';
		}
		if($rating>=0.8 && $rating <= 1.2) {
			$image = '<img src="'.$this->_imagepath.'1.png" >';
		}
		if($rating>=1.8 && $rating <= 2.2) {
			$image = '<img src="'.$this->_imagepath.'2.png" >';
		}
		if($rating>=2.8 && $rating <= 3.2) {
			$image = '<img src="'.$this->_imagepath.'3.png" >';
		}
		if($rating>=3.8 && $rating <= 4.2) {
			$image = '<img src="'.$this->_imagepath.'4.png" >';
		}
		if($rating>=4.8 && $rating <= 5) {
			$image = '<img src="'.$this->_imagepath.'5.png" >';
		}
			 
		return $image;
	}		
	
	function update_pages($num) {
        global $wpdb;		
		
		if($this->settings["wpsp_auto_update"] == "Yes") {		
			$upd_pages = $wpdb->get_results("SELECT ID,pageID FROM wp_wpsp_pages ORDER BY RAND() LIMIT 10");	
			
			foreach($upd_pages as $upd_page) {
				$this->update_page($upd_page->ID,$upd_page->pageID);
			}
		}
	}
	
	function update_page($id,$pageid) {
        global $wpdb;	
		
		$upd_page = $wpdb->get_row("SELECT * FROM wp_wpsp_pages WHERE ID = ".$id);				
		$content = $this->create_pages($upd_page->keyword,$upd_page->pageTmpl,$upd_page->amazonTmpl,$upd_page->pageTitle,"All",0,"",1,1);	

		return $wpdb->query("UPDATE wp_posts SET post_content = '".str_replace("'","''",$content)."' WHERE ID=".$pageid);
	}
			
    function create_pages($keyword_string,$page_tmpl,$amazon_tmpl,$title,$searchindex,$browsenode,$ebay_cat,$post_cat=1,$update=0) {
		global $wpdb;

		if($keyword_string=='') {
			$this->_showmsg = true;
			$this->_message = 'Error: Please enter at least one keyword.';
		} elseif($title=='') {
			$this->_showmsg = true;
			$this->_message = 'Error: Please enter a page title.';		
		} else {		
			$keywords = str_replace("\r", "", $keyword_string);
			$keywords = explode("\n", $keywords); 
			$pages = array();
			foreach ($keywords as $key) {
				$page = array();
				$page["Level"] = 0;
				for($j=0;$j<strlen($key);$j++){
					if($key[$j]=="-" ){ //|| $key[$j]=="#"
						$page["Level"]++;
					}
				}
				
				$page["Title"] = str_replace("-", "", $key);
				
				if($page["Level"] > 0) {
					for($p=$i-1;$p>=0;$p--){
						if($page["Level"]>$pages[$p]["Level"]){
							$page["Parent"] = str_replace("-", "", $keywords[$p]);
							//$page["Parent"] = str_replace("#", "", $page["Parent"]);
							$p=0;
						}
					}
				} else {
					$page["Parent"] = "";			
				}
			  
				array_push($pages, $page);
				$i++;
			}

			$page_template = $this->get_template($page_tmpl);
			$page_tmpl_id = $page_template->ID;
			$raz[0] = "{";
			$raz[1] = "}";

			preg_match_all("/\\".$raz[0]."[^\\".$raz[1]."]+\\".$raz[1]."/s", $page_template->tmpl_code, $matches);
			$page_elements = $matches[0];
			$amcount = 0;
			$ecount = 0;
			foreach($page_elements as $prod){
				if($prod=='{amazon}'){
					$amcount++;
				} elseif($prod=='{ebay}'){
					$ecount++;
				}
			}
			$allcount = $amcount;			
			$added_pages = 0;
			foreach ($pages as $page) {
				// if first character != #
				if($page["Title"][0] == "#") {
				} else {
			   
				$nowtotal = 0;
				$now = 0;
				$ebay_nom=0;
				$nav = "";
				$skipcount = 0;
				
				$amazon_items = array();
				$amazon_content = array();
				$content= $page_template->tmpl_code;
				if($amcount != 0) {
					$added_page = $this->get_amazon_products($page["Title"], $amcount,$searchindex,$browsenode);
				}			
				//if($amcount == 0 && $ecount == 0) {	
				//	$this->_showmsg = true;
				//	$this->_message = 'Error: Page template has to contain at least one {ebay} or {amazon} tag.';				
				//	return false;									
				//} else
				if($content == "" ) {	
					$this->_showmsg = true;
					$this->_message = 'Error: Page template could not be loaded.';				
					return false;				
				} elseif($added_page == false && $allcount != 0) {
					echo '<div id="message" class="updated fade"><p>Error: No products found for this keyword: '.$page["Title"].' ...Skipped!</p></div>';				
				} else { // if(count($added_page)>0 && $amcount != 0)
					foreach ($page_elements as $element) {
						if($element=="{ebay}") {
							$ebay = '[ebayrss id="'.$ebay_nom.'" keys="'.$page["Title"].'" cat="'.$ebay_cat.'"]'."\n";
							$content = preg_replace('/\{ebay\}/', $ebay, $content, 1); 
							$ebay_nom++;	
						} elseif($element=="{amazon}") {
							// wpspamazon
							$skipit = 0;
							$skip = $this->settings["amazon_skip_if"];
							if($skip == "noimg" || $skip == "nox") {if($added_page[$now]["mediumimage"] == "") {$skipit = 1;}}	
							if($skip == "nodesc" || $skip == "nox") {if($added_page[$now]["description"] == "") {$skipit = 1;}}			

							if($skipit == 0) {
							
								$single_amazon = $this->get_template($amazon_tmpl,"amazon");
								$amazon_tmpl_id = $single_amazon->ID;	
								
								
								if($this->settings["amazon_noshortcode"] == "Yes") {

										$text = $added_page[$now]["description"];
										$words = split('[ ]', $text);
										$counttext = $this->settings['amazon_desc_length'];
										if ( count($words) > $counttext ){
											$text = join(' ', array_slice($words, 0, $counttext));
										}

									$link = '<a href="'.$added_page[$now]["url"].'">'.$added_page[$now]["Title"].'</a>';	
									$sa_tmpl = $single_amazon->tmpl_code;
									$sa_tmpl = str_replace("\r\n", "", $sa_tmpl);
									$sa_tmpl = str_replace("\n", "", $sa_tmpl);
									$sa_tmpl = str_replace("\r", "", $sa_tmpl);				
									
									$added_page[$now]["price"] = str_replace("$", "$ ", $added_page[$now]["price"]);
									$added_page[$now]["listprice"] = str_replace("$", "$ ", $added_page[$now]["listprice"]);
									
									// Conditional Tags
									
									preg_match('#\[has_reviews](.*)[\/has_reviews]\]#iU', $sa_tmpl, $matches);
									if ($matches[0] != false) {
										if($added_page[$now]["reviews"][0]["review"] == "" || $added_page[$now]["reviews"][0]["rating"] == "" || !$added_page[$now]["reviews"][0]["rating"]) {
											$sa_tmpl = str_replace($matches[0], "", $sa_tmpl);
										} else {
											$sa_tmpl = str_replace(array("[has_reviews]","[/has_reviews]"), "", $sa_tmpl);					
										}
									}	
									
									preg_match('#\[has_rating](.*)[\/has_rating]\]#iU', $sa_tmpl, $matches);
									if ($matches[0] != false) {
										if($added_page[$now]["totalReviews"] == "" || !$added_page[$now]["totalReviews"] || $added_page[$now]["totalReviews"] == 0) {
											$sa_tmpl = str_replace($matches[0], "", $sa_tmpl);
										} else {
											$sa_tmpl = str_replace(array("[has_rating]","[/has_rating]"), "", $sa_tmpl);					
										}
									}
									
									preg_match('#\[has_listprice](.*)[\/has_listprice]\]#iU', $sa_tmpl, $matches);
									if ($matches[0] != false) {
										if($added_page[$now]["listprice"] == "" || !$added_page[$now]["listprice"]) {
											$sa_tmpl = str_replace($matches[0], "", $sa_tmpl);
										} else {
											$sa_tmpl = str_replace(array("[has_listprice]","[/has_listprice]"), "", $sa_tmpl);					
										}
									}													
									
									// Template Tags
									$sa_tmpl = str_replace("{title}", '<a name="'.$nowtotal.'"></a>'.$added_page[$now]["Title"], $sa_tmpl);
									$sa_tmpl = str_replace("{smallthumb}", $added_page[$now]["smallimage"], $sa_tmpl);
									$sa_tmpl = str_replace("{mediumthumb}", $added_page[$now]["mediumimage"], $sa_tmpl);
									$sa_tmpl = str_replace("{largethumb}", $added_page[$now]["largeimage"], $sa_tmpl);	
									$thumbnail = '<a href="'.$added_page[$now]["url"].'" rel="nofollow"><img style="float:left;margin: 0 20px 10px 0;" src="'.$added_page[$now]["mediumimage"].'" /></a>';	
									$sa_tmpl = str_replace("{thumbnail}", $thumbnail, $sa_tmpl);				
									$sa_tmpl = str_replace("{description}",$text , $sa_tmpl);
									$sa_tmpl = str_replace("{features}", $added_page[$now]["features"], $sa_tmpl);
									$sa_tmpl = str_replace("{buynow}", '<a href="'.$added_page[$now]["url"].'" rel="nofollow"><img src="'.$this->_imagepath.'buynow-small.gif" /></a>', $sa_tmpl);		
									$sa_tmpl = str_replace("{buynow-big}", '<a href="'.$added_page[$now]["url"].'" rel="nofollow"><img src="'.$this->_imagepath.'buynow-big.gif" /></a>', $sa_tmpl);	
									$sa_tmpl = str_replace("{listprice}", $added_page[$now]["listprice"], $sa_tmpl);				
									$sa_tmpl = str_replace("{price}", $added_page[$now]["price"], $sa_tmpl);
									$sa_tmpl = str_replace("{url}", $added_page[$now]["url"], $sa_tmpl);
									$sa_tmpl = str_replace("{link}", $link, $sa_tmpl);
									$sa_tmpl = str_replace("{totalReviews}", $added_page[$now]["totalReviews"], $sa_tmpl);
					
									$sa_tmpl = str_replace("{reviews-url}", $added_page[$now]["reviewsurl"], $sa_tmpl);
									$sa_tmpl = str_replace("{reviews-iframe}", '[wpspamareviews asin="'.$added_page[$now]["ASIN"].'"]', $sa_tmpl);
									$sa_tmpl = str_replace("{reviews-noiframe}", $added_page[$now]["reviewsnoiframe"], $sa_tmpl);
		
									// reviews
									preg_match('#\{reviews(.*)\}#iU', $sa_tmpl, $rmatches);
									if ($rmatches[0] == false) {			
									} else {
										$sa_tmpl = str_replace($rmatches[0],'[wpspamareviews asin="'.$added_page[$now]["ASIN"].'"]' , $sa_tmpl);				
									}
							
									if (strpos($sa_tmpl, "{rating}") != false) {			 
										$image = $this->get_star_rating($added_page[$now]["averageRating"]);
										$sa_tmpl = str_replace("{rating}",$image,$sa_tmpl);
									}

								} else {
									$sa_tmpl = '[wpspamazon asin="'.$added_page[$now]["ASIN"].'" template="'.$amazon_tmpl_id.'" num="'.$nowtotal.'"]';
								}
								
								$nav .= '<li><a href="#'.$nowtotal.'">'.$added_page[$now]["Title"].'</a></li>';
							
							} else {
								$sa_tmpl = "";	
								$skipcount++;
							}
						
							$content = preg_replace('/\{amazon\}/', $sa_tmpl, $content, 1); 
							$now++; $nowtotal++;		
						}     
					}
				   
					$content = str_replace('{amazonsearch}', "http://www.amazon.com/gp/redirect.html?ie=UTF8&location=http%3A%2F%2Fwww.amazon.com%2Fs%3Fie%3DUTF8%26x%3D0%26ref_%3Dnb%255Fsb%255Fnoss%26y%3D0%26field-keywords%3D".urlencode($page['Title'])."%26url%3Dsearch-alias%253Daps&tag=".$this->_affilateID."&linkCode=ur2&camp=1789&creative=390957", $content); 
					$content = str_replace('{keyword}', $page['Title'], $content);
					$content = str_replace('{amazon}', "", $content);
					$content = str_replace('{ebay}', "", $content);
					$content = str_replace('{nav}', "<ul>".$nav."</ul>", $content);	   
					$title_page = str_replace('{keyword}', $page['Title'], $title);
			   
					if($update == 1) {
						return $content;			   
					} elseif(get_page_by_title($title_page) == NULL) {
						if($skipcount == $amcount && $amcount > 0) {
							$this->_showmsg = true;
							$this->_message = 'All Amazon products for keyword '.$page["Title"].' were skipped because of your "Skip Products If" setting and thus the page was not created.';							
						} else {
					
							remove_filter('content_save_pre', 'wp_filter_post_kses');
							if($page["Parent"]!="" && $page["Parent"][0] == "#"){				
								$searchpage = str_replace("#", "", $page["Parent"]);
								$page_parent = get_page_by_title($searchpage);	
							} elseif($page["Parent"]!="" && $page["Parent"][0] != "#"){						
								$page_parent = get_page_by_title(str_replace('{keyword}', $page['Parent'], $title));
							} else {
								$page_parent = "";
							}		
							if($page_parent->ID != "") {$parent = $page_parent->ID;} else {$parent = 0;}
							$post_author= $this->settings["post_author"];
							$post_parent = $parent;		
							$post_type = $this->settings["post_type"];
							$post_status = $this->settings["post_status"];
							$comment_status = $this->settings["post_comments"];
							$post_date= current_time('mysql');
							$post_date_gmt= current_time('mysql', 1);
							$post_category = array($post_cat);
							$post_content = $content;	
							
							$ft = $this->settings["feat_links"];
							if($ft == 1) {
								$post_content .= '<p style="font-size: 90%;">Powered by <a href="http://wpshoppingpages.com/" title="WordPress affiliate plugin">WP Shopping Pages</a></p>';
							}
							
							$post_title = ucwords(str_replace('{keyword}', $page['Title'], $title));
							$tags_input = "";
							
							$menu_order = 0;
							
							$post_data = compact('post_content','post_title','post_type', 'post_parent','menu_order','post_date','post_date_gmt','post_author','post_category', 'post_status', 'tags_input', 'comment_status');		
						
							$post_data = add_magic_quotes($post_data);
							$id = wp_insert_post($post_data);
							if ( is_wp_error( $id ) )
							echo "\n" . $id->get_error_message();						

							if($id) {
								$wpdb->query("INSERT INTO `wp_wpsp_pages` ( `ID` , `pageID` , `keyword`, `pageTmpl`, `pageTitle`, `amazonTmpl`, `ebayTmpl` ) VALUES (NULL , '".$id."', '".$page['Title']."' , '".$page_tmpl_id."','".$title."', '".$amazon_tmpl_id."', '".$ebay_tmpl."' );");
								$added_pages++;
							}
						}
					}
				}
				}
		    }
			if($added_pages>0) {
				$this->_showmsg = true;
				$this->_message = $added_pages.' pages added successfully';
                if((count($keywords)-$added_pages)>0){
                    $this->_message .= " and ".(count($keywords)-$added_pages).' pages skipped (for being duplicates or because no products were found).';
                }				
			} else {
				$this->_showmsg = true;
				if($this->_message == "") {
					$this->_message = 'Error: No pages could be added because all were duplicates.';
				}
			}
		}
    }

    function delete_page($id,$pageid,$kw){
        global $wpdb;
        $wpdb->query("DELETE FROM `wp_wpsp_pages` WHERE ID = ".$id."");
        //$wpdb->query("UPDATE `wp_posts` SET `post_type` = 'post' WHERE `ID` =".$pageid." LIMIT 1 ;");
        $result = $wpdb->query("DELETE FROM ".$wpdb->posts." WHERE `ID` =".$pageid." LIMIT 1 ;");
		//$result = wp_delete_post( $pageid, true );
		//echo "DELETE FROM `wp_posts` WHERE `ID` =".$pageid." LIMIT 1 ;";
		if($result != false) {
			$wpdb->query("DELETE FROM `wp_wpsp_ebaycache` WHERE ebay_Key = '".$kw."'");		
			$wpdb->query("UPDATE wp_posts SET post_parent='0'  WHERE post_parent = ".$pageid);
		}
		return $result;
    }

    function add_template($tmpl_title,$tmpl_code,$tmpl_type) {
        global $wpdb;
        return $wpdb->query("INSERT INTO `wp_wpsp_templates` ( `ID` , `tmpl_title` , `tmpl_code`, `tmpl_type` ) VALUES ('', '".$tmpl_title."', '".$tmpl_code."', '".$tmpl_type."');");
    }

    function update_template($id,$tmpl_title,$tmpl_code,$tmpl_type) {
        global $wpdb;
        return $wpdb->query("UPDATE `wp_wpsp_templates` SET `tmpl_title` = '".$tmpl_title."',`tmpl_code` = '".$tmpl_code."',`tmpl_type` = '".$tmpl_type."' WHERE `ID` =".$id." LIMIT 1 ;");
    }

    function delete_template($id) {
        global $wpdb;
        return $wpdb->query("DELETE FROM `wp_wpsp_templates` WHERE `ID` =".$id." LIMIT 1 ;");
    }

    function get_templates($type=NULL) {
        global $wpdb;
        if($type!=NULL) {
            $query = "SELECT * FROM  `wp_wpsp_templates` WHERE tmpl_type = '".$type."' ORDER BY `ID` DESC";
        } else {
			$query = 'SELECT * FROM  `wp_wpsp_templates` ORDER BY `ID` DESC';
        }
        
        return $wpdb->get_results($query, ARRAY_A);
    }

    function get_pages() {
        global $wpdb; 
        $query = 'SELECT * FROM `wp_wpsp_pages` ORDER BY `ID` DESC';

        return $wpdb->get_results($query, ARRAY_A);
    }

    function get_template($id, $type = "page") {
        global $wpdb;
		
		$rnd = "";
		if($id == "randomp") {$rnd = "page";} elseif($id == "randoma") {$rnd = "amazon";}

		if($rnd != "") {
			$query = 'SELECT * FROM `wp_wpsp_templates` WHERE tmpl_type = "'.$type.'" ORDER BY RAND() LIMIT 1';		
		} else {
			$query = 'SELECT * FROM `wp_wpsp_templates` WHERE ID = '.$id;
        }
		
        return $wpdb->get_row($query);
    }
	
	function wpsp_aws_request($region, $params, $public_key, $private_key) {
		$method = "GET";
		$host = "ecs.amazonaws.".$region;
		$uri = "/onca/xml";

		$params["Service"] = "AWSECommerceService";
		$params["AWSAccessKeyId"] = $public_key;
		
		$t = time() + 10000;
		$params["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z",$t);	
		$params["Version"] = "2010-09-01";
		ksort($params);
		
		$canonicalized_query = array();
		foreach ($params as $param=>$value) {
			$param = str_replace("%7E", "~", rawurlencode($param));
			$value = str_replace("%7E", "~", rawurlencode($value));
			$canonicalized_query[] = $param."=".$value;
		}
		$canonicalized_query = implode("&", $canonicalized_query);
		$string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;   
		$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $private_key, True));  
		$signature = str_replace("%7E", "~", rawurlencode($signature));  
		$request = "http://".$host.$uri."?".$canonicalized_query."&Signature=".$signature; 
			
		if ( function_exists('curl_init') ) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $request);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			$response = curl_exec($ch);
			if (!$response) {
				echo "<div class=\"updated\"><p>cURL error:" . curl_error($ch) . " (Number " . curl_errno($ch).")</p></div>";
			}		
			curl_close($ch);
		} else { 				
			$response = @file_get_contents($request);
		}
		
		if ($response === False) {
			return False;
		} else {
			$pxml = simplexml_load_string($response);
			if ($pxml === False) {
				return False;
			} else {
				return $pxml;
			}
		}
	} 	
  
	function get_amazon_products($keywords,$count,$searchindex,$browsenode) {
		global $wpdb;
		
		$public_key = $this->_awspublic;
		$private_key = $this->_awssecret;
		$affid = $this->_affilateID;
		$added_post = 0;
		$page = 1;
		$return = array();
		$site = $this->settings["amazon_site"];		
		if($this->settings["amazon_search_method"] == "exact") {
			$keywords = '"' .$keywords. '"';
		}		
		$browsenode = 0;
		
		while($added_post < $count){  // $added_post<$count

			if($page>1){
			//	$count = $count - 10; 
			}
		//echo "COUNT: " .$count."<br/>";	
	
			if($searchindex == "All") {
			$pxml = $this->wpsp_aws_request($site, array(
			"Operation"=>"ItemSearch",
			"AssociateTag"=>$affid,
			"Keywords"=>$keywords,
			"SearchIndex"=>$searchindex,
			"ItemPage"=>$page,
			"ReviewSort"=>"-HelpfulVotes",
			"TruncateReviewsAt"=>"5000",
			"IncludeReviewsSummary"=>"False",			
			"ResponseGroup"=>"Large"
			), $public_key, $private_key);	
			} elseif($browsenode == 0) {
			$pxml = $this->wpsp_aws_request($site, array(
			"Operation"=>"ItemSearch",
			"AssociateTag"=>$affid,
			"Keywords"=>$keywords,
			"SearchIndex"=>$searchindex,
			"ItemPage"=>$page,
			"ReviewSort"=>"-HelpfulVotes",
			"TruncateReviewsAt"=>"5000",
			"IncludeReviewsSummary"=>"False",			
			"ResponseGroup"=>"Large"
			), $public_key, $private_key);				
			} else {
			$pxml = $this->wpsp_aws_request($site, array(
			"Operation"=>"ItemSearch",
			"AssociateTag"=>$affid,
			"Keywords"=>$keywords,
			"SearchIndex"=>$searchindex,
			"BrowseNode"=>$browsenode,
			"ItemPage"=>$page,
			"ReviewSort"=>"-HelpfulVotes",
			"TruncateReviewsAt"=>"5000",
			"IncludeReviewsSummary"=>"False",			
			"ResponseGroup"=>"Large"
			), $public_key, $private_key);					
			}
			//echo "<pre>";print_r($pxml);echo "</pre>";
			if($count<=10){
				$count_this = $count;
			} else{
				$count_this = 10;
			}
			$i=0;
			
			if (!$pxml) {
				$this->_showmsg = true;
				$this->_message = 'Error: Amazon API request did not work.';	
				return false;			
			}
			
			if (isset($pxml->Error)) {
				$this->_showmsg = true;
				$this->_message = '<p>There was a problem with your Amazon API request. This is the error Amazon returned:</p>
				<p><i><b>'.$pxml->Error->Code.':</b> '.$pxml->Error->Message.'</i></p>';	
				return false;		
			}				
			
			if (!$pxml->Items->Item && !empty($return)) {
				return $return;
			} elseif (!$pxml->Items->Item && empty($return)) {
				$this->_showmsg = true;
				$this->_message = 'Error: No products found.';	
				return false;			
			}			

			foreach($pxml->Items->Item as $item) {	
				if ($i<$count_this) {
					$desc = "";					
					if (isset($item->EditorialReviews->EditorialReview)) {

						foreach($item->EditorialReviews->EditorialReview as $descs) {
							$desc .= $descs->Content;
						}		
					}				
					$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
								   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
								   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
					);
					$desc = preg_replace($search, '', $desc); 
					
					$features = "";
					if (isset($item->ItemAttributes->Feature)) {	
						$features = "<ul>";
						foreach($item->ItemAttributes->Feature as $feature) {
							$posx = strpos($feature, "href=");
							if ($posx === false) {
								$features .= "<li>".$feature."</li>";		
							}
						}	
						$features .= "</ul>";				
					}

					$product_review = $item->CustomerReviews->IFrameURL;
					$reviewsiframe = '<iframe style="margin-top: 10px;" src="'.$product_review.'" width="100%" height="450px"><p>Your browser does not support iframes.</p></iframe>';
					$revcontent = @file_get_contents($product_review); 
					if (preg_match('~<body[^>]*>(.*?)</body>~si', $revcontent, $body)) { $reviewsnoiframe = str_replace('class="crVotingButtons">', "", $body[1]); } else {$reviewsnoiframe = "";} 
					
						$single = array();
						$single["ASIN"] = $item->ASIN;
						$single["Title"] = $item->ItemAttributes->Title;
						$single["smallimage"] = $item->SmallImage->URL;
						$single["mediumimage"] = $item->MediumImage->URL;
						$single["largeimage"] = $item->LargeImage->URL;
						$single["description"] =  strip_tags($desc,'<br>');
						$single["features"] =  $features;
						$single["reviews"] =  "";	
						$single["reviewsurl"] =  $product_review;
						$single["reviewsiframe"] =  $reviewsiframe;
						$single["reviewsnoiframe"] = $reviewsnoiframe;
						$single["price"] = $item->OfferSummary->LowestNewPrice->FormattedPrice;	
						$single["listprice"] = $item->ItemAttributes->ListPrice->FormattedPrice;
						$single["url"] = $item->DetailPageURL;
						$single["averageRating"] = $item->CustomerReviews->AverageRating;		
						$single["totalReviews"] = $item->CustomerReviews->TotalReviews;			
						$added_post++;
						array_push($return, $single);			
						$i++;
				}
			}
			$page++;
		}
		return $return;
	}
			
    function ebayrss_handler($atts, $content = null) {
        global $wpdb;
		
		//remove_filter('the_content', 'wpautop');
        $cache_ebay = $wpdb->get_results("SELECT * FROM wp_wpsp_ebaycache WHERE adid = '".$atts['id']."' AND ebay_Key = '".$atts['keys']."' ",ARRAY_A);

        if(count($cache_ebay)==0) {
			$lang = $this->settings["ebay_lang"];
			$country = $this->settings["ebay_country"];
			$ebaycat = $atts["cat"];
			if (empty($ebaycat) || $ebaycat == "all"){$ebaycat="-1";}		
			$arrFeeds = array();

			require_once ( ABSPATH . WPINC .  '/rss.php' );	
			/*$therss = fetch_rss("http://rss.api.ebay.com/ws/rssapi?FeedName=SearchResults&siteId=$country&language=$lang&output=RSS20&sacat=$ebaycat&fcl=3&satitle=".str_replace(" ","+", ($atts['keys']))."&sacur=0&frpp=100&afepn=" . urlencode($this->_campID) . "&dfsp=32&sabfmts=0&salic=$country&ftrt=1&ftrv=1&customid=" .str_replace(" ","+", ($atts['keys']))."&fss=0&saobfmts=exsif&catref=C5&saaff=afepn&from=R6&saslop=1");

			if($therss->items != "" && $therss->items != null) {
				foreach ($therss->items as $item) { 
					$itemRSS = array (
						'title' => $item['title'],
						'desc' => $item['description'],
						'link' => $item['link'],
						'date' => $item['pubDate']
						);
					array_push($arrFeeds, $itemRSS);
				}
			}*/
			
			if(empty($sortorder)) {$sortorder = "BestMatch";}		
			$country = $this->settings["ebay_country"];		
			if($country == 0) {$program = 1;}
			elseif($country == 205) {$program = 2;}
			elseif($country == 16) {$program = 3;}
			elseif($country == 15) {$program = 4;}
			elseif($country == 23) {$program = 5;}
			elseif($country == 2) {$program = 7;}
			elseif($country == 71) {$program = 10;}
			elseif($country == 77) {$program = 11;}
			elseif($country == 101) {$program = 12;}
			elseif($country == 186) {$program = 13;}
			elseif($country == 193) {$program = 14;}
			elseif($country == 3) {$program = 15;}
			elseif($country == 146) {$program = 16;}
			else {$program = $country;}				
			$rssurl= "http://rest.ebay.com/epn/v1/find/item.rss?keyword=" . str_replace(" ","+", ($atts['keys']))."&campaignid=" . urlencode($this->_campID) . "&sortOrder=" . $sortorder."&programid=".$program."";	
			$therss = fetch_rss($rssurl);
					
			if ($therss){		
				if($therss->items == "" || $therss->items == null) {
				} else {
					foreach ($therss->items as $item) { 
						$itemRSS = array (
							'title' => $item['title'],
							'desc' => $item['description'],
							'link' => $item['link'],
							'date' => $item['pubDate']
							);
						array_push($arrFeeds, $itemRSS);			
					}			
				}		
			}			

			$number = $atts['id'];
			$ebcontent = $this->settings['ebay_template'];
			
				preg_match_all('#<td>(.*)<\/td>#iU', $arrFeeds[$number]['desc'], $matches);
				$thumbnail = $matches[0][0];	
				$description = $matches[0][1];
				
				preg_match('#<strong>(.*)<\/strong>#iU', $description, $pricem);	
				$price = $pricem[1];		
				
			$ebcontent = str_replace("{thumbnail}", $thumbnail, $ebcontent);
			$ebcontent = str_replace("{price}", $price, $ebcontent);			
			$ebcontent = str_replace('{title}', $arrFeeds[$number]['title'], $ebcontent);
			$ebcontent = str_replace("{descriptiontable}", $arrFeeds[$number]['desc'], $ebcontent);			
			$ebcontent = str_replace("{description}", $description, $ebcontent);
			$ebcontent = str_replace("{url}", $arrFeeds[$number]['link'], $ebcontent);
			if($arrFeeds[$number]['title'] != "") {
				$wpdb->query("INSERT INTO wp_wpsp_ebaycache (adid,ebay_Key,ebay_Content, views_count) VALUES ('".$number."','".str_replace("'","''",$atts['keys'])."','".str_replace("'","''",$ebcontent)."','0')");
			} else {$ebcontent = "";}
		} else {
            $reload_pageviews = $this->settings["ebay_cache_length"];
            $ebcontent = $cache_ebay[0]['ebay_Content'];
            $count = $cache_ebay[0]['views_count']+1;
            if($count<=$reload_pageviews) {
				$wpdb->query("UPDATE wp_wpsp_ebaycache SET views_count='".$count."' WHERE adid = '".$atts['id']."' AND ebay_Key = '".$atts['keys']."'  ");
            } else {
                $wpdb->query("DELETE FROM wp_wpsp_ebaycache WHERE adid = '".$atts['id']."' AND ebay_Key = '".$atts['keys']."'  ");
            }
        }	
		
		$content = $ebcontent;
        return $content;

    }
	
	function wpsp_ama_handler($atts, $content = null) {

		$public_key = $this->_awspublic;
		$private_key = $this->_awssecret;
		$affid = $this->_affilateID;
		$return = array();
		$locale = $this->settings["amazon_site"];			
		if($locale == "us") {$locale = "com";}
		if($locale == "uk") {$locale = "co.uk";}	
	
		$pxml = $this->wpsp_aws_request($locale, array(
		"Operation"=>"ItemLookup",
		"ItemId"=>$atts["asin"],
		"IncludeReviewsSummary"=>"False",
		"AssociateTag"=>$affid,
		"TruncateReviewsAt"=>"5000",
		"ResponseGroup"=>"Large"
		), $public_key, $private_key);
		//echo "<pre>";print_r($pxml);echo "</pre>";
		if ($pxml === False) {
			return $content;
		} else {
			$added_page = array();
			if($pxml->Items->Item) {
				foreach($pxml->Items->Item as $item) {	
					$desc = "";					
					if (isset($item->EditorialReviews->EditorialReview)) {

						foreach($item->EditorialReviews->EditorialReview as $descs) {
							$desc .= $descs->Content;
						}		
					}				
					$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
								   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
								   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
					);
					$desc = preg_replace($search, '', $desc); 
					
					$features = "";
					if (isset($item->ItemAttributes->Feature)) {	
						$features = "<ul>";
						foreach($item->ItemAttributes->Feature as $feature) {
							$posx = strpos($feature, "href=");
							if ($posx === false) {
								$features .= "<li>".$feature."</li>";		
							}
						}	
						$features .= "</ul>";				
					}

					$product_review = $item->CustomerReviews->IFrameURL;
					$reviewsiframe = '<iframe style="margin-top: 10px;" src="'.$product_review.'" width="100%" height="450px"><p>Your browser does not support iframes.</p></iframe>';
					$revcontent = @file_get_contents($product_review); 
					if (preg_match('~<body[^>]*>(.*?)</body>~si', $revcontent, $body)) { $reviewsnoiframe = str_replace('class="crVotingButtons">', "", $body[1]); } else {$reviewsnoiframe = "";} 
					
						$single = array();
						$single["ASIN"] = $item->ASIN;
						$single["Title"] = $item->ItemAttributes->Title;
						$single["smallimage"] = $item->SmallImage->URL;
						$single["mediumimage"] = $item->MediumImage->URL;
						$single["largeimage"] = $item->LargeImage->URL;
						$single["description"] =  strip_tags($desc,'<br>');
						$single["features"] =  $features;
						$single["reviews"] =  "";	
						$single["reviewsurl"] =  $product_review;
						$single["reviewsiframe"] =  $reviewsiframe;
						$single["reviewsnoiframe"] = $reviewsnoiframe;
						$single["price"] = $item->OfferSummary->LowestNewPrice->FormattedPrice;	
						$single["listprice"] = $item->ItemAttributes->ListPrice->FormattedPrice;
						$single["url"] = $item->DetailPageURL;
						$single["averageRating"] = $item->CustomerReviews->AverageRating;		
						$single["totalReviews"] = $item->CustomerReviews->TotalReviews;			
						array_push($added_page, $single);			
				}		
				
					$now = 0;
					$text = $added_page[$now]["description"];
					$words = split('[ ]', $text);
					$counttext = $this->settings['amazon_desc_length'];
					if ( count($words) > $counttext ){
						$text = join(' ', array_slice($words, 0, $counttext));
					}
				$single_amazon = $this->get_template($atts["template"],"amazon");
				$link = '<a href="'.$added_page[$now]["url"].'">'.$added_page[$now]["Title"].'</a>';	
				$sa_tmpl = $single_amazon->tmpl_code;
				$sa_tmpl = str_replace("\r\n", "", $sa_tmpl);
				$sa_tmpl = str_replace("\n", "", $sa_tmpl);
				$sa_tmpl = str_replace("\r", "", $sa_tmpl);				
				
				$added_page[$now]["price"] = str_replace("$", "$ ", $added_page[$now]["price"]);
				$added_page[$now]["listprice"] = str_replace("$", "$ ", $added_page[$now]["listprice"]);
				
				// Conditional Tags
				
				preg_match('#\[has_reviews](.*)[\/has_reviews]\]#iU', $sa_tmpl, $matches);
				if ($matches[0] != false) {
					if($added_page[$now]["totalReviews"] == "" || !$added_page[$now]["totalReviews"] || $added_page[$now]["totalReviews"] == 0) {
						$sa_tmpl = str_replace($matches[0], "", $sa_tmpl);
					} else {
						$sa_tmpl = str_replace(array("[has_reviews]","[/has_reviews]"), "", $sa_tmpl);					
					}
				}	
				
				preg_match('#\[has_rating](.*)[\/has_rating]\]#iU', $sa_tmpl, $matches);
				if ($matches[0] != false) {
					if($added_page[$now]["totalReviews"] == "" || !$added_page[$now]["totalReviews"] || $added_page[$now]["totalReviews"] == 0) {
						$sa_tmpl = str_replace($matches[0], "", $sa_tmpl);
					} else {
						$sa_tmpl = str_replace(array("[has_rating]","[/has_rating]"), "", $sa_tmpl);					
					}
				}
				
				preg_match('#\[has_listprice](.*)[\/has_listprice]\]#iU', $sa_tmpl, $matches);
				if ($matches[0] != false) {
					if($added_page[$now]["listprice"] == "" || !$added_page[$now]["listprice"]) {
						$sa_tmpl = str_replace($matches[0], "", $sa_tmpl);
					} else {
						$sa_tmpl = str_replace(array("[has_listprice]","[/has_listprice]"), "", $sa_tmpl);					
					}
				}	

				if(empty($added_page[$now]["price"]) || $added_page[$now]["price"] == "Too low to display") {
					$added_page[$now]["price"] = $added_page[$now]["listprice"];
				}
				
				// Template Tags
				$sa_tmpl = str_replace("{title}", '<a name="'.$atts["num"].'"></a>'.$added_page[$now]["Title"], $sa_tmpl);
				$sa_tmpl = str_replace("{smallthumb}", $added_page[$now]["smallimage"], $sa_tmpl);
				$sa_tmpl = str_replace("{mediumthumb}", $added_page[$now]["mediumimage"], $sa_tmpl);
				$sa_tmpl = str_replace("{largethumb}", $added_page[$now]["largeimage"], $sa_tmpl);	
				$thumbnail = '<a href="'.$added_page[$now]["url"].'" rel="nofollow"><img style="float:left;margin: 0 20px 10px 0;" src="'.$added_page[$now]["mediumimage"].'" /></a>';	
				$sa_tmpl = str_replace("{thumbnail}", $thumbnail, $sa_tmpl);				
				$sa_tmpl = str_replace("{description}",$text , $sa_tmpl);
				$sa_tmpl = str_replace("{features}", $added_page[$now]["features"], $sa_tmpl);
				$sa_tmpl = str_replace("{buynow}", '<a href="'.$added_page[$now]["url"].'" rel="nofollow"><img src="'.$this->_imagepath.'buynow-small.gif" /></a>', $sa_tmpl);		
				$sa_tmpl = str_replace("{buynow-big}", '<a href="'.$added_page[$now]["url"].'" rel="nofollow"><img src="'.$this->_imagepath.'buynow-big.gif" /></a>', $sa_tmpl);	
				$sa_tmpl = str_replace("{listprice}", $added_page[$now]["listprice"], $sa_tmpl);				
				$sa_tmpl = str_replace("{price}", $added_page[$now]["price"], $sa_tmpl);
				$sa_tmpl = str_replace("{url}", $added_page[$now]["url"], $sa_tmpl);
				$sa_tmpl = str_replace("{link}", $link, $sa_tmpl);
				$sa_tmpl = str_replace("{totalReviews}", $added_page[$now]["totalReviews"], $sa_tmpl);

				$sa_tmpl = str_replace("{reviews-url}", $added_page[$now]["reviewsurl"], $sa_tmpl);
				$sa_tmpl = str_replace("{reviews-iframe}", $reviewsiframe, $sa_tmpl);
				$sa_tmpl = str_replace("{reviews-noiframe}", $added_page[$now]["reviewsnoiframe"], $sa_tmpl);

				// reviews
				preg_match('#\{reviews(.*)\}#iU', $sa_tmpl, $rmatches);
				if ($rmatches[0] == false) {			
				} else {
					$sa_tmpl = str_replace($rmatches[0],$reviewsiframe , $sa_tmpl);				
				}
		
				if (strpos($sa_tmpl, "{rating}") != false) {			 
					$image = $this->get_star_rating($added_page[$now]["averageRating"]);
					$sa_tmpl = str_replace("{rating}",$image,$sa_tmpl);
				}		
		
				return $sa_tmpl;
			} else {
				return $content;	
			}
		}
	}	

	function wpsp_ama_reviews($atts, $content = null) {

		$public_key = $this->_awspublic;
		$private_key = $this->_awssecret;
		$affid = $this->_affilateID;
		$return = array();
		$locale = $this->settings["amazon_site"];			
		if($locale == "us") {$locale = "com";}
		if($locale == "uk") {$locale = "co.uk";}	
	
		$pxml = $this->wpsp_aws_request($locale, array(
		"Operation"=>"ItemLookup",
		"ItemId"=>$atts["asin"],
		"IncludeReviewsSummary"=>"False",
		"AssociateTag"=>$affid,
		"TruncateReviewsAt"=>"5000",
		"ResponseGroup"=>"Reviews"
		), $public_key, $private_key);
		//echo "<pre>";print_r($pxml);echo "</pre>";
		if ($pxml === False) {
			return false;
		} else {
			if($pxml->Items->Item->CustomerReviews->IFrameURL) {
			
				$product_review = $pxml->Items->Item->CustomerReviews->IFrameURL;
				$reviewsiframe = '<iframe style="margin-top: 10px;" src="'.$product_review.'" width="100%" height="450px"><p>Your browser does not support iframes.</p></iframe>';		
		
				return $reviewsiframe;
			} else {
				return $content;	
			}
		}
	}
}

add_filter('cron_schedules', 'wpsp_add_weekly');
function wpsp_add_weekly( $schedules ) {
    $schedules['weekly'] = array(
        'interval' => 604800, //that's how many seconds in a week, for the unix timestamp
        'display' => __('Once Weekly')
    );
    return $schedules;
}
?>