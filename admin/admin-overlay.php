<?php
add_action( 'admin_footer', 'wikiembed_overlay_popup_form' );
add_action( 'media_buttons_context', 'wikiembed_overlay_buttons' );

/**
 * wikiembed_overlay_buttons function.
 * 
 * @access public
 * @param mixed $context
 * @return void
 */
function wikiembed_overlay_buttons( $context ) {
	global $post, $pagenow;
	
	if ( in_array( $pagenow, array( "post.php", "post-new.php" ) ) && in_array( $post->post_type , array( "post", "page" ) ) ) {
            $wiki_embed_overlay_image_button = plugins_url('/rdp-wiki-press-embed/resources/img/icon.png');
	    $output_link = '<a href="#TB_inline?height=500&width=690&inlineId=wiki_embed_form" class="thickbox button" title="' .__("RDP Wiki-Press Embed", 'wiki-embed') . '" id="wiki-embed-overlay-button">';
            $output_link .= '<span class="wp-media-buttons-icon" style="background: url('. $wiki_embed_overlay_image_button.'); background-repeat: no-repeat; background-position: left bottom;"/></span>';
            $output_link .= '</a><style>#wiki_embed_form{ display:none;}</style>';
	    return $context.$output_link;
	} else {
    	return $context;
	}
}

/**
 * wikiembed_overlay_popup_form function.
 * 
 * @access public
 * @return void
 */
