 <div class="wrap nosubsub">
	<div id="icon-edit" class="icon32"><br /></div>
        <?php if($this->_showmsg) { ?>
        <div id="message" class="updated fade"><p><?php echo $this->_message; ?></p></div>
        <?php } ?>

        <?php if(!$this->_awspublic || !$this->_awssecret ) { ?>
        <div id="message" class="updated fade"><p>Warning: Your Amazon API keys have not been entered yet, adding pages will not work! Go to the "Options" screen to enter your details.</p></div>
        <?php } ?>		
		
<h2>WP Shopping Pages</h2>


<br class="clear" />

<div id="col-container">

<div id="col-right">
<div class="col-wrap">
<form id="posts-filter" action="" method="post">
<div class="tablenav">


<div class="alignleft actions">
<select name="action">
<option value="" selected="selected">Bulk Actions</option>
<option value="deletepage">Delete</option>
<option value="updatepage">Update</option>
</select>
<input type="submit" value="Apply" name="doaction" id="doaction" class="button-secondary action" />
</div> 

<br class="clear" />

</div>

<div class="clear"></div>

<table class="widefat fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col" id="name" class="manage-column column-name" style="">Name</th>
        <th scope="col"  class="manage-column column-name" style="">Page ID</th>
        <th scope="col"  class="manage-column column-name" style="">Template</th>
        <th scope="col"  class="manage-column column-name" style="">Title</th>
	</tr>
	</thead>

	<tfoot>
	<tr>
	<th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col"  class="manage-column column-name" style="">Name</th>
        <th scope="col"  class="manage-column column-name" style="">Page ID</th>
         <th scope="col"  class="manage-column column-name" style="">Template</th>
        <th scope="col"  class="manage-column column-name" style="">Title</th>
	</tr>
	</tfoot>
 
	<tbody id="the-list" class="list:cat">
 <?php
                        $tmpls = $this->get_pages();
                        if(count($tmpls)>0) {
                            foreach ($tmpls as $tmpl) {
                                ?>
                            <tr id='cat-1' class='iedit alternate'>
                                <th scope='row' class='check-column'><input type="checkbox" name="p-<?php echo $tmpl['ID'];?>"/></th>
                                <td class="name column-name"><?php echo $tmpl['keyword'];?></td>
                                <td class="name column-name"><?php echo $tmpl['pageID'];?></td>
                                <td class="name column-name"><?php echo $tmpl['pageTmpl'];?></td>
                                <td class="name column-name"><?php echo $tmpl['pageTitle'];?></td>
                            </tr>
                                <?php
                            }
                        }
                        ?>
	
        </tbody>

</table>
</form>

<div class="form-wrap">
</div>

</div>
</div><!-- /col-right -->

<div id="col-left">
<div class="col-wrap">


<div class="form-wrap">
<h3>Create new Shopping Pages</h3>
<div id="ajax-response"></div>
<form name="addpages" id="addpages" method="post" action="">

