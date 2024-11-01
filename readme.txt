=== Shopp Product Search ===
Contributors: dgilfoy, ivycat
Tags: widgets, sidebar, role based
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 1.0.0

==Short Description ==

Add searching capabilities for Shopp plugin to your WordPress Search bar.

==Description==
A plugin that searches the title or description for items searched for in the WordPress search bar. 

This plugin does require some configuration - namely altering the search template file. 

== Notes ==

Plugin is depending upon theme styling.  Version 1.0 of this plugin does not contain native styles.  If you are curious as to the reasoning behind this, check out:  

http://nimbu.in/p/wordcampseattle/

This is a minimal plugin, function over form.  If you would like to extend it, or would like us to extend it in later versions, feel free to contact us at admins@ivycat.com.  We do custom plugins as well as upgrades and features for existing plugins.

== Installation ==

1. Upload the entire shopp-product-search directory to your plugins folder 
2. Click Install Plugin in your WordPress plugin page
3. ??? Profit ???

== Usage ==

Activate and add:
 if( function_exists( 'simple_shopp_product_search' ) ){
        simple_shopp_product_search( have_posts(), true, 'shop' );
 }  
 
I'd recommend replacing: if( !have_posts() ) { // no posts found message } -

with the above product search function.
    
There are a couple filters for customization:

apply_filters( 'shopp_search_wrapper_markup', $markup_array );

These allow the markup of the output to be altered. See get_wrapper_markup function for details. There are two default markups included - normal xhtml and html5.  

simple_shop_product_search function paramaters: 

have_posts(): Pass this along to add "no posts if neither blog results or product results are found, doesn't display "no posts" if there are products but no site wide results.

html5 (default: false) : returns html5 markup if true, otherwise defaults to standard xhtml.

product_url_segment: Your default page for Shop products.  For example, our examples is shop, yours may be products.



Contact me if you want some more filters or actions added.

== Screenshots ==

1. The Plugin's output.  Default is to put a span around the searched word.  To capitalize the word, we recommend styles - text-transform perhaps


== Frequently Asked Questions ==

Q: What is the point of this plugin?.  
A: Shopp doesn't come with a product search, so we made one. It is simple and could be expanded upon, but this works well and is lightweight. 


== Changelog ==



== Upgrade Notice ==

Latest versions mean latest security, latest features and the best time!

== Road Map ==

1. Suggest a feature...


