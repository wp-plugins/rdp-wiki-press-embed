=== RDP Wiki-Press Embed ===
Contributors: rpayne7264, enej, ejackisch, devindra, ctlt-dev, ubcdev
Tags: mediawiki, wiki, wiki-embed, embed, content framework, wiki inc, pediapress, pediapress embed
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 1.2.0

RDP Wiki-Press Embed lets you embed Media Wiki pages in to your site, from sites like Wikipedia, and PediaPress book pages.

== Description ==

RDP Wiki-Press Embed will pull content from any Media Wiki website (such as wikipedia.org) and from PediaPress.
It strips and reformats the content, allowing you to supply some arguments to dictate how this works.

Forked from: [Wiki Embed plugin](http://wordpress.org/plugins/wiki-embed/  "Wiki Embed plugin").

== Frequently Asked Questions ==

= Could you use this to replicate the whole of Wikipedia = 

Maybe, but why would you want to? That is not what the tool was designed to do. 


== Usage ==

RDP Wiki Embed is implemented using the shortcode [wiki-embed]. It accepts the following arguments:

* url: (required) the web address of the wiki article that you want to embed on this page.
* no-edit: Hide the "edit" links from the wiki.
* no-contents: Hide the page's contents box.
* no-infobox: Hide any infobox that appears on the wiki for this page.
* tabs: Replaces the sections of the wiki article with tabs.
* accordion: Replaces the sections of the wiki article with an accordion. This option cannot be used as the same time as 'tabs'.

Example:
[wiki-embed url="http://en.wikipedia.org/wiki/Example" no-edit no-contents no-infobox accordion]

For embedding PediaPress pages, only the url argument is supported.


== Configuration ==

Settings for the plugin can be found in 'Wiki Embed' -> 'Settings'.
Here you can enable/disable various features, define shortcode defaults, and configure some global settings for the plugin.


== Installation ==

1. Upload `rdp-wiki-embed` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Change the wiki embed settings to your liking

= Extra =
To make everything pretty, add a wiki.custom.css and pediapress.custom.css file. Start with the wiki.custom-sample.css and pediapress.custom-sample.css files located in the 'resources/css/' folder.


== Screenshots ==

1. A page that list all the wiki content that is embedded on the site. 
2. A look at the wiki embed settings page. 
3. Wiki page shortcode embed helper form
4. PediaPress book shortcode embed helper form


== Changelog ==

= 1.2.0 =
* REFACTOR: re-worked shortcode pop-up form to use tabs and allow creation of PediaPress shortcodes, with attributes to override global settings.
* REFACTOR: re-worked class RDP_WE_PPE to handle shortcode attributes
* REFACTOR: removed label text from media button so that it now only displays the Wikipedia icon
* REFACTOR: added a short circuit to main init() function to prevent links to front-end scripts and styles from being generated on the admin side
* FIX: fixed bug that corrupted AJAX return results
* FIX: changed screenshot #3
* UPDATE: added screenshot #4

*Changed files:*

* readme.txt
* screenshot-3.png
* WikiEmbed.php
* admin/admin-overlay.php
* admin/settings-page.php
* resources/rdpWEPPE.php
* resources/css/pediapress.common.css

*New files:*

* screenshot-4.png
* admin/css/jquery-ui.css
* admin/css/jquery-ui.theme.min.css
* admin/js/script.admin-overlay.js

= 1.1.0 =
* FEATURE: added settings to disable PediaPress book TOC links
* FEATURE: added setting to show/hide PediaPress book TOC
* FEATURE: added lightbox with iframe to PediaPress book cover images and 'Add to Cart' button
* REFACTOR: fixed the colorbox skin
* REFACTOR: fixed layout issues on the settings page
* FIX: updated screenshot #2

*Changed files:*

* readme.txt
* WikiEmbed.php
* screenshot-2.png
* resources/rdpWEPPE.php
* admin/settings-page.php
* resources/css/colorbox.css

*New files:*

* resources/js/pediapress-overlay.js
* resources/css/images/border1.png
* resources/css/images/border2.png
* resources/css/images/loading.gif

= 1.0.1 =
* updated screenshot #2

= 1.0.0 =
* FEATURE: added wiki content overwrite functionality
* FEATURE: added PediaPress content overwrite functionality
* FEATURE: added global overwrite functionality for pages not containing the [wiki-embed] shortcode
* FEATURE: added 'No Caching' settings option
* FEATURE: added check for custom CSS files located in resources/css folder
* FIX: re-worked code to eliminate some 'Undefined index' notices when reading from various variables

*Changed files:*

* readme.txt
* WikiEmbed.php
* admin/settings-page.php
* resources/css/wiki-embed.css
* resources/simple_html_dom.php

*New files:*

* resources/css/pediapress.common.css
* resources/css/pediapress.custom-sample.css
* resources/css/wiki.custom-sample.css
* resources/js/wiki-embed-overwrite.js
* resources/js/script.wcr.js
* resources/js/url.min.js
* resources/rdpWEPPE.php

= Inherited from Original Wiki Embed Plugin =
= 1.4.5 =
* wordpress search queries will now also search wiki-embedded content.

= 1.4.4 =
* allowing to display object and param tags so that we can embed kultura videos

= 1.4.3 =
* wiki_embed cache now not auto loaded.
* better escaped content that is coming from the the wiki
* removed unwanted site_options 
* bug fix: UTF encoding

= 1.4.2 =
* deploy script didn't quite work trying with a different user

= 1.4.1 =
* Bug fix: Prevents the DOMDocument parser from giving PHP Warnings if its given bad HTML from the source page

= 1.4 =
* Enables ability to split article into accordion (via jQuery UI)
* Rewrote function that parses/renders html to no longer use Simple HTML DOM parser as it uses a lot of resources 
* Updated caching: Now if the remote page can't be accessed, cached content will be served if it exists, even if it is past its expiry date.
* More caching: Now if the cache expires the page will show the cached content one last time and refresh it immediately after. This means the user should never really face delays for the wiki content is to be retrieved and parsed.
* Fixed overlay view
* The refresh of the page happens after the page has been set to the browser. 
* Skipping version 1.3 since there are a lot of change in version 1.4

= 1.2.4 =
* Bug fix: Causes errors on 404 pages. Links stop working. Now fixed
* removed un-necessary files

= 1.2.3 =
* Settings page bug fix, wasn't working properly. 
* Always ignore files that link to pdf documents, 
* convert &amp; to & 

= 1.2.2 = 
* removed unnecessary javascript files
* add files for css and js on admin side
* lots of bug fixes 
* better url recognition
* bug fix for table of content anchor linking 

= 1.2.1 = 
* bug fix
* #toc links weren't ignored properly 
* force styles that might not be present

= 1.2 = 
* Added a security feature that only allows certain sites to be wiki embedable in your site.
* Bug fixes, TOC is not hijacked by js any more and is treated as an internal link, update the overlay to use HTML5
* allow default a get parameter to be passed to the url. so you can do stuff like [wiki-embed url='http://en.wikipedia.org/wiki/%page%' get=page default_get=WordPress]
* and now if you go to the page that has that shortcode and pass in a ?page=English in the url
* it will embed the http://en.wikipedia.org/wiki/English instead of the default http://en.wikipedia.org/wiki/WordPress
* allow for plan html view by going to the {siteurl}/wp-admin/admin-ajax.php?url={encoded_url}&action=wiki_embed&plain_html=1
* this make the wiki embed act like a scraper.

= 1.1 = 
* Bug fix will display the admin overlay again, this bug occurs only when wiki embed is network activated 

= 1.0 = 
* removed unneeded code 
* improved setting menu navigation 
* improved Wiki Embed List | added the ability to see embeds that don't have a target url easier
* bug fix. embedded images are not conceded as internal links any more 
* bug fix. editor can now save settings. Added email to setting to which notifications are sent to if a visitor stumbles on a link that is not part of the navigation. 
* improvement easier to add target urls to wiki embeds. 
* version bumped up to 1.0 
* ajaxurl changed to wiki_embed_ajaxurl 
* version bumped up to 1.0

= 0.9.1 = 
* Pagination added 
* Icons added 
* How the content gets saved and stored improved. For example if you are quering the same wiki contetent only you will only request the content from the wiki once.
* Default Settings added
* This is the version before that is undergoing verification testing

= 0.9 =
* Is the pre production release, please help us test it. 
* Default Settings added
* This is the version before that is undergoing verification testing

= 0.9 =
* Is the pre production release, please help us test it.
 
 
