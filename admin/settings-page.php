<?php 
function wikiembed_settings_page() {
	global $wikiembed_object;

	$wikiembed_options = $wikiembed_object->options;
	$wikiembed_version = $wikiembed_object->version;

	$updated = false;
	$option = "wikiembed_options";

	if ( isset( $_POST[$option] ) ):
		$value = $_POST[$option];

               $fdisplayTOC = empty($value['toc-show'])? 0 : intval($value['toc-show']);
		
		if ( ! is_array( $value ) ) {
			$value = trim($value);
		}else{
                    $value = shortcode_atts( $wikiembed_object->default_settings(), $value );
                }
		
                $value['toc-show'] = $fdisplayTOC;
		$value = stripslashes_deep( $value );
		$updated = update_option( $option, $value );
		$wikiembed_options = $value;
	endif; 	
	
	$tabs_support = get_theme_support( 'tabs' );
	$accordion_support = get_theme_support( 'accordions' );
	?>
	<div class="wrap">
	    <div class="icon32" id="icon-options-general"><br /></div>
		
		<h2>Wiki Embed Settings</h2>
		<form method="post" action="admin.php?page=wikiembed_settings_page">
			<?php settings_fields('wikiembed_options'); ?>
			<a href="#" id="show-help" >Explain More</a>
			
			<?php if ( $updated ): ?>
				<div class="updated below-h2" id="message"><p>Wiki Embed Settings Updated</p></div>
			<?php endif; ?>
			<h3>Enable Wiki Embed Functionality </h3>
			<p>If there is functionality that wiki embed has that you don't want &mdash; disable it. This will keep pages lean and mean. </p>
			<table class="form-table">
				<tr>
					<th valign="top" class="label" scope="row"></th>
					<td class="field">
                                                <?php 
                                                $fTabs = empty($wikiembed_options['tabs'])? '' : $wikiembed_options['tabs'];
                                                 ?>
                                                <input type="checkbox" aria-required="true" name="wikiembed_options[tabs]" value="1" id="wiki-embed-tabs" <?php checked($fTabs); ?> />
						<span>
							<label for="wiki-embed-tabs">Ability to convert a Wiki page headlines into tabs</label>
						</span>
						<br />
						<div class="help-div">Loads the tabs javascript file on each page of the site.</div>
						
                                                
                                                <?php 
                                                $fAccordions = empty($wikiembed_options['accordions'])? '' : $wikiembed_options['accordions'];
                                                 ?>
                                                <input type="checkbox" aria-required="true" name="wikiembed_options[accordions]" value="1" id="wiki-embed-accordions" <?php checked($fAccordions); ?>/>
						<span>
							<label for="wiki-embed-accordions">Ability to convert a Wiki page headlines into accordion</label>
						</span>
						<br />
						<div class="help-div">Loads the accordions javascript file on each page of the site.</div>
						
                                                <?php 
                                                $fAddedStyling = empty($wikiembed_options['style'])? '' : $wikiembed_options['style'];
                                                 ?>						
                                                <input type="checkbox" aria-required="true" name="wikiembed_options[style]" value="1" id="wiki-embed-overlay" <?php checked( $fAddedStyling); ?> />
						<span>
							<label for="wiki-embed-overlay">Additional styling not commonly found in your theme.</label>
						</span>
						<br />
						<div class="help-div">Loads wiki-embed css files on each page of the site.<br /></div>
						
						<?php 
                                                
                                                $disabled_tabs = ( empty($tabs_support) ? '' : 'disabled="disabled"'); 
                                                $fTabsStyle = empty($wikiembed_options['tabs-style'])? '' : $wikiembed_options['tabs-style'];
                                                
                                                ?>
						<input type="checkbox" aria-required="true" <?php echo $disabled_tabs; ?> name="wikiembed_options[tabs-style]" id="wiki-embed-tab-style" value="1" <?php checked( $fTabsStyle ); ?> />
						<span>
							<?php if ( ! empty( $disabled_tabs ) ) { ?>
								<em> Your theme support tabs styling</em>
							<?php } else { ?>
								<label for="wiki-embed-tab-style">Additional tabs styling, useful if you theme doesn't support tab styling </label>
							<?php } ?>
						</span>
						<br />
						<div class="help-div">Loads tabs css files on each page of the site.<br /></div>
						
						<?php 
                                                
                                                $disabled_accordion = ( empty($accordion_support) ? '' : 'disabled="disabled"');
                                                $fAccordionStyle = empty($wikiembed_options['accordion-style'])? '' : $wikiembed_options['accordion-style'];
                                                
                                                ?>
						<input type="checkbox" aria-required="true" <?php echo $disabled_accordion; ?> name="wikiembed_options[accordion-style]" id="wiki-embed-accordion-style" value="1" <?php checked( $fAccordionStyle); ?> />
						<span>
							<?php if ( ! empty( $disabled_accordion ) ) { ?>
								<em> Your theme support accordion styling</em>
							<?php } else { ?>
								<label for="wiki-embed-accordion-style">Additional Accordion styling, useful if your theme doesn't support accordion styling </label>
							<?php } ?>
						</span>
						<br />
						<div class="help-div">Loads accordion css files on each page of the site.<br /> </div>
					</td>
				</tr>
                                <tr>
                                    <th valign="top" class="label" scope="row">
                                            <span class="alignleft">
                                                    <label for="src">PediaPress TOC Display</label>
                                            </span>
                                    </th> 
                                    <td class="field">
                                        <?php 
                                            $fdisplayTOC = empty($wikiembed_options['toc-show'])? 0 : intval($wikiembed_options['toc-show']);
                                        ?>
                                        <input type="checkbox" aria-required="true" value="1" name="wikiembed_options[toc-show]" id="wiki-embed-display-toc" <?php checked( $fdisplayTOC); ?> />
                                        <span>
                                                <label for="wiki-embed-display-links">Display Table of Contents for PediaPress books</label>
                                        </span>                                         
                                    </td>
                                </tr>
                                <tr>
                                    <th valign="top" class="label" scope="row">
                                            <span class="alignleft">
                                                    <label for="src">PediaPress TOC Links</label>
                                            </span>
                                    </th> 
                                    <td class="field">
                                        <?php
                                            $sTOCLinks = empty($wikiembed_options['toc-links'])? 'default' : $wikiembed_options['toc-links'];
                                        ?>                                           
                                        <label><input name="wikiembed_options[toc-links]" type="radio" value="default"  <?php checked($sTOCLinks,"default"); ?> /> Default &mdash; TOC links are enabled</label>
                                        <br />
                                        <label><input name="wikiembed_options[toc-links]" type="radio" value="logged-in" <?php checked($sTOCLinks,"logged-in"); ?> /> Logged-in &mdash; TOC links are active only when a user is logged in</label>
                                        <br />
                                        <label><input name="wikiembed_options[toc-links]" type="radio" value="disabled" <?php checked($sTOCLinks,"disabled"); ?>  /> Disabled &mdash; TOC links are completely disabled, all the time</label>                                        
                                    </td>                                    
                                </tr>
			</table>
			
			<h3>Global Settings </h3>
			<p>These settings are applied site-wide</p>
			<table class="form-table">
				<tr> <!-- Update Content -->
					<th valign="top" class="label" scope="row">
						<span class="alignleft">
							<label for="src">Update content from the wiki</label>
						</span>
					</th>
					<td class="field">
						<select name="wikiembed_options[wiki-update]" id="wiki-embed-update">
							<option value="0" <?php selected( $wikiembed_options['wiki-update'], "0" ); ?>>No Caching</option>
							<option value="5" <?php selected( $wikiembed_options['wiki-update'], "5" ); ?>>Every 5 minutes </option>
							<option value="30" <?php selected( $wikiembed_options['wiki-update'], "30" ); ?>>Every 30 minutes </option>
							<option value="360" <?php selected( $wikiembed_options['wiki-update'], "360" ); ?>>Every 6 hours </option>
							<option value="1440" <?php selected( $wikiembed_options['wiki-update'], "1440" ); ?>>Daily </option>
							<option value="262974383" <?php selected( $wikiembed_options['wiki-update'], "262974383" ); ?>>Manually</option>
						</select>
						<div class="help-div">
							Set the duration the content of the wiki page will be stored on your site, before it is refreshed again.
							<br />
							<em>Manually</em> means the content will be stored for <em>6 months</em> which will allow you to refresh the content manually.
						</div>
					</td>
				</tr>
				<tr><!-- Internal wiki links -->
					<th valign="top" class="label" scope="row">
						<span class="alignleft">
							<label for="src">Internal wiki links</label>
						</span><br />
						<div class="help-div">Internal wiki links are links that take you to a different page on the same wiki.</div>
					</th>
					<td class="field">
						<label><input name="wikiembed_options[wiki-links]" type="radio" value="default"  <?php checked($wikiembed_options['wiki-links'],"default"); ?> /> Default &mdash; links takes you back to the wiki</label>
						<br />
						<label><input name="wikiembed_options[wiki-links]" type="radio" value="overlay" <?php checked($wikiembed_options['wiki-links'],"overlay"); ?> /> Overlay &mdash; links open with the content in an overlay window</label>
						<br />
						<label><input name="wikiembed_options[wiki-links]" type="radio" value="new-page" <?php checked($wikiembed_options['wiki-links'],"new-page"); ?>  /> WordPress Page &mdash; links open a WordPress page with the content of the wiki</label>
						<br />
						Note: You can make the links open in specific page by specifying a <a href="?page=wiki-embed">target url</a>. 
						<br />
						<label><input name="wikiembed_options[wiki-links]" type="radio" value="overwrite" <?php checked($wikiembed_options['wiki-links'],"overwrite"); ?>  /> Overwrite &mdash; links cause page content to be replaced with the content of the new wiki page</label>
						<br />
                                                <?php
                                                    $fglobalWCR = empty($wikiembed_options['default']['global-content-replace'])? '0' : $wikiembed_options['default']['global-content-replace'];
                                                ?>
                                                <ul style="margin:0"><li style="margin-left: 20px;" >
                                                <input  type="checkbox" aria-required="true" value="1" name="wikiembed_options[default][global-content-replace]" id="wiki-embed-global-content-replace" <?php checked( $fglobalWCR); ?> /> 
                                                <label for="wiki-embed-global-content-replace">Look for all links to wiki sites listed in the <b>Security</b> section and force the content on the current page to be replaced with the content found at a wiki URL when the link is clicked.</label>
                                                    </li>
                                                    <?php 
                                                        $sTemplate = empty($wikiembed_options['default']['global-content-replace-template'])? '' : $wikiembed_options['default']['global-content-replace-template'];
                                                    
                                                    ?>
                                                    <li style="margin-left: 20px;"><label for="ddWCRTemplate">Page Template to Use for Replaced Content</label> <select  name='wikiembed_options[default][global-content-replace-template]' id="ddWCRTemplate">
                                                    <option value="same" <?php echo selected( $sTemplate, 'same' )  ?>>Same as Current Page</option>
                                                    <option value="page.php" <?php echo selected( $sTemplate, 'page.php' )  ?>>Default Template</option>
                                                <?php 
                                                    $templates = get_page_templates();
                                                    foreach ( $templates as $template_name => $template_filename ) {
                                                        if($template_filename == 'page.php')continue;
                                                        echo "<option value='$template_filename' " . selected( $sTemplate, $template_filename ) . ">$template_name</option>";
                                                    }
                                                 ?>        
                                                    </select></li></ul> 
                                                <br />                                                
						<label for="wiki-links-new-page-email">email to </label>
							<input type="text" id="wiki-links-new-page-email" name="wikiembed_options[wiki-links-new-page-email]" value="<?php echo $wikiembed_options['wiki-links-new-page-email']; ?>"/>
						
						<div class="help-div">Specify an email address if you would like to be contacted when some access a new page. that has not been cached yet. This will help you create a better site structure as the content on the wiki grows.</div>
					</td>
				</tr>
				<tr>
					<th valign="top" class="label" scope="row">
						<span class="alignleft">
							<label for="src">Credit wiki page</label>
						</span>
						<br />
						<div class="help-div">This makes it easy to insert a link back to the wiki page.</div>
					</th>
					<td>
                                            <?php
                                                $fdisplaySource = empty($wikiembed_options['default']['source'])? '0' : $wikiembed_options['default']['source'];
                                            ?>
						<input type="checkbox" aria-required="true" value="1" name="wikiembed_options[default][source]" id="wiki-embed-display-links" <?php checked( $fdisplaySource); ?> />
						<span>
							<label for="wiki-embed-display-links">Display a link to the content source after the embedded content</label>
						</span>  
						<br />
						<div id="display-wiki-source" >
							<div style="float:left;" >Before the link <br /><input type="text" name="wikiembed_options[default][pre-source]" size="7" value="<?php echo esc_attr($wikiembed_options['default']['pre-source']); ?>" /><br /></div>
							<div style="float:left; width:250px; padding-top:23px;" >http://www.link-to-the-wiki-page.com</div>
						</div>
					</td>
				</tr>
			</table>
			
			<h3>Shortcode Defaults</h3>
			<p>Tired of checking off all the same settings across the site. Set the shortcodes defaults here</p>
			<table class="form-table">
				<tr>
					<th valign="top" class="label" scope="row">
					</th>
					<td class="field">
                                        <?php 
                                        $fTabs = empty($wikiembed_options['default']['tabs'])? '0' : $wikiembed_options['default']['tabs'];
                                         ?>                                            
					<input type="radio" name="wikiembed_options[default][tabs]" value="1" id="wiki-embed-tabs" <?php checked( $fTabs,1 ); ?> />
					<span><label for="wiki-embed-tabs">Convert section headings to tabs</label></span><br />
                                        <input type="radio" name="wikiembed_options[default][tabs]" value="2" id="wiki-embed-accordion" <?php checked( $fTabs,2 ); ?> />
					<span><label for="wiki-embed-accordion">Convert section headings to accordion</label></span><br />
					<input type="radio" name="wikiembed_options[default][tabs]" value="0" id="wiki-embed-normal-headers" <?php checked( $fTabs,0 ); ?> />
					<span><label for="wiki-embed-normal-headers">Don't convert section headings</label></span><br />
					
                                        <?php 
                                        $fEdit = empty($wikiembed_options['default']['no-edit'])? '' : $wikiembed_options['default']['no-edit'];
                                         ?>                                        
					<input type="checkbox" aria-required="true" value="1" name="wikiembed_options[default][no-edit]" id="wiki-remove-edit" <?php checked( $fEdit ); ?> /> <span ><label for="wiki-remove-edit">Remove edit links</label></span>    <br />
					<div class="help-div">Often wiki pages have edit links displayed next to sections, which is not always desired. </div>
					
                                        <?php 
                                        $fContents = empty($wikiembed_options['default']['no-contents'])? '' : $wikiembed_options['default']['no-contents'];
                                         ?>                                          
                                        <input type="checkbox" aria-required="true" value="1" name="wikiembed_options[default][no-contents]" id="wiki-embed-contents" <?php checked( $fContents ); ?> /> <span ><label for="wiki-embed-contents">Remove table of contents</label></span>    <br />
					<div class="help-div">Often wiki pages have a table of contents (a list of content) at the top of each page. </div>
                                        
                                        <?php 
                                        $fInfoBox = empty($wikiembed_options['default']['no-infobox'])? '' : $wikiembed_options['default']['no-infobox'];
                                         ?>					
					<input type="checkbox" aria-required="true" value="1" name="wikiembed_options[default][no-infobox]" id="wiki-embed-infobox" <?php checked( $fInfoBox ); ?> /> <span ><label for="wiki-embed-infobox">Remove infoboxes</label></span>    <br />
					<div class="help-div"></div>
					</td>
				</tr>
			</table>
			
			<h3>Security</h3>
			<p>Restrict the domains of wikis that you want content to be embedded from.<br />Example: <em>en.wikipedia.org</em> would allow any urls from the english wikipedia, but not from <em>de.wikipedia.org</em> German wikipedia</p>
			<table class="form-table">
				<tr>
					<th valign="top" class="label" scope="row"></th>
					<td class="field">
						<span>Separate domains by new lines</span>
						<br />
                                                <?php 
                                                    $text_string = empty($wikiembed_options['security']['whitelist'])? 'en.wikipedia.org' : $wikiembed_options['security']['whitelist'];
                                                    $text_string = esc_textarea($text_string);
                                                ?>
						<textarea name="wikiembed_options[security][whitelist]"  rows="10" cols="50"><?php echo $text_string; ?></textarea>
					</td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>	
	</div>
	<?php	
}