<div class="form-field form-required">
<label for="add_title"><strong>Page Title</strong></label>
        <input type="text" name="add_title" id="add_title" value="<?php echo $this->settings["add_title_template"]; ?>">
	<label for="add_keys"><strong>Keywords</strong> (one per line)</label>
        <textarea name="add_keys" id="add_keys" rows="15"></textarea> 
        <br>
        
                <label for="page_tmpl"><strong>Page Template</strong></label>
        <select name="page_tmpl" id="page_tmpl">
			<option value="randomp" <?php if ($this->settings["add_page_template"]=="randomp"){echo "selected";}?>>Random</option>
            <?php
            echo $option_page_templates;
            ?>
        </select>
		
		<span<?php if($amazon_display == 0) {echo ' style="display:none;"';} ?>>		
        <label for="amazon_tmpl"><strong>Amazon Single Template</strong></label>
        <select name="amazon_tmpl" id="amazon_tmpl">
			<option value="randoma" <?php if ($this->settings["add_ama_template"]=="randoma"){echo "selected";}?>>Random</option>
            <?php
            echo $option_amazon_templates;
            ?>
        </select>
		</span>		
		
     <!--   <label for="ebay_tmpl">eBay Single Template</label>
        <select name="ebay_tmpl" id="ebay_tmpl">
			<option value="randome" <?php if ($this->settings["add_ebay_template"]=="randome"){echo "selected";}?>>Random</option>
            <?php
            echo $option_ebay_templates;
            ?>
        </select>-->
		
	<?php $ll = $this->settings["amazon_site"]; if($ll == "us") {$ll = "com";} ?>	
	<label for="ama_searchindex">Amazon SearchIndex</label>
				<select name="ama_searchindex" id="ama_searchindex">
					<option <?php if ($this->settings["add_ama_si"]=="All"){echo "selected";}?>>All</option>
					<?php if($ll!="fr" || $ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Apparel"){echo "selected";}?>>Apparel</option><?php } ?>
					<?php if($ll=="com" || $ll=="de") {?><option <?php if ($this->settings["add_ama_si"]=="Automotive"){echo "selected";}?>>Automotive</option><?php } ?>
					<?php if($ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Baby"){echo "selected";}?>>Baby</option><?php } ?>
					<?php if($ll!="uk" || $ll!="de") {?><option <?php if ($this->settings["add_ama_si"]=="Beauty"){echo "selected";}?>>Beauty</option><?php } ?>
					<option <?php if ($this->settings["add_ama_si"]=="Books"){echo "selected";}?>>Books</option>
					<option <?php if ($this->settings["add_ama_si"]=="Classical"){echo "selected";}?>>Classical</option>
					<?php if($ll=="com") {?><option value="DigitalMusic" <?php if ($this->settings["add_ama_si"]=="DigitalMusic"){echo "selected";}?>>Digital Music</option><?php } ?>
					<?php if($ll!="jp" || $ll!="ca") {?><option value="MP3Downloads" <?php if ($this->settings["add_ama_si"]=="MP3Downloads"){echo "selected";}?>>MP3 Downloads</option><?php } ?>
					<option <?php if ($this->settings["add_ama_si"]=="DVD"){echo "selected";}?>>DVD</option>
					<option <?php if ($this->settings["add_ama_si"]=="Electronics"){echo "selected";}?>>Electronics</option>
					<?php if($ll!="com" || $ll!="uk") {?><option value="ForeignBooks" <?php if ($this->settings["add_ama_si"]=="ForeignBooks"){echo "selected";}?>>Foreign Books</option><?php } ?>
					<?php if($ll=="com") {?><option value="GourmetFood" <?php if ($this->settings["add_ama_si"]=="GourmetFood"){echo "selected";}?>>Gourmet Food</option><?php } ?>
					<?php if($ll=="com") {?><option value="Grocery" <?php if ($this->settings["add_ama_si"]=="Grocery"){echo "selected";}?>>Grocery</option><?php } ?>
					<?php if($ll!="ca") {?><option value="HealthPersonalCare" <?php if ($this->settings["add_ama_si"]=="HealthPersonalCare"){echo "selected";}?>>Health &amp; Personal Care</option><?php } ?>
					<?php if($ll!="fr" || $ll!="ca") {?><option value="HomeGarden" <?php if ($this->settings["add_ama_si"]=="HomeGarden"){echo "selected";}?>>Home &amp; Garden</option><?php } ?>
					<?php if($ll=="com") {?><option <?php if ($this->settings["add_ama_si"]=="Industrial"){echo "selected";}?>>Industrial</option><?php } ?>
					<?php if($ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Jewelry"){echo "selected";}?>>Jewelry</option><?php } ?>
					<?php if($ll=="com") {?><option value="KindleStore" <?php if ($this->settings["add_ama_si"]=="KindleStore"){echo "selected";}?>>Kindle Store</option><?php } ?>
					<?php if($ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Kitchen"){echo "selected";}?>>Kitchen</option><?php } ?>
					<?php if($ll=="com" || $ll=="de") {?><option <?php if ($this->settings["add_ama_si"]=="Magazines"){echo "selected";}?>>Magazines</option><?php } ?>
					<?php if($ll=="com") {?><option <?php if ($this->settings["add_ama_si"]=="Merchants"){echo "selected";}?>>Merchants</option><?php } ?>
					<?php if($ll=="com") {?><option <?php if ($this->settings["add_ama_si"]=="Miscellaneous"){echo "selected";}?>>Miscellaneous</option><?php } ?>
					<option <?php if ($this->settings["add_ama_si"]=="Music"){echo "selected";}?>>Music</option>
					<?php if($ll=="com") {?><option value="MusicalInstruments" <?php if ($this->settings["add_ama_si"]=="MusicalInstruments"){echo "selected";}?>>Musical Instruments</option><?php } ?>
					<?php if($ll!="ca") {?><option value="MusicTracks" <?php if ($this->settings["add_ama_si"]=="MusicTracks"){echo "selected";}?>>Music Tracks</option><?php } ?>
					<?php if($ll!="jp" || $ll!="ca") {?><option value="OfficeProducts" <?php if ($this->settings["add_ama_si"]=="OfficeProducts"){echo "selected";}?>>Office Products</option><?php } ?>
					<?php if($ll!="fr" || $ll!="ca") {?><option value="OutdoorLiving" <?php if ($this->settings["add_ama_si"]=="OutdoorLiving"){echo "selected";}?>>Outdoor &amp; Living</option><?php } ?>
					<?php if($ll=="com" || $ll=="de") {?><option value="PCHardware" <?php if ($this->settings["add_ama_si"]=="PCHardware"){echo "selected";}?>>PC Hardware</option><?php } ?>
					<?php if($ll=="com") {?><option value="PetSupplies" <?php if ($this->settings["add_ama_si"]=="PetSupplies"){echo "selected";}?>>Pet Supplies</option><?php } ?>
					<?php if($ll=="com" || $ll=="de") {?><option <?php if ($this->settings["add_ama_si"]=="Photo"){echo "selected";}?>>Photo</option><?php } ?>
					<?php if($ll=="com" || $ll=="de") {?><option <?php if ($this->settings["add_ama_si"]=="Shoes"){echo "selected";}?>>Shoes</option><?php } ?>
					<option <?php if ($this->settings["add_ama_si"]=="Software"){echo "selected";}?>>Software</option>
					<?php if($ll!="fr" || $ll!="ca") {?><option value="SportingGoods" <?php if ($this->settings["add_ama_si"]=="SportingGoods"){echo "selected";}?>>Sporting Goods</option><?php } ?>
					<?php if($ll!="fr" || $ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Tools"){echo "selected";}?>>Tools</option><?php } ?>
					<?php if($ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Toys"){echo "selected";}?>>Toys</option><?php } ?>
					<option value="UnboxVideo" <?php if ($this->settings["add_ama_si"]=="UnboxVideo"){echo "selected";}?>>Unbox Video</option>
					<option <?php if ($this->settings["add_ama_si"]=="VHS"){echo "selected";}?>>VHS</option>
					<option <?php if ($this->settings["add_ama_si"]=="Video"){echo "selected";}?>>Video</option>
					<option value="VideoGames" <?php if ($this->settings["add_ama_si"]=="VideoGames"){echo "selected";}?>>Video Games</option>
					<?php if($ll!="jp" || $ll!="ca") {?><option <?php if ($this->settings["add_ama_si"]=="Watches"){echo "selected";}?>>Watches</option><?php } ?>
					<?php if($ll=="com") {?><option <?php if ($this->settings["add_ama_si"]=="Wireless"){echo "selected";}?>>Wireless</option><?php } ?>
					<?php if($ll=="com") {?><option value="WirelessAccessories" <?php if ($this->settings["add_ama_si"]=="WirelessAccessories"){echo "selected";}?>>Wireless Accessories</option><?php } ?>         			
				</select>	

	<label for="ebay_cat">Ebay Category</label>
				<select name="ebay_cat" id="ebay_cat">
					<option <?php if ($this->settings["add_ebay_cat"]=="all"){echo "selected";}?> value="all">All Categories</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="20081"){echo "selected";}?> value="20081">Antiques</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="550"){echo "selected";}?> value="550" >Art</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="2984"){echo "selected";}?> value="2984">Baby</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="267"){echo "selected";}?> value="267" >Books</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="12576"){echo "selected";}?> value="12576">Business &amp; Industrial</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="625"){echo "selected";}?> value="625" >Cameras &amp; Photo</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="15032"){echo "selected";}?> value="15032">Cell Phones &amp; PDAs</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="11450"){echo "selected";}?> value="11450">Clothing, Shoes &amp; Accessories</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="11116"){echo "selected";}?> value="11116" >Coins &amp; Paper Money</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="1"){echo "selected";}?> value="1" >Collectibles</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="58058"){echo "selected";}?> value="58058">Computers &amp; Networking</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="14339"){echo "selected";}?> value="14339">Crafts</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="237"){echo "selected";}?> value="237" >Dolls &amp; Bears</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="11232"){echo "selected";}?> value="11232" >DVDs &amp; Movies</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="6000"){echo "selected";}?> value="6000" >eBay Motors</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="293"){echo "selected";}?> value="293" >Electronics</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="45100"){echo "selected";}?> value="45100" >Entertainment Memorabilia</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="31411"){echo "selected";}?> value="31411" >Gift Certificates</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="26395"){echo "selected";}?> value="26395" >Health &amp; Beauty</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="11700"){echo "selected";}?> value="11700">Home &amp; Garden</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="281"){echo "selected";}?> value="281" >Jewelry &amp; Watches</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="11233"){echo "selected";}?> value="11233">Music</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="619"){echo "selected";}?> value="619" >Musical Instruments</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="870"){echo "selected";}?> value="870" >Pottery &amp; Glass</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="10542"){echo "selected";}?> value="10542">Real Estate</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="316"){echo "selected";}?> value="316" >Specialty Services</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="382"){echo "selected";}?> value="382" >Sporting Goods</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="64482"){echo "selected";}?> value="64482">Sports Mem, Cards &amp; Fan Shop</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="260"){echo "selected";}?> value="260" >Stamps</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="1305"){echo "selected";}?> value="1305">Tickets</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="220"){echo "selected";}?> value="220">Toys &amp; Hobbies</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="3252"){echo "selected";}?> value="3252" >Travel</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="1249"){echo "selected";}?> value="1249" >Video Games</option>
					<option <?php if ($this->settings["add_ebay_cat"]=="99"){echo "selected";}?> value="99">Everything Else</option>
				</select>	
				
	<?php if($this->settings["post_type"] == "post") {?>
	<label for="post_cat">Category</label>
				<select name="post_cat" id="post_cat">				
				<?php
				   	$categories = get_categories('type=post&hide_empty=0');
				   	foreach($categories as $category)
				   		{
				   			echo '<option value="'.$category->cat_ID.'">'.$category->cat_name.'</option>';
				   		 }				
				?>				
				</select>			
	<?php } ?>
	