function wikiembed_overlay_popup_form() {
	global $wikiembed_object, $pagenow, $post;
	$wikiembed_options = $wikiembed_object->options;
	
	if ( in_array( $pagenow, array( "post.php", "post-new.php" ) ) && in_array( $post->post_type , array( "post", "page" ) ) ) {
            wp_enqueue_script('wiki-embed-admin-overlay', plugins_url( '/admin/js/script.admin-overlay.js',RDP_WE_PLUGIN_BASENAME), array("jquery","jquery-ui-tabs"), '1.0', true);
            $params = array(
                            'pp_button_text' => (PPE_CTA_BUTTON_TEXT != $wikiembed_options['ppe-cta-button-text'])? $wikiembed_options['ppe-cta-button-text'] : PPE_CTA_BUTTON_TEXT,
                            'pp_button_width' => (PPE_CTA_BUTTON_WIDTH != $wikiembed_options['ppe-cta-button-width'])? $wikiembed_options['ppe-cta-button-width'] : PPE_CTA_BUTTON_WIDTH,
                            'pp_button_top_color' => (PPE_CTA_BUTTON_TOP_COLOR != $wikiembed_options['ppe-cta-button-top-color'])? $wikiembed_options['ppe-cta-button-top-color'] : PPE_CTA_BUTTON_TOP_COLOR,
                            'pp_button_bottom_color' => (PPE_CTA_BUTTON_BOTTOM_COLOR != $wikiembed_options['ppe-cta-button-bottom-color'])? $wikiembed_options['ppe-cta-button-bottom-color'] : PPE_CTA_BUTTON_BOTTOM_COLOR,
                            'pp_button_font_color' => (PPE_CTA_BUTTON_FONT_COLOR != $wikiembed_options['ppe-cta-button-font-color'])? $wikiembed_options['ppe-cta-button-font-color'] : PPE_CTA_BUTTON_FONT_COLOR,
                            'pp_button_font_hover_color' => (PPE_CTA_BUTTON_FONT_HOVER_COLOR != $wikiembed_options['ppe-cta-button-font-hover-color'])? $wikiembed_options['ppe-cta-button-font-hover-color'] : PPE_CTA_BUTTON_FONT_HOVER_COLOR,
                            'pp_button_border_color' => (PPE_CTA_BUTTON_BORDER_COLOR != $wikiembed_options['ppe-cta-button-border-color'])? $wikiembed_options['ppe-cta-button-border-color'] : PPE_CTA_BUTTON_BORDER_COLOR,
                            'pp_button_box_shadow_color' => (PPE_CTA_BUTTON_BOX_SHADOW_COLOR != $wikiembed_options['ppe-cta-button-box-shadow-color'])? $wikiembed_options['ppe-cta-button-box-shadow-color'] : PPE_CTA_BUTTON_BOX_SHADOW_COLOR,
                            'pp_button_text_shadow_color' => (PPE_CTA_BUTTON_TEXT_SHADOW_COLOR != $wikiembed_options['ppe-cta-button-text-shadow-color'])? $wikiembed_options['ppe-cta-button-text-shadow-color'] : PPE_CTA_BUTTON_TEXT_SHADOW_COLOR
                        );
            wp_localize_script('wiki-embed-admin-overlay', 'rdp_we_admin', $params);
            wp_enqueue_style( 'wiki-embed-admin-core-style', plugins_url( '/admin/css/jquery-ui.css',RDP_WE_PLUGIN_BASENAME ), null,'1.11.2' );            
            wp_enqueue_style( 'wiki-embed-admin-theme-style', plugins_url( '/admin/css/jquery-ui.theme.min.css',RDP_WE_PLUGIN_BASENAME ), array('wiki-embed-admin-core-style'),'1.11.2' );            
            
            ?>
	
		<div id="wiki_embed_form">
                    <div id="wiki-embed-tabs" style="position: static;">
                      <ul>
                        <li><a href="#tabs-1"><?php _e('Wiki Page', 'wiki-embed'); ?></a></li>
                        <li><a href="#tabs-2"><?php _e('PediaPress Book', 'wiki-embed'); ?></a></li>
                      </ul>
			<div id="tabs-1" class="wiki_embed_form_wrap">
                            <div class="media-item media-blank">
					<table class="describe">
						<tbody>
							<tr>
								<th valign="top" style="width: 130px;" class="label" scope="row">
									<span class="alignleft"><label for="wiki-embed-src"><?php _e('Source URL', 'wiki-embed'); ?></label></span>
									<span class="alignright"><abbr class="required" title="required" id="status_img">*</abbr></span>
								</th>
								<td class="field"><input type="text" aria-required="true" value="http://" name="wiki-embed-src" id="wiki-embed-src" size="60"><br /><br /></td>
							</tr>
							
							<?php if ( $wikiembed_options['tabs'] ): ?>
								<tr>
									<th valign="top" class="label" scope="row">
									</th>
									<td class="field"><input type="radio" name="wiki-embed-tabs" value=" tabs" class="wiki-embed-tabs" id="wiki-embed-tabs" <?php checked( $wikiembed_options['default']['tabs'],1 ); ?> />
									<span><label for="wiki-embed-tabs">Convert section headings to tabs</label></span>
									</td>
								</tr>
								<tr>
									<th valign="top" class="label" scope="row">
									</th>
									<td class="field"><input type="radio" name="wiki-embed-tabs" value=" accordion" class="wiki-embed-accordion" id="wiki-embed-accordion" <?php checked( $wikiembed_options['default']['tabs'],2 ); ?> />
									<span><label for="wiki-embed-accordion">Convert section headings to accordion</label></span>
									</td>
								</tr>
								<tr>
									<th valign="top" class="label" scope="row">
									</th>
									<td class="field"><input type="radio" name="wiki-embed-tabs" value=" " id="wiki-embed-normal-headers" id="wiki-embed-normal-headers" <?php checked( $wikiembed_options['default']['tabs'],0 ); ?> />
									<span><label for="wiki-embed-normal-headers">Don't convert section headings</label></span>
									</td>
								</tr>
							<?php else: ?>
								<tr>
									<th valign="top" class="label" scope="row">
									</th>
									<td class="field"><input type="checkbox" disabled="disabled" /><span><label for="wiki-embed-tabs"> <del>Top section converted into tabs</del></label></span>
									&mdash; to enable see the <a href="">Settings page</a>
									</td>
								</tr>
							<?php endif; ?>
							
							<tr>
								<th valign="top" class="label" scope="row">
								</th>
								<td class="field"><input type="checkbox" aria-required="true" value=" no-edit" name="wiki-embed-edit" id="wiki-embed-edit"<?php checked($wikiembed_options['default']['no-edit'] ); ?> /> <span ><label for="wiki-embed-edit"> Remove edit links</label></span></td>
							</tr>
							<tr>
								<th valign="top" class="label" scope="row">
								</th>
								<td class="field"><input type="checkbox" aria-required="true" value=" no-contents" name="wiki-embed-contents" id="wiki-embed-contents" <?php checked($wikiembed_options['default']['no-contents'] ); ?> /> <span ><label for="wiki-embed-contents"> Remove contents index</label></span></td>
							</tr>
							<tr>
								<th valign="top" class="label" scope="row">
								</th>
								<td class="field"><input type="checkbox" aria-required="true" value=" no-infobox" name="wiki-embed-infobox" id="wiki-embed-infobox" <?php checked($wikiembed_options['default']['no-infobox'] ); ?> /> <span ><label for="wiki-embed-infobox"> Remove info box</label></span></td>
							</tr>
							<tr>
								<td></td>
								<td><br />
									<input type="button" value="Insert into Post/ Page" onclick="wiki_embed_insert_overlay_form();" id="btnWikiEmbed" class="button">
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
                    
                    <div id="tabs-2" class="pp_embed_form_wrap">
                        <div class="media-item media-blank">
                            <table class="describe">
                                <tbody>
                                    <tr>
                                        <th valign="top" style="width: 130px;" class="label" scope="row">
                                                <span class="alignleft"><label for="src"><?php _e('Source URL', 'wiki-embed'); ?></label></span>
                                                <span class="alignright"><abbr class="required" title="required" id="status_img">*</abbr></span>
                                        </th>
                                        <td class="field"><input type="text" aria-required="true" value="http://" name="pp-embed-src" id="pp-embed-src" size="60"><br /><br /></td>
                                    </tr> 
                                    <tr>
                                        <th valign="top" class="label" scope="row"></th>
                                        <td class="field"><input type="checkbox" aria-required="true" value="1" name="pp-embed-display-toc" id="pp-embed-display-toc" <?php checked($wikiembed_options['toc-show'] ); ?> /> <span ><label for="pp-embed-display-toc"> Display Table of Contents for PediaPress book</label></span></td>
                                    </tr>  
                                    <tr>
                                        <th valign="top" class="label" scope="row"></th>
                                        <td class="field">
                                        <?php
                                            $sTOCLinks = empty($wikiembed_options['toc-links'])? 'default' : $wikiembed_options['toc-links'];
                                        ?>                                           
                                        <label><input name="pp-embed-toc-links" id="pp-embed_toc-links-default" type="radio" value="default"  <?php checked($sTOCLinks,"default"); ?> /> Default &mdash; TOC links are enabled</label>
                                        <br />
                                        <label><input name="pp-embed-toc-links" id="pp-embed_toc-links-logged-in" type="radio" value="logged-in" <?php checked($sTOCLinks,"logged-in"); ?> /> Logged-in &mdash; TOC links are active only when a user is logged in</label>
                                        <br />
                                        <label><input name="pp-embed-toc-links" id="pp-embed_toc-links-disabled" type="radio" value="disabled" <?php checked($sTOCLinks,"disabled"); ?>  /> Disabled &mdash; TOC links are completely disabled, all the time</label>                                               
                                        </td>
                                    </tr>
                                    <tr>
                                        <th valign="top" class="label" scope="row"></th>
                                        <td class="field">
                                            <h3>CTA Button Settings</h3>
                                            <label for="ppe-cta-button-content">Popup Content (shortcode/text/HTML)</label><br />
                                            <?php
                                                $sPPDownloadButtonContent = empty($wikiembed_options['ppe-cta-button-content'])? '' : $wikiembed_options['ppe-cta-button-content'];
                                                $sPPDownloadButtonContent = esc_textarea($sPPDownloadButtonContent);
                                            ?>                                              
                                            <textarea id="ppe-cta-button-content"><?php echo $sPPDownloadButtonContent ?></textarea>
                                        </td>
                                    </tr>                                    
                                    
                                    <tr>
                                        <th valign="top" class="label" scope="row"></th>
                                        <td class="field">
                                            <label for="ppe-cta-button-text">Button Text</label><br />
                                            <?php
                                                $sPPDownloadButtonText = empty($wikiembed_options['ppe-cta-button-text'])? PPE_CTA_BUTTON_TEXT : $wikiembed_options['ppe-cta-button-text'];
                                                $sPPDownloadButtonText = esc_attr($sPPDownloadButtonText);
                                            ?>                                              
                                            <input type="text" id="ppe-cta-button-text" value="<?php echo $sPPDownloadButtonText ?>" />
                                        </td>
                                    </tr>
                                <tr style="line-height: normal;">
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $sPPDownloadButtonWidth = ( isset( $wikiembed_options['ppe-cta-button-width'] ) ) ? $wikiembed_options['ppe-cta-button-width'] : PPE_CTA_BUTTON_WIDTH;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Width:</span> <input type="text" id="ppe-cta-button-width" value="<?php echo $sPPDownloadButtonWidth ?>"  style="width: 50px;"/> pixels (max: 500)                                    
                                    </td>
                                </tr>                                
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $bgTopColor = ( isset( $wikiembed_options['ppe-cta-button-top-color'] ) ) ? $wikiembed_options['ppe-cta-button-top-color'] : PPE_CTA_BUTTON_TOP_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Top Color:</span> <input type="text" id="ppe-cta-button-top-color" class="bgTopColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_TOP_COLOR  ?>" value="<?php echo $bgTopColor ?>" />                                        
                                    </td>
                                </tr>
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $bgBottomColor = ( isset( $wikiembed_options['ppe-cta-button-bottom-color'] ) ) ? $wikiembed_options['ppe-cta-button-bottom-color'] : PPE_CTA_BUTTON_BOTTOM_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Bottom Color:</span> <input type="text" id="ppe-cta-button-bottom-color" class="bgBottomColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_BOTTOM_COLOR  ?>" value="<?php echo $bgBottomColor ?>" />                                        
                                    </td>
                                </tr>
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $fontColor = ( isset( $wikiembed_options['ppe-cta-button-font-color'] ) ) ? $wikiembed_options['ppe-cta-button-font-color'] : PPE_CTA_BUTTON_FONT_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Font Color:</span> <input type="text" id="ppe-cta-button-font-color" class="fontColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_FONT_COLOR  ?>" value="<?php echo $fontColor ?>" />                                        
                                    </td>
                                </tr> 
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $fontHoverColor = ( isset( $wikiembed_options['ppe-cta-button-font-hover-color'] ) ) ? $wikiembed_options['ppe-cta-button-font-hover-color'] : PPE_CTA_BUTTON_FONT_HOVER_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Font Hover Color:</span> <input type="text" id="ppe-cta-button-font-hover-color" class="fontHoverColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_FONT_HOVER_COLOR  ?>" value="<?php echo $fontHoverColor ?>" />                                        
                                    </td>
                                </tr>  
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $borderColor = ( isset( $wikiembed_options['ppe-cta-button-border-color'] ) ) ? $wikiembed_options['ppe-cta-button-border-color'] : PPE_CTA_BUTTON_BORDER_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Border Color:</span> <input type="text" id="ppe-cta-button-border-color" class="borderColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_BORDER_COLOR  ?>" value="<?php echo $borderColor ?>" />                                        
                                    </td>
                                </tr> 
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $boxShadowColor = ( isset( $wikiembed_options['ppe-cta-button-box-shadow-color'] ) ) ? $wikiembed_options['ppe-cta-button-box-shadow-color'] : PPE_CTA_BUTTON_BOX_SHADOW_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Button Shadow Color:</span> <input type="text" id="ppe-cta-button-box-shadow-color" class="boxShadowColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_BOX_SHADOW_COLOR  ?>" value="<?php echo $boxShadowColor ?>" />                                        
                                    </td>
                                </tr> 
                                <tr>
                                    <th valign="top" style="padding: 0;" class="label" scope="row"></th>
                                    <td class="field" style="padding: 0 10px;">
                                        <?php 
                                            $textShadowColor = ( isset( $wikiembed_options['ppe-cta-button-text-shadow-color'] ) ) ? $wikiembed_options['ppe-cta-button-text-shadow-color'] : PPE_CTA_BUTTON_TEXT_SHADOW_COLOR;
                                        ?>
                                        <span style="width: 150px;display: inline-block;">Text Shadow Color:</span> <input type="text" id="ppe-cta-button-text-shadow-color" class="textShadowColor rdp-we-color-picker" data-default-color="<?php echo PPE_CTA_BUTTON_TEXT_SHADOW_COLOR  ?>" value="<?php echo $textShadowColor ?>" />                                        
                                    </td>
                                </tr>                                     
                                    <tr>
                                        <td></td>
                                        <td><br />
                                                <input type="button" value="Insert into Post/ Page" onclick="pp_embed_send_to_editor();" id="btnPPEmbed" class="button">
                                        </td>
                                    </tr>                                    
                                </tbody>
                            </table>
                                                        
                        
                        </div>
                    </div>                    
                    
                    </div><!-- wiki-embed-tabs -->
		</div><!-- end #wiki_embed_form -->
		<?php
	}
}