// Display contextual help for Books
function wiki_embed_add_help_text( $contextual_help, $screen_id, $screen ) { 
	if ( 'wiki-embed_page_wikiembed_settings_page' == $screen->id ) {
		$contextual_help =
			'<h3>' . __('Wiki Embed Explained') . '</h3>' .
			'<ul>' .
			'<li>' . __('Specify the correct genre such as Mystery, or Historic.') . '</li>' .
			'<li>' . __('Specify the correct writer of the book.  Remember that the Author module refers to you, the author of this book review.') . '</li>' .
			'</ul>' .
			'<p>' . __('If you want to schedule the book review to be published in the future:') . '</p>' .
			'<ul>' .
			'<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
			'<li>' . __('Change the date to the date to actual publish this article, then click on Ok.') . '</li>' .
			'</ul>' .
			'<h3>' . __('Shortcode') . '</h3>';
	}
	
	return $contextual_help;
}

// Sanitize and validate input. Accepts an array, return a sanitized array.
function wikiembed_options_validate( $wikiembed_options ) {
	return array(
		'tabs'            => ( isset( $wikiembed_options['tabs']            ) && $wikiembed_options['tabs']            == 1 ? 1 : 0 ),
		'accordians'      => ( isset( $wikiembed_options['accordions']      ) && $wikiembed_options['accordions']      == 1 ? 1 : 0 ),
		'style'           => ( isset( $wikiembed_options['style']           ) && $wikiembed_options['style']           == 1 ? 1 : 0 ),
		'tabs-style'      => ( isset( $wikiembed_options['tabs-style']      ) && $wikiembed_options['tabs-style']      == 1 ? 1 : 0 ),
		'accordion-style' => ( isset( $wikiembed_options['accordion-style'] ) && $wikiembed_options['accordion-style'] == 1 ? 1 : 0 ),
		'wiki-update'     => ( is_numeric( $wikiembed_options['wiki-update'] ) ? $wikiembed_options['wiki-update'] : "30" ),
		'wiki-links'      => ( in_array( $wikiembed_options['wiki-links'], array( "default", "overlay", "new-page","overwrite" ) ) ? $wikiembed_options['wiki-links'] : "default" ),
		'wiki-links-new-page-email' => wp_rel_nofollow( $wikiembed_options['wiki-links-new-page-email'] ),
		'toc-show'            => ( isset( $wikiembed_options['toc-show']) && $wikiembed_options['toc-show']            == 1 ? 1 : 0 ),		
		'toc-links'            => ( isset( $wikiembed_options['toc-links']) ?  trim( $wikiembed_options['toc-links'] ) : null ),		
                'default' => array(
                        'global-content-replace' => ( isset( $wikiembed_options['default']['global-content-replace'] ) && $wikiembed_options['default']['global-content-replace'] == 1 ? 1 : 0 ),
                        'global-content-replace-template' => $wikiembed_options['default']['global-content-replace-template'],
			'source'      => ( isset( $wikiembed_options['default']['source'] ) && $wikiembed_options['default']['source'] == 1 ? 1 : 0 ),
			'pre-source'  => wp_rel_nofollow( $wikiembed_options['default']['pre-source'] ),
			'no-contents' => ( isset( $wikiembed_options['default']['no-contents'] ) && $wikiembed_options['default']['no-contents'] == 1 ? 1 : 0 ),
			'no-edit'     => ( isset( $wikiembed_options['default']['no-infobox']  ) && $wikiembed_options['default']['no-infobox']  == 1 ? 1 : 0 ),
			'no-infobox'  => ( isset( $wikiembed_options['default']['no-edit']     ) && $wikiembed_options['default']['no-edit']     == 1 ? 1 : 0 ),
			'tabs'        => ( is_numeric( $wikiembed_options['default']['tabs'] ) ? $wikiembed_options['default']['tabs'] : "0" ),
		),
		'security' => array(
			'whitelist' => ( isset( $wikiembed_options['security']['whitelist'] ) ? trim( $wikiembed_options['security']['whitelist'] ) : null ),
		),
	);
}
?>
<style>
.help-div{clear: both;margin-top: 3px;}
</style>