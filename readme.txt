=== Coupon Creator ===
Contributors: brianjessee
Plugin Name: Coupon Creator
Plugin URI: http://couponcreatorplugin.com
Tags: custom post type, coupon, shortcode
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=W6FGHL2BUNY2W&lc=US&item_name=Coupon%20Creator&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 4.2
Tested up to:  4.7
Stable tag: 2.5
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0

Create coupons to display on your site by using a shortcode.

== Description ==

> <strong>Support on WordPress.org</strong> is for troubleshooting bugs and related issues. The forums are monitored and replied to within one week's time.
>
> If you are looking for direct support please check out [Coupon Creator Pro](http://cctor.link/JIGHR)

Create your own coupon with the Coupon Creator for WordPress or upload an image of a coupon instead.

[Check out a demo gallery of Coupon Creator Features!](http://cctor.link/IjIV1)

Watch this quick video to see the Coupon Creator in Action:

https://www.youtube.com/watch?v=aGoxJ3TBRhk

<h4>Coupon Creator Add-ons 2.5 Features Include: (included with Pro Business and higher license levels)</h4>
* Reveal Code Feature
* Lower Third Advanced Template
* Highlight Advanced Template
* Lower Third Advanced Template
* Create and Display WooCommerce Coupons from the Coupon Creator Editor

<h4>Coupon Creator Pro 2.5 Features Include:</h4>
* [couponloop] shortcode, filter bar, and template system, to give you control over customizations without losing changes on updates
* Quick and Bulk edits for the expiration and counter fields
* Border themes, Dotted Border, Stitched Border, Saw Tooth Border (modern browsers and IE 10+), and None Option
* Recurring Expiration; set an expiration for the end of the month and have it automatically change to the end of the next month
* X Days expiration to set a period to redeem a coupon from the day of printing it
* Range expiration to display a start and end date for the coupon to be valid such as valid 11/11/17 thru 12/11/17
* Ability to insert columns and rows into the content editor. Options include, two column combinations, three column combinations, four columns, and rows
* Pro Inserter has the ability to search coupons and categories in the dropdown
* Visual editor to easily style the term's content on your site
* Display the Print View in a Popup for any coupons and print directly from the Popup
* Use the View Shortcodes to display content in the Shortcode View or the Print View only
* Set a Counter per coupon to expire the coupon after a limit has been reached or use as an unlimited counter
* Change “Expires on:”, “Click to Open in Print View”, and “Print the Coupon” for all coupons
* Set coupon size for the Shortcode View and the Print View for all coupons including the Image Coupon
* Override “Click to Open in Print View” text and link per coupon
* Override “Print the Coupon” text and link per coupon
* Disable the Print View per coupon
* Add your Google Analytics Code to the Print Template from the Coupon Options
<h4>How to Create a Coupon</h4>
Create a coupon by going to the coupon custom post type and filling in all the settings in the custom meta box.

Insert the coupon into a post or page using the shortcode inserter above the content editor.

Coupon displays until the expiration date that is chosen by you or you can check the Ignore Expiration Checkbox and the coupon will display on the site past the expiration date or with no date at all.

<h4>Coupon Creator Shortcode</h4>

The Coupon Shortcode:
	[coupon couponid="xx" category="Category Name(optional)" coupon_align="cctor_aligncenter" name="Coupon Name"]

Manually replace fields in shortcode:

couponid - replace xx with ID of Coupon custom post

couponalign - align coupon options:  cctor_aligncenter,  cctor_alignnone,  cctor_alignleft, and  cctor_alignright

name -optional and for your reference only

<h4>Coupon Loop</h4>

Set couponid to "loop" to display all coupons. (couponid="loop")

All the coupons in the loop will use the same couponalign.

<h4>Coupon Categories</h4>

Assign categories to a coupon using the loop option only to display coupons from a specific category.

category - add the category name to display coupons only from it

For example, if you have a category called "Coupon Home Page", call it by:

category="Coupon Home Page"

<h4>Coupon Shortcode Inserter</h4>

Above the post editor, click the "Add Coupon" button to open the inserter.

Select an individual coupon or coupon loop.

If you select the coupon loop, an option will appear to select a coupon category for the loop or you can leave it blank for all coupons (default).

The third option to select is the couponalign.

Once you have all the options selected, press "Insert Coupon" to insert the shortcode into the editor.

<h4>Examples</h4>

Find examples of coupons on the [Coupon Creator Home Page](http://cctor.link/JIGHR)

<h4>Coupons in Text Widgets</h4>

Coupons will work in Sidebar Text Widgets, but you must add

add_filter('widget_text', 'do_shortcode');

To your theme's function.php

<h4>Coupon Options</h4>

On the options page set default colors for new coupons.

Another option for nofollow on the links and templates along with an option to hide the click to print link.

You can add custom css in the options and it will modify both the shortcode coupon and the print template.

== Installation ==

1. Upload '/coupon-creator/' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a coupon under the coupon custom post type and insert shortcode into content using shortcode
	[coupon couponid="xx" coupon_align="cctor_aligncenter" name="Coupon Name"]

== Screenshots ==

1. Coupon Examples
2. Expiration Tab in Coupon Editor
3. Coupon Editor
4. Coupon List
5. Coupon Inserter
6. Coupon Options

== Changelog ==

= 2.5.1 May 11th, 2017 =
* Add - German Translation for Front End Text, thanks Andreas!
* Add - cctor_is_coupon_taxonomy for checks if on a Coupon Taxonomy such as Category, Location, or Vendor

= 2.5 May 11th, 2017 =
* Add - A new Admin UI to modernize the look and make it easier to organize related fields
* Add - System Info Tab to get more details about a site setup for troubleshooting
* Add - Template system and move default and image coupons as two free options
* Add - License Key Activation and Deactivation from the Plugin List
* Add - Default template option for new coupons
* Add - Wisdom plugin for opt in to track plugin stats to help improve it
* Change - The Styles tab to be border and background tab
* Change - Move the field colors to be inline with the field they modify
* Add - A safety check for the Plugin Engine Version
* Add - A migration script to update existing coupons to the new template system
* Fix - Warnings for File Time on Print Stylesheet

= 2.4 November 7th, 2016 =
* Add - Modified Enter Title Here to Enter Admin Coupon Title per http://stephanieleary.com/2016/06/wordpress-hidden-gem-enter_title_here-filter/
* Add - Add the display of the Coupon Category terms per coupon in the admin list table.
* Add - Add the pngx as the basis for the plugins to make it more modular
* Tweak - Change Coupon Updated Messages to remove word Post and replace with Coupon per http://wordpress.stackexchange.com/questions/15357/edit-the-post-updated-view-post-link
* Fix - Fixed it so that if no category is selected with the inserter nothing is add to the shortcode.

= 2.3.1 August 16th, 2016 =
* Fix - Translations not loading, thanks to cahaubold for bringing up this issue

= 2.3 June 7th, 2016 =
* Add - expiration system as a new class to manage coupon display and expiration messages from a central source
* Add - message on coupon options and the individual editor pages to inform the user if there is an error that needs to be troubleshooted with a link to the guide to start that process
* Add - new expiration option with defaults to choose how the coupon will expired or if ignored
* Add - script to update coupons to the new expiration option
* Upgrade  - The Help System to sort videos to respective tabs to make it easier to find help and add written guide links that are available.
* Tweak - Move update scripts to their own class and add a update script for the new expiration class
* Tweak - Move the admin column methods into their own class
* Fix - search method from running on bbPress search results, thanks Dr Sagman for pointing this out
* Deprecate - cctor_expiration_check, cctor_expiration_and_current_date, and method get_cctor_support_core_infomation

= 2.2.1 April 12th, 2016 =
* Fix - deprecated notice for WordPress 4.5 from an unused function
* Fix - conflict with bbPress, which caused forums, topics and replies to not show, thanks Dr Sagman!
* Fix - capabilties not running on initial install

= 2.2 March 23rd, 2016 =
* Add - a check for the role before adding the coupon capabilities to prevent invalid argument warnings in the foreach statement, thanks Maxim
* Update - the capability system to match to existing capabilities to make it possible to modify Added defaults to the coupon option functions to enable critical fields to have a backup
* Add - flush or permalinks on version upgrade to precent 404 errors
* Add - option to prevent coupon creator from modifying the standard search query to remove coupons due to change in custom post type setup


= 2.1.2 December 7th, 2015 =
* Fix - bug on option page tabs due to changes in 4.4


= 2.1.1 September 13th, 2015 =
* Update - Text Domain

= 2.1 August 27th, 2015 =
* Add - base CSS to the print view to present a better layout of the text with more options in Pro to change font size, weight, and family.
* Add - option to disable the base CSS for the print view.
* Add - constant to prevent all coupons from opening in new windows or tabs - define( 'CCTOR_PREVENT_OPEN_IN_NEW_TAB', true );
* Add - PHP Date Validation when saving.
* Add - define('CCTOR_HIDE_UPGRADE', true); to hide Pro Upgrade Notices.
* Add - do_action( 'cctor_before_coupon_inner_wrap' , $coupon_id ); hook into the shortcode coupon.
* Add - function to update old image border radius field to the outer border.
* Add - update function to change the cctor_ignore_expiration value from on to 1 for older version upgrades.
* Update - deal CSS to this class cctor_deal instead of targeting a heading tag directly to enable use of h3 tags in content.
* Update combined sanitize functions into a class to enable future validation messages.
* Update - Coupon Options Tabs and Coupon Meta Tabs to detect if saved by php and use that to determine what tab to return to after a save attempt instead of detecting if message div exists with jQuery.
* Update - the styling of the Coupon Inserter based off new coding in Pro and added script to resize the thickbox based on the Content.
* Fix - an issue with default options not saving with Pro and added sanitization for defaults.
* Fix - custom permalinks change to make sure permalinks are flushed and the new slug is being used.
* Fix - select option defaults to work again.
* Fix - spelling on cctor_options_styles and cctor_options_scripts hooks.

= 2.0.3 May 21st, 2015 =
* Fix - Coupon Inserter so categories show in dropdown
* Fix - status variable undefined notices
* Remove - the_content filter from terms and manually run each function to remove conflicts with other plugins such as JetPack

= 2.0.2 April 9th, 2015 =
* Fix - tabs on reload of options or editor it returns you to the same tab
* Update - wp_remote_get function with esc_url_raw on add_query_arg call

= 2.0.1 April 9th, 2015 =
* Fix - so permalinks are flushed on activate and deactivate
* Fix - undefined notice error when saving coupon options

= 2.0 April 8th, 2015 =
* Add - column in coupon list to mention if the coupon is showing of not showing based on expiration date
* Add - current date from this function current_time('mysql') under the expiration date for reference and date formats to the default format chosen in the options
* Add - numeric check for image uploads instead of using text sanitize
* Add - -webkit-print-color-adjust: exact; to the CSS to help print background colors in Webkit Browsers, does not work in FireFox or IE. The user has to choose to print background images and colors in the browser
* Update - support fields in option and meta sections to use same information instead of four (4) different ones
* Add - new styling to the support links
* Add - coding to change Admin Tabs to Accordion when the div width cannot fit the tabs
* Add - Option to remove wpautop from running on terms fields by default it is on thanks to this snippet - http://www.wpcustoms.net/snippets/remove-wpautop-custom-post-types/
* Add - DONOTCACHEPAGE Constant to Print Template
* Update - Colorbox to 1.5.14
* Fix - Undefined property: stdClass::$delete_posts in Coupon List
* Fix - save_post hook priority as some plugins caused the custom fields to not save
* Fix - bug where Date Format does not save to Month First if Day First is the Default
* Fix - textarea width
* Remove - extract function from the WordPress Settings API functions and replaced with arrays instead, this removes the last use of the extract function from the Coupon Creator

= 1.90 November 20th, 2014 =
* Add - a hook templating system to modify the shortcode and print templates
* Add - coding for licenses
* Add - hooks for Option and Meta additions from add ons
* Update - Coupon List and Individual Coupon Information to make it easier to see the expiration
* Add - more translation fields
* Add - tab system for both Options and Meta fields to make it easier to edit
* Add - coupon capabilities
* Add - shortcode to the coupon editor page and to the coupon admin list
* Add - Entire coupon a link

= 1.80 July 7th, 2014 =
* Add - Expiration Date and If Ignore Expiration is on to the Coupon Listing
* Add - class for Options including, Custom CSS, Default, Colors, nofollow on print link, hide print link, improved permalink
* Add - new options for all coupons including adding custom CSS.
* Add - class to manage meta fields
* Add - various hooks and filters to upcoming
* Update - image size to only set the width of image and allow for different heights
* Update - Version check to alert message to make it more noticeable
* Fix - Permalink Flush on Activation of Plugin
* Fix - filemtime error on Windows Servers for the Print Template

= 1.70 April 4th, 2014 =
* Update - entire plugin to Object-oriented programming
* Update - Color Picker to lastest version
* Update - Change Image Uploader to the latest version of WordPress Media Uploader
* Add - Setting Page with ability to change slug of coupon permalinks
* Add - custom columns to list view of all coupons
* Add - localization for admin and frontend
* Fix - Deprecated php split function

= 1.60 March, 5th 2015 =
* Update - Styling to make the coupons more flexible in size
* Update - and Added Responsive Styling with basic support still for IE7
* Add - a html comment that shows when a coupon is expired
* Add - version numbers to the scripts and stylesheets
* Add - New Q&A to the FAQ
* Update - Colorbox to 1.4.37
* Update - the single_template filter to template_include that was causing 404 errors in some themes

= 1.50 February 13th, 2014 =
* Add - Translation using the standard WordPress method
* Add - Spanish Translation thanks to Carmen in Miami
* Update - Coding to prepare for a rewrite

= 1.45 October 23rd, 2013 =
* Fix - issue where check box uncheck does not save.

= 1.41 August, 20th 2013 =
* Add - Post Reset Function to the shortcode to fix an issue preventing comments from showing

= 1.40 August 14th, 2013 =
* Add - a loop option to the shortcode to display more then one coupon
* Add - coupon categories to use with the loop option of the shortcode
* Fix - css for box-sizing: content-box for the coupons or in themes like Twenty Thirteen the styling broke
* Add - plugin version into WordPress Database options for future updates
* Update - Colobox script and fixed background images

= 1.37 January 6th, 2013 =
* Fix - SVN to latest version

= 1.35 January 6th, 2013 =
* Update - Insert Coupon Icon on Editor for WordPress 3.5
* Add - CSS for inside .widget-wrap to fit 100% into space
* Add - Coupon Images are now links to larger view. Thanks to the coding from Darin of VzPro
* Update - the Click to Open in Print View font a little larger

= 1.31 =
* Update - Click to Open in New Window Coding so it will open new window without javascript. (Note this may not open a new window in all browsers.)

= 1.30 July 21st, 2012 =
* Add - checkbox to ignore expiration date so coupon will always display one website
* "Expire On" will not show if no expiration is added
* Add - some more comments to coding and fixed line spacing issue
* Add - donation link

= 1.20 July 16th, 2012 =
* Fix - fixes to remove php notices in shortcode and in meta box

= 1.1.5 =
* Fix - SVN to latest version

= 1.1 =
* Fix - Fixes preventing images, js, and css from loading - Thanks for heads up from Tom Ewer of WPMU.org

= 1.0 July 8th, 2012 =
* Initial Release and 1st Version and 1st Plugin!

== Upgrade Notice ==
= 2.5 =
Coupon Creator 2.5 includes numerous improvements and introduces Coupon Creator Add-ons. It is recommended to update both Coupon Creator and Coupon Creator Pro to 2.5 for them to work correctly.

== Frequently Asked Questions ==
<h4>What if I have support questions?</h4>
Please ask on the [Coupon Creator Support Forum](http://wordpress.org/support/plugin/coupon-creator) on WordPress. For Pro users, please visit [Coupon Creator Pro](http://cctor.link/JIGHR). On CouponCreatorPlugin.com there are documentation on the css and hooks of the coupon creator as well as more answers to questions and premium tutorials as well.

<h4>The Coupon Inserter, Image Uploader, the Expiration Date Picker, or the Color Pickers are not working, what is wrong?</h4>
Most likely this is a JavaScript error and could be caused by another plugin or your theme. Please check the Developer Console for your browser and see if there is an error. If you post the error on the support forum I maybe able to help. Otherwise, try disabling plugins and changing themes to try and find the sources as well.

<h4>How do I fix 404 Errors on the Print View of the Coupons? </h4>
The cause of the conflict can be hosting, plugin, or theme related.
First, try to resave your permalinks and then check the Print View.
If that does not work, try disabling plugins and/or changing the themes and check again.
If none of that works, it could be a conflict with the hosting setup.

<h4>My coupon was working and now it does not display, how can I fix it? </h4>
Please check if the expiration date has passed and whether or not the ignore expiration is checked. If it is checked or the date is still in the future, please post on the Support Forum in WordPress about the issue and include a link to the page so I can check for errors.

<h4>Where does the shortcode go? </h4>
Insert the shortcode in the content editor of a post, page, or custom post type.

<h4>How can I display coupons using the shortcode in a sidebar text widget? </h4>
Add this coding to your theme's function.php:

add_filter('widget_text', 'do_shortcode');

<h4>How can I prevent coupons from appearing in a site search? </h4>
By default the coupons are excluded from search. However, if you add coding to show custom post types in the search, you would have to exclude the custom post type, cctor_coupon.

<h4>How big of an image is the coupon?</h4>
There are two sizes, but the image uploaded should be 400 pixels by 200 pixels to display correctly.

Does the Coupon Creator have custom capabilities for WordPress users?
Yes, with version 1.90 custom capabilities have been added. See Documentation at CouponCreatorPlugin.com for more information.

<h4>Can I change cctor_coupon in the print view permalink? </h4>
With version 1.70 you can change the slug of the permalink in the Options section of the plugin. Coupon > Options > Permalink Tab

<h4>Can I have both an image and text in the coupon? </h4>
You can add html coding to the discount box, but there is no visual editor to do it.

<h4>How do I customize the coupon (make bigger, change layout, etc...)?</h4>
There is no direct way to customize the look of the coupon in the WordPress dashboard beyond the change of colors without doing custom coding. In Coupon Creator Pro, there are more options to modify the look of the coupons including background images, the visual editor for terms, set default sizes for coupons and image coupons as well, and many more options.

How can I remove the Click to Open in Print View or Click to Print Text?
With version 1.90 on this tab Coupons > Options > Link Attributes/Permalinks Tab you can choose to hide the Click to Open in Print View Link for all Coupons.

For more answers as well as documentation please visit [Coupon Creator Pro](http://cctor.link/JIGHR)
