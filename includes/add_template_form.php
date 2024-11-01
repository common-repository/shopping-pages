<div class="wrap">
    <h2>Templates</h2>
    <?php if($this->_showmsg) { ?>
        <div id="message" class="updated fade"><p><?php echo $this->_message; ?></p></div>
        <?php } ?>

    <br class="clear" />

    <div id="col-container">

        <div id="col-right">
            <div class="col-wrap">
                <form id="posts-filter" action="" method="post">
                    <div class="tablenav">


                        <div class="alignleft actions">
                            <select name="action">
                                <option value="" selected="selected">Bulk Actions</option>
                                <option value="deletetmpls">Delete</option>
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
                                <th scope="col" id="description" class="manage-column column-description" style="">Preview</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th scope="col"  class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
                                <th scope="col"  class="manage-column column-name" style="">Name</th>
                                <th scope="col"  class="manage-column column-description" style="">Preview</th>
                            </tr>
                        </tfoot>
                        <?php
                        $tmpls = $this->get_templates();
                        if(count($tmpls)>0) {
                            foreach ($tmpls as $tmpl) {
                                ?>
                        <tbody id="the-list" class="list:cat">
                            <tr id='cat-1' class='iedit alternate'>
                                 <th scope='row' class='check-column'><input type="checkbox" name="p-<?php echo $tmpl['ID'];?>"/></th>
                                <td class="name column-name"><?php echo $tmpl['tmpl_title'];?><br/>
								<i>Type: <?php echo $tmpl['tmpl_type'];?></i>
				<div class="row-actions">
					<span class="edit">
					<a title="Copy template into form" href="admin.php?page=amtp-templates-page&template=<?php echo $tmpl['ID'];?>">Copy</a>
					|
					</span>
					<span class="view">
					<a href="admin.php?page=amtp-templates-page&template=<?php echo $tmpl['ID'];?>&edit=1" title="Edit">Edit</a>
					|
					</span>
					<span class="delete">
					<a rel="permalink" class="submitdelete" title="Delete" href="admin.php?page=amtp-templates-page&action=deletetmpl&tmpl=<?php echo $tmpl['ID'];?>">Delete</a>
					</span>
				</div>										
								
								</td>
                                <td class="description column-description"><?php echo $tmpl['tmpl_code'];?></td>
                            </tr>
                        </tbody>
                                <?php
                            }
                        }
                        ?>


                    </table>

                

                </form>		

            </div>
			
		<h3>Links</h3>
<ul>
<li><a href="http://wprobot.net/">WP Robot</a> - premium autoblogging plugin for WordPress</li>
<li><a href="http://cmscommander.com/">CMS Commander</a> - manage multiple WordPress weblogs</li>
<li><a href="http://wpshoppingpages.com/">WP Shopping Pages</a></li>
<li><a href="http://wpshoppingpages.com/documentation/">Documentation</a></li>
</ul>
		
			
        </div><!-- /col-right -->

        <div id="col-left">
            <div class="col-wrap">


                <div class="form-wrap">
                    <h3>Add New Template</h3>
                    <div id="ajax-response"></div>
                    <form name="addtemplate" id="addtemplate" method="post" action="">
                        <div class="form-field form-required">
                            <label for="tmpl">Template Name</label>
                            <input name="tmpl" id="tmpl" type="text" value="<?php echo $templ->tmpl_title; ?>" size="40" aria-required="true" />
                        </div>
                        <div style="margin-left:6px;">
							<fieldset>
                            <label for="">Template Type:</label>
                            <input name="tmpl_type" id="tmpl_type" type="radio" value="page" <?php if($templ->tmpl_type == "page") {echo 'checked="1"';} ?> /> Page Template<br/>
                            <input name="tmpl_type" id="tmpl_type" type="radio" value="amazon" <?php if($templ->tmpl_type == "amazon") {echo 'checked="1"';} ?> /> Amazon Single Template<br/>
 <!--<input name="tmpl_type" id="tmpl_type" type="radio" value="ebay" />eBay Single Template-->
                            </fieldset>
                        </div>
                        <div class="form-field form-required">
                            <label for="tmpl_code">Template Code</label>
                            <textarea name="tmpl_code" id="tmpl_code" rows="25" aria-required="true"><?php echo $templ->tmpl_code; ?></textarea>
                        </div>
						
                        <p class="submit">
							<?php if($_GET['edit'] == 1) { ?>
							<input type="hidden" name="action" value="updatetmpl" />
							<input type="hidden" class="button" name="tmpl_ID" value="<?php echo $templ->ID; ?>" />			
							<input type="submit" class="button-primary" name="submit" value="Edit Template" />
							<a href="admin.php?page=amtp-templates-page"><strong>(Cancel)</strong></a>		
							<?php } else { ?>
							<input type="hidden" name="action" value="addtemplate" />
							<input type="submit" class="button-primary" name="submit" value="Create New Template" />
							<?php }?>
						</p>
                    </form></div>

            </div>
			
                <div>
                    <p><strong>Available Template Tags:</strong><br /></p>
                    <p>
                        <strong><u>1. Page Template Tags:</u></strong><br>
                            <strong>{keyword}</strong> - Replaced with search keyword<br>
                            <strong>{amazon}</strong> - Replaced with 1 Amazon product<br>
                            <strong>{ebay}</strong> - Replaced with 1 Ebay auction<br>
                            <strong>{linkshare}</strong> - Replaced with 1 Linkshare product<br>
                            <strong>{commissionjunction}</strong> - Replaced with 1 Commission Junction product<br>							
							<strong>{amazonsearch}</strong> - Affiliate Link to Amazon search page for {keyword}<br>
							<strong>{nav}</strong> - Replaced with an unordered navigation list (1 link to each Amazon product on the current page)<br><br/>							
                        <strong><u>2. Amazon Template Tags:</u></strong><br>
                            <strong>{keyword}</strong> - Replaced with search keyword<br>						
                            <strong>{title}</strong> - Product title<br>
							<strong>{thumbnail}</strong> - Thumbnail image<br>
                            <strong>{smallthumb}</strong> - URL to small thumbnail<br>
                            <strong>{mediumthumb}</strong> - URL to medium thumbnail<br>
							<strong>{largethumb}</strong> - URL to large thumbnail (if available)<br>
                            <strong>{description}</strong> - Product description<br/>
							<strong>{features}</strong> - Short feature list<br/>
                            <strong>{price}</strong> - Product price<br/>
							<strong>{link}</strong> - Product link<br/>
                            <strong>{url}</strong> - Product URL<br/>
							<strong>{buynow}</strong> - A buy now button linking to the product.<br/>
							<strong>{buynow-big}</strong> - A bigger buy now button linking to the product.<br/>							
							<strong>{reviews-iframe}</strong> - displays the 3 most popular reviews inside an iframe.<br/>
							<strong>{reviews-noiframe}</strong> - displays the 3 most popular reviews without the surrounding iframe.<br/>							
							<strong>[has_listprice]...content...[/has_listprice]</strong> - Conditional Tag: "Content" will be displayed only if there is a listprice for current product.
		
                    </p>					
					
                </div>		

                <div>
                <p><strong>Reinsert Default Templates:</strong><br /></p>
				<p>If you messed up the default templates and want to start over you can add them again with the button below.</p>	
				<form name="deftemplate" id="deftemplate" method="post" action="">		
				<input type="hidden" name="action" value="addtemplate" />
				<input type="submit" name="readd_default" value="Re-insert Default Templates" />	
				</form>
                </div>			
        </div><!-- /col-left -->

    </div><!-- /col-container -->	
	
</div><!-- /wrap -->