</div>

<p class="submit"><input type="submit" class="button-primary" name="addpages" value="Create Pages" /></p>
</form></div>

</div>
</div><!-- /col-left -->

</div><!-- /col-container -->

<h3>Links</h3>
<p><a href="http://wprobot.net/">WP Robot</a> - <a href="http://cmscommander.com/">CMS Commander</a> - <a href="http://wpshoppingpages.com/">WP Shopping Pages</a> - <a href="http://wpshoppingpages.com/documentation/">Documentation</a></p>
	
<h3>How to add Subpages:</h3>
<p>Subpages are declared in the keyword list by starting them with a dash ("-"), which will make the page a subpage of the previous keyword. For example:<br/><br/><i>Keyword 1<br/><strong>-</strong>Keyword 2<br/><strong>--</strong>Keyword 3</i><br/><br/>Here the page for "Keyword 2" will be a subpage of "Keyword 1", while the page for "Keyword 3" is a subpage of "Keyword 2".<br/><a href="http://wpshoppingpages.com/documentation">See the documentation for more details</a></p>	
	
<h3>How to add Subpages to existing pages:</h3>
<p>Simply mark the existing page you want to add Shopping Pages to as sub-pages with a "#". Example with "Your Page" being the exact name of the existing page:<br/><br/><i>#Your Page<br/><strong>-</strong>Keyword 1<br/><strong>-</strong>Keyword 2</i><br/></p>
	
</div><!-- /wrap -->