=== Coupon Creator ===
Contributors: brianjessee
Plugin Name: Coupon Creator
Plugin URI: http://couponcreatorplugin.com
Tags: custom post type, coupon, shortcode
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=W6FGHL2BUNY2W&lc=US&item_name=Coupon%20Creator&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 3.9
Tested up to: 4.2.2
Stable tag: 2.0.3
License: GPLv2
License URI: http://www.opensource.org/licenses/GPL-2.0

Create coupons to display on your site by using a shortcode.

== Description ==

> <strong>Support on WordPress.org</strong> is for troubleshooting bugs and related issues. The forums are monitored and replied to within one week's time.
>
> If you are looking for direct support please check out [Coupon Creator Pro](http://cctor.us/hpcctor)

Create your own coupon with the Coupon Creator for WordPress or upload an image of a coupon instead.

[Check out a demo gallery of Coupon Creator Features!](http://cctor.us/1Kpjgif)

Watch this quick video to see the Coupon Creator in Action:

https://www.youtube.com/watch?v=oTa7puu7t24

<h4>Coupon Creator Pro 2.0 Now Available!</h4>
[Get a Pro License](http://cctor.us/hpcctor) with a visual editor for the coupon terms, counter, six (6) more style options, custom coupon sizing,text overrides, and more with 1 year of updates and support.

<h4>Coupon Creator Pro 2.0 Features Include:</h4>
* Visual editor to easily style the term's content on your site
* Display the Print View in a Popup for any coupons and print directly from the Popup
* Use the View Shortcodes to display content in the Shortcode View or the Print View only
* Create and Display WooCommerce Coupons from the Coupon Creator Editor
* Set a Counter per coupon to expire the coupon after a limit has been reached
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

Find examples of coupons on the [Coupon Creator Home Page](http://cctor.us/hpcctor)

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

1. Coupon Editor
2. Expiration Tab in Coupon Editor
3. Image Coupon Tab in Coupon Editor
4. Coupon List
5. Coupon Inserter
6. Coupon Options

== Changelog ==
= 2.0.3 May 21st, 2015 =
* Fixed Coupon Inserter so categories show in dropdown
* Fixed status variable undefined notices
* Removed the_content filter from terms and manually run each function to remove conflicts with other plugins such as JetPack

= 2.0.2 April 9th, 2015 =
* Fixed tabs on reload of options or editor it returns you to the same tab
* Secured wp_remote_get function with esc_url_raw on add_query_arg call

= 2.0.1 April 9th, 2015 =
* Fixed so permalinks are flushed on activate and deactivate
* Fixed undefined notice error when saving coupon options

= 2.0 April 8th, 2015 =
* Added column in coupon list to mention if the coupon is showing of not showing based on expiration date
* Added current date from this function current_time('mysql') under the expiration date for reference and date formats to the default format chosen in the options
* Added numeric check for image uploads instead of using text sanitize
* Added -webkit-print-color-adjust: exact; to the CSS to help print background colors in Webkit Browsers, does not work in FireFox or IE. The user has to choose to print background images and colors in the browser
* Changed support fields in option and meta sections to use same information instead of four (4) different ones
* Added new styling to the support links
* Added coding to change Admin Tabs to Accordion when the div width cannot fit the tabs
* Added Option to remove wpautop from running on terms fields by default it is on thanks to this snippet - http://www.wpcustoms.net/snippets/remove-wpautop-custom-post-types/
* Added DONOTCACHEPAGE Constant to Print Template
* Updated Colorbox to 1.5.14
* Fixed Undefined property: stdClass::$delete_posts in Coupon List
* Fixed save_post hook priority as some plugins caused the custom fields to not save
* Fixed bug where Date Format does not save to Month First if Day First is the Default
* Fixed textarea width
* Removed extract function from the WordPress Settings API functions and replaced with arrays instead, this removes the last use of the extract function from the Coupon Creator

= 1.90 November 20th, 2014 =
* Major update to all coding
* Added a hook templating system to modify the shortcode and print templates
* Added coding for licenses
* Added hooks for Option and Meta additions from add ons
* Update Coupon List and Individual Coupon Information to make it easier to see the expiration
* Added more translation fields
* Added tab system for both Options and Meta fields to make it easier to edit
* Added coupon capabilities
* Added shortcode to the coupon editor page and to the coupon admin list
* Made entire coupon a link

= 1.80 July 7th, 2014 =
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

= 1.70 April 4th, 2014 =
* Rewrite of entire plugin to Object-oriented programming
* Update Color Picker to lastest version
* Change Image Uploader to the latest version of WordPress Media Uploader
* Added Setting Page with ability to change slug of coupon permalinks
* Added custom columns to list view of all coupons
* Added localization for admin and frontend
* Replaced deprecated php split function

= 1.60 March, 5th 2015 =
* Updated Styling to make the coupons more flexible in size
* Updated and Added Responsive Styling with basic support still for IE7
* Added a html comment that shows when a coupon is expired
* Added version numbers to the scripts and stylesheets
* Added New Q&A to the FAQ
* Updated Colorbox to 1.4.37
* Changed the single_template filter to template_include that was causing 404 errors in some themes

= 1.50 February 13th, 2014 =
* Added Translation using the standard WordPress method
* Added Spanish Translation thanks to Carmen in Miami
* Cleaned up coding to prepare for a rewrite

= 1.45 October 23rd, 2013 =
* Fixes issue where check box uncheck does not save.

= 1.41 August, 20th 2013 =
* Added Post Reset Function to the shortcode to fix an issue preventing comments from showing

= 1.40 August 14th, 2013 =
* Added a loop option to the shortcode to display more then one coupon
* Added coupon categories to use with the loop option of the shortcode
* Fixed css for box-sizing: content-box for the coupons or in themes like Twenty Thirteen the styling broke
* Added plugin version into WordPress Database options for future updates
* Updated Colobox script and fixed background images

= 1.37 January 6th, 2013 =
* Fixed SVN to latest version

= 1.35 January 6th, 2013 =
* Changed Insert Coupon Icon on Editor for WordPress 3.5
* Added CSS for inside .widget-wrap to fit 100% into space
* Coupon Images are now links to larger view. Thanks to the coding from Darin of VzPro
* Made the Click to Open in Print View font a little larger

= 1.31 =
* Modifed Click to Open in New Window Coding so it will open new window without javascript.
* Note this may not open a new window in all browsers.

= 1.30 July 21st, 2012 =
* Added checkbox to ignore expiration date so coupon will always display one website
* "Expire On" will not show if no expiration is added
* Added some more comments to coding and fixed line spacing issue
* Added donation link

= 1.20 July 16th, 2012 =
* Bug fixes to remove php notices in shortcode and in meta box

= 1.1.5 =
* Fixed SVN to latest version

= 1.1 =
* Bug Fixes preventing images, js, and css from loading - Thanks for heads up from Tom Ewer of WPMU.org

= 1.0 July 8th, 2012 =
* Initial Release and 1st Version and 1st Plugin!

== Upgrade Notice ==
= 2.0.3 =
Fixed Coupon Inserter categories dropdown, improves validation, adds a url meta field for Pro.

== Frequently Asked Questions ==
<h4>What if I have support questions?</h4>
Please ask on the [Coupon Creator Support Forum](http://wordpress.org/support/plugin/coupon-creator) on WordPress. For Pro users, please visit [Coupon Creator Pro](http://cctor.us/hpcctor). On CouponCreatorPlugin.com there are documentation on the css and hooks of the coupon creator as well as more answers to questions and premium tutorials as well.

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

For more answers as well as documentation please visit [Coupon Creator Pro](http://cctor.us/hpcctor)
