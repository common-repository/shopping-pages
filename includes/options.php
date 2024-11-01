<div class="wrap">
    <h2>Settings</h2>
     <?php if($this->_showmsg) { ?>
        <div id="message" class="updated fade"><p><?php echo $this->_message; ?></p></div>
        <?php } ?>
    <form action="" method="post">
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><label for="secret-key">Amazon Affiliate ID</label></th>
            <td>
                <input name="amazon_affid" type="text" id="amazon-affilated" value="<?php echo $options["amazon_affid"]; ?>" class="regular-text" /><span class="description"></span><br>
            </td>
        </tr>	
        <tr valign="top">
            <th scope="row"><label for="amazon-key">Amazon Access Key ID</label></th>
            <td>
                <input name="amazon_apikey" type="text" id="amazon-key" value="<?php echo $options["amazon_apikey"]; ?>" class="regular-text" /><span class="description"></span><br>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="secret-key">Amazon Secret Access Key</label></th>
            <td>
                <input name="amazon_secret" type="text" id="secret-key" value="<?php echo $options["amazon_secret"]; ?>" class="regular-text" /><span class="description"></span><br>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="amazon_site">Amazon Website</label></th>
            <td>
				<select name="amazon_site" id="amazon_site">
					<option value="com" <?php if ($options["amazon_site"]=='com'){echo "selected";}?>>Amazon.com</option>
					<option value="co.uk" <?php if ($options["amazon_site"]=='co.uk'){echo "selected";}?>>Amazon.co.uk</option>
					<option value="de" <?php if ($options["amazon_site"]=='de'){echo "selected";}?>>Amazon.de</option>
					<option value="ca" <?php if ($options["amazon_site"]=='ca'){echo "selected";}?>>Amazon.ca</option>
					<option value="jp" <?php if ($options["amazon_site"]=='jp'){echo "selected";}?>>Amazon.jp</option>
					<option value="fr" <?php if ($options["amazon_site"]=='fr'){echo "selected";}?>>Amazon.fr</option>		
					<option value="es" <?php if ($options['amazon_site']=='es'){echo "selected";}?>>Amazon.es</option>
				</select>	
				</td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="amazon_search_method">Amazon Search Method</label></th>
            <td>
				<select name="amazon_search_method" id="amazon_search_method">
					<option value="exact" <?php if ($options["amazon_search_method"]=="exact"){echo "selected";}?>>Exact Match</option>
					<option value="broad" <?php if ($options["amazon_search_method"]=="broad"){echo "selected";}?>>Broad Match</option>
				</select>
				</td>
        </tr>		
        <tr valign="top">
            <th scope="row"><label for="amazon_desc_length">Amazon Description Length</label></th>
            <td>
                <input name="amazon_desc_length" type="text" id="amazon_desc_length" value="<?php echo $options["amazon_desc_length"]; ?>" class="small-text" /> words
            </td>
        </tr>
		
        <tr valign="top">
            <th scope="row"><label for="amazon_noshortcode">Amazon Embed Style</label></th>
            <td>
				<input name="amazon_noshortcode" type="checkbox" id="amazon_noshortcode" value="Yes" <?php if ($options['amazon_noshortcode']=='Yes') {echo "checked";} ?>/> <?php _e("Embed products on pages instead of using a shortcode to retreive information with every page load (slightly faster loading time but not updated frequently).","wpshoppingpages"); ?>
            </td>
        </tr>	
		
       <!-- <tr valign="top">
            <th scope="row"><label for="amazon_reviews">Amazon Reviews Template</label></th>
            <td>
				<textarea name="amazon_reviews" rows="5" cols="37"><?php echo $options["amazon_reviews"];?></textarea>	
            </td>
        </tr>-->
        <tr valign="top">
            <th scope="row"><label for="amazon_skip_if">Skip Products If</label></th>
            <td>
				<select name="amazon_skip_if" id="amazon_skip_if">
					<option value="" <?php if ($options["amazon_skip_if"]==""){echo "selected";}?>>Don't skip</option>
					<option value="nodesc" <?php if ($options["amazon_skip_if"]=="nodesc"){echo "selected";}?>>No description found</option>
					<option value="noimg" <?php if ($options["amazon_skip_if"]=="noimg"){echo "selected";}?>>No thumbnail image found</option>
					<option value="nox" <?php if ($options["amazon_skip_if"]=="nox"){echo "selected";}?>>No description OR no thumbnail</option>
				</select>
			</td>
        </tr>			
        <tr valign="top">
            <th scope="row"><label for="camp-id">eBay Camp ID</label></th>
            <td>
                <input name="ebay_campid" type="text" id="camp-id"  value="<?php echo $options["ebay_campid"]; ?>" class="regular-text" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="ebay_lang">eBay Language</label></th>
            <td>
				<select name="ebay_lang" id="ebay_lang">
					<option value="en-US" <?php if($options["ebay_lang"]=="en-US"){_e('selected');}?>>English</option>
					<option value="de" <?php if($options["ebay_lang"]=="de"){_e('selected');}?>>German</option>
					<option value="fr" <?php if($options["ebay_lang"]=="fr"){_e('selected');}?>>French</option>
					<option value="it" <?php if($options["ebay_lang"]=="it"){_e('selected');}?>>Italian</option>
					<option value="es" <?php if($options["ebay_lang"]=="es"){_e('selected');}?>>Spanish</option>
					<option value="nl" <?php if($options["ebay_lang"]=="nl"){_e('selected');}?>>Dutch</option>
					<option value="cn" <?php if($options["ebay_lang"]=="cn"){_e('selected');}?>>Chinese</option>
					<option value="tw" <?php if($options["ebay_lang"]=="tw"){_e('selected');}?>>Taiwanese</option>
				</select>
				</td>
        </tr>		
        <tr valign="top">
            <th scope="row"><label for="ebay_country">eBay Country</label></th>
            <td>
				<select name="ebay_country" id="ebay_country">
					<option value="0" <?php if($options["ebay_country"]=="0"){_e('selected');}?>>United States</option>
					<option value="2" <?php if($options["ebay_country"]=="2"){_e('selected');}?>>Canada</option>
					<option value="3" <?php if($options["ebay_country"]=="3"){_e('selected');}?>>United kingdom</option>
					<option value="15" <?php if($options["ebay_country"]=="15"){_e('selected');}?>>Australia</option>
					<option value="16" <?php if($options["ebay_country"]=="16"){_e('selected');}?>>Austria</option>
					<option value="23" <?php if($options["ebay_country"]=="23"){_e('selected');}?>>Belgium (French)</option>
					<option value="71" <?php if($options["ebay_country"]=="71"){_e('selected');}?>>France</option>
					<option value="77" <?php if($options["ebay_country"]=="77"){_e('selected');}?>>Germany</option>
					<option value="100" <?php if($options["ebay_country"]=="100"){_e('selected');}?>>eBay Motors</option>
					<option value="101" <?php if($options["ebay_country"]=="101"){_e('selected');}?>>Italy</option>
					<option value="123" <?php if($options["ebay_country"]=="123"){_e('selected');}?>>Belgium (Dutch)</option>
					<option value="146" <?php if($options["ebay_country"]=="146"){_e('selected');}?>>Netherlands</option>
					<option value="186" <?php if($options["ebay_country"]=="186"){_e('selected');}?>>Spain</option>
					<option value="193" <?php if($options["ebay_country"]=="193"){_e('selected');}?>>Switzerland</option>
					<option value="196" <?php if($options["ebay_country"]=="196"){_e('selected');}?>>Taiwan</option>
					<option value="223" <?php if($options["ebay_country"]=="223"){_e('selected');}?>>China</option>
				</select>
				</td>
        </tr>	
        <tr valign="top">
            <th scope="row"><label for="ebay_template">eBay Auction Template</label></th>
            <td>
				<textarea name="ebay_template" rows="7" cols="37"><?php echo $options["ebay_template"];?></textarea>	
            </td>
        </tr>
		
		
		
        <tr valign="top">
            <th scope="row"><label for="ebay_cache_length">eBay Auction Caching</label></th>
            <td>
                Get new auctions every <input name="ebay_cache_length" type="text" id="ebay_cache_length" value="<?php echo $options["ebay_cache_length"]; ?>" class="small-text" /> pageviews
            </td>
        </tr>	


		
        <tr valign="top">
            <th scope="row"><label for="wpsp_post_type">Post Type</label></th>
            <td>
				<select name="wpsp_post_type">
					<option value="page" <?php if ($options["post_type"]=='page'){echo "selected";}?>>Create Pages</option>
					<option value="post" <?php if ($options["post_type"]=='post'){echo "selected";}?>>Create Posts</option>				
				</select>	
            </td>
        </tr>
		
		
		
        <tr valign="top">
            <th scope="row"><label for="post_status">Post Status</label></th>
            <td>
				<select name="post_status">
					<option value="publish" <?php if ($options["post_status"]=='publish'){echo "selected";}?>>Published</option>
					<option value="draft" <?php if ($options["post_status"]=='draft'){echo "selected";}?>>Draft</option>				
				</select>	
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="post_author">Post Author ID</label></th>
            <td>
				<input name="post_author" type="text" id="post_author" value="<?php echo $options["post_author"]; ?>" class="small-text" />
            </td>
        </tr>			
		
       <tr valign="top">
            <th scope="row"><label for="feat_links">Featured Links</label></th>
            <td>
				<input name="feat_links" type="checkbox" id="feat_links" value="1" <?php if ($options['feat_links']=='1') {echo "checked";} ?>/> <?php _e("Active","wpshoppingpages"); ?>
            </td>
        </tr>			

       <tr valign="top">
            <th scope="row"><label for="post_comments">Comments on Pages</label></th>
            <td>
				<select name="post_comments">
					<option value="closed" <?php if ($options["post_comments"]=='closed'){echo "selected";}?>>Disabled</option>
					<option value="open" <?php if ($options["post_comments"]=='open'){echo "selected";}?>>Enabled</option>				
				</select>	
            </td>
        </tr>	

  
        <tr valign="top">
            <th scope="row"><label for="wpsp_auto_update">Auto Update</label></th>
            <td>
				<input name="wpsp_auto_update" type="checkbox" id="wpsp_auto_update" value="Yes" <?php if ($options['wpsp_auto_update']=='Yes') {echo "checked";} ?>/> <?php _e("Automatically update pages every week.","wpshoppingpages"); ?>
				<?php 
				$lolgl = wp_next_scheduled("wpsphook");
				if(!empty($lolgl)) { ?>
				<i>(Next run: <?php echo date('m/j/Y H:i:s',wp_next_scheduled("wpsphook")); ?> )</i>
				<?php } ?>	
            </td>
        </tr>					

   </table>
   
   <p> <input class="button-primary" type="submit" name="submitoptions" value="Save Options" />
    <input type="hidden" name="action" value="editoption"/><br/><br/>
	<input type="submit" name="reset_options" value="Reset Options to Default" /></p>
    </form>
	
<h3>Links</h3>
<p><a href="http://wpshoppingpages.net/">WP Robot</a> - <a href="http://cmscommander.com/">CMS Commander</a> - <a href="http://wpshoppingpages.com/">WP Shopping Pages</a> - <a href="http://wpshoppingpages.com/documentation/">Documentation</a></p>
	
	
</div>