=== Coupon Creator ===
Contributors: brianjessee
Plugin Name: Coupon Creator
Plugin URI: http://jesseeproductions.com/coupon-creator/
Tags: custom post type, coupon, shortcode
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=W6FGHL2BUNY2W&lc=US&item_name=Coupon%20Creator&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 3.6
Tested up to: 3.9.1
Stable tag: 1.90 Beta
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0

Create coupons to display on your site by using a shortcode.

== Description ==

Create your own coupon with the Coupon Creator for WordPress or upload an image of a coupon instead.

<h4>How to Create a Coupon</h4>
Create a coupon by going to the coupon custom post type and filling in all the settings in the custom meta box.

Insert the coupon into a post or page using the shortcode inserter above the content Editor.

Coupon displays until the expiration date chosen by you or you can check the Ignore Expiration Checkbox and the coupon will display on the site past the expiration date or with no date at all.

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

Assign categories to a coupon and with the loop option only display coupons from a specific category.

category - add the category name to display coupons only from it

For example if you have a category called "Coupon Home Page", call it by:

category="Coupon Home Page"

<h4>Coupon Shortcode Inserter</h4>

Above the post editor click the "Add Coupon" button to open the inserter.

Select an individual coupon or coupon loop.

If you select the coupon loop, an option will appear to select a coupon category for the loop or you can leave it blank for all coupons (default).

The third option to select is the couponalign.

Once you have all the options selected, press "Insert Coupon" to insert the shortcode into the editor.

<h4>Examples</h4>

Find examples of coupons on the [Coupon Creator Home Page](http://jesseeproductions.com/coupon-creator/)

<h4>Coupons in Text Widgets</h4>

Coupons will work in Sidebar Text Widgets, but you must add

add_filter('widget_text', 'do_shortcode');

To your theme's function.php

<h4>Coupon Options</h4>

On the options page set default colors for new coupons. 

Also options for nofollow on the links and templates along with an option to hide the click to print link. 

With Version 1.80 you can add custom css in the options and it will modify both the shortcode coupon and the print template. 

<h4>Coupon Creator Pro - Comming Soon</h4>
New Features in the works include a visual editor to make coupons, background coupon images, editable link texts, more themes, more control from the settings page, custom css, premium support, and more!


== Installation ==

1. Upload '/coupon_creator/' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create a coupon under the coupon custom post type and insert shortcode into content using shortcode
	[coupon couponid="xx" coupon_align="cctor_aligncenter" name="Coupon Name"]

== Screenshots ==

1. Coupons Displayed on a Website
2. Custom Meta Box to Create a Coupon with Open Date Picker
3. Custom Meta Box with Image as the Coupon
4. Custom Meta Box to Create a Coupon with Open Color Picker
5. Coupon Shortcode Inserter on Editor
6. Shortcode in WordPress Editor

== Changelog ==
= 1.80 =
* Added Expiration Date and If Ignore Expiration is on to the Coupon Listing
* Added class for Options including, Custom CSS, Default, Colors, nofollow on print link, hide print link, improved permalink 
* Added new options for all coupons including adding custom CSS.
* Added class to manage meta fields
* Added various hooks and filters to upcoming
* Changed image size to only set the width of image and allow for different heights
* Changed Version check to alert message to make it more noticeable
* Coding Updates and fixes throughout
* Fixed Permalink Flush on Activation of Plugin
* Fixed filemtime error on Windows Servers for the Print Template

= 1.70 =
* Rewrite of entire plugin to Object-oriented programming
* Update Color Picker to lastest version
* Change Image Uploader to the latest version of WordPress Media Uploader
* Added Setting Page with ability to change slug of coupon permalinks
* Added custom columns to list view of all coupons
* Added localization for admin and frontend
* Replaced deprecated php split function

= 1.60 =
* Updated Styling to make the coupons more flexible in size
* Updated and Added Responsive Styling with basic support still for IE7
* Added a html comment that shows when a coupon is expired
* Added version numbers to the scripts and stylesheets
* Added New Q&A to the FAQ
* Updated Colorbox to 1.4.37
* Changed the single_template filter to template_include that was causing 404 errors in some themes

= 1.50 =
* Added Translation using the standard WordPress method
* Added Spanish Translation thanks to Carmen in Miami
* Cleaned up coding to prepare for a rewrite

= 1.45 =
* Fixes issue where check box uncheck does not save.

= 1.41 =
* Added Post Reset Function to the shortcode to fix an issue preventing comments from showing

= 1.40 =
* Added a loop option to the shortcode to display more then one coupon
* Added coupon categories to use with the loop option of the shortcode
* Fixed css for box-sizing: content-box for the coupons or in themes like Twenty Thirteen the styling broke
* Added plugin version into WordPress Database options for future updates
* Updated Colobox script and fixed background images

= 1.37 =
* Fixed SVN to latest version

= 1.35 =
* Changed Insert Coupon Icon on Editor for WordPress 3.5
* Added CSS for inside .widget-wrap to fit 100% into space
* Coupon Images are now links to larger view. Thanks to the coding from Darin of VzPro
* Made the Click to Open in Print View font a little larger

= 1.31 =
* Modifed Click to Open in New Window Coding so it will open new window without javascript.
* Note this may not open a new window in all browsers.

= 1.30 =
* Added checkbox to ignore expiration date so coupon will always display one website
* "Expire On" will not show if no expiration is added
* Added some more comments to coding and fixed line spacing issue
* Added donation link

= 1.20 =
* Bug fixes to remove php notices in shortcode and in meta box

= 1.1.5 =
* Fixed SVN to latest version

= 1.1 =
* Bug Fixes preventing images, js, and css from loading - Thanks for heads up from Tom Ewer of WPMU.org

= 1.0 =
* Initial Release and 1st Version and 1st Plugin!

== Upgrade Notice ==
= 1.81 =
New options added including custom css on the Options Panel, plus bug fixes and improved coding.
If you set a custom permalink, go to the options page and add it back to the options page and resave for it to work. 

== Frequently Asked Questions ==
<h4>How big of an image is the coupon?</h4>
There are two sizes, but the image uploaded should be 400 pixels by 200 pixels to display correctly.

<h4>What if I have support questions?</h4>
Please use the [Coupon Creator Support Forum](http://wordpress.org/support/plugin/coupon-creator) on WordPress.

<h4>How do I customize the coupon (make bigger, change layout, etc...)?</h4>
There is no direct way to customize the look of the coupon in the WordPress dashboard. All customizations would involve modifying the css, the shortcode file, and the single template file. If you are interested in a custom design please contact [Brian](http://jesseeproductions.com/contact/) about a price.

<h4>Can I have both an image and text in the coupon? </h4>
You can add html coding to the discount box, but there is no visual editor to do it.

<h4>My coupon was working and now it does not display, how can I fix it? </h4>
Please check if the expiration date has passed and whether or not the ignore expiration is checked. If it is checked or the date is still in the future, please post on the Support Forum in WordPress about the issue and include a link to the page so I can check for errors.

<h4>How do I fix 404 Errors on the Print View of the Coupons? </h4>
The cause of the conflict can be hosting, plugin, or theme related.
Try first to resave your permalinks and then check the Print View.
If that does not work, try disabling plugins and/or changing the themes and check again.
If none of that works, it could be a conflict with the hosting setup.

<h4>How to do I translate the Coupon Creator? (1.50) </h4>
To add a translation, please reply to the Translation Post with the language and country. The post is in the Support Forum and it will be added to the next version of the Coupon Creator.

= Please translate the following phrases: =
*   Click to Print
*   Click to Open in Print View
*   Expires On:

= Current Translations included: =
*   English (Default)
*   Spanish (Thanks to Carmen in Miami)

<h4>Where does the shortcode go? </h4>
Insert the shortcode in the content editor of a post, page, or custom post type.

<h4>How can I display coupons using the shortcode in a sidebar text widget? </h4>
Add this coding to your theme's function.php:

add_filter('widget_text', 'do_shortcode');

<h4>How can I prevent coupons from appearing in a site search? </h4>
By default the coupons are excluded from search. However, if you add coding to show custom post types in the search, you would have to exclude the custom post type, cctor_coupon.

<h4>How can I remove the Click to Open in Print View or Click to Print Text? </h4>
You either modify the shortcode and the template file or use CSS to hide the display of either.

<h4>Does the Coupon Creator have custom capabilities for WordPress users? </h4>
No, there are no custom capabilities for users. The plugin is designed to work on single sites and not directory sites.

<h4>Can I change cctor_coupon in the print view permalink? </h4>
With version 1.70 you can change the slug of the permalink in the settings section of the plugin. Found under the Coupon Creator Heading.

<h4>The Coupon Inserter, Image Uploader, the Expiration Date Picker, or the Color Pickers are not working, what is wrong?</h4>
Most likely this is a JavaScript error and could be caused by another plugin or your theme. Please check the Developer Console for your browser and see if there is an error. If you post the error on the support forum I maybe able to help. Otherwise try disabling plugins and changing themes to try and find the sources as well.

