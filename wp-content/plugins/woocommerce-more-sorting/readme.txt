=== More Sorting Options for WooCommerce ===
Contributors: algoritmika,anbinder
Tags: woocommerce,sorting
Requires at least: 4.4
Tested up to: 4.7
Stable tag: 3.0.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add new custom, rearrange, remove or rename WooCommerce sorting options.

== Description ==

Plugin extends WooCommerce by adding new **custom sorting** options:

* Sort: A to Z
* Sort: Z to A
* SKU: Ascending
* SKU: Descending
* Stock Quantity: Ascending
* Stock Quantity: Descending

With this plugin you can also **rearrange order** of sorting options (including WooCommerce default) on frontend.

Premium version also allows to **rename or completely remove** default WooCommerce sorting options.

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!
* Drop us a line at http://www.algoritmika.com.

= More =
* Visit [More Sorting Options for WooCommerce](http://coder.fm/item/woocommerce-more-sorting-plugin) plugin page.

== Installation ==

1. Upload the entire 'woocommerce-more-sorting' folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. More sorting options will be automatically added to WooCommerce plugin.

== Frequently Asked Questions ==

= Can I change text for added sorting options? =

Yes, just go to "WooCommerce > Settings > More Sorting".

= Will added sorting options work as default options? =

Yes, You can set added sorting options work as default - just go to "WooCommerce > Settings > Products > Display > Default Product Sorting".

== Screenshots ==

1. Admin options.

== Changelog ==

= 3.0.0 - 13/12/2016 =
* Fix - `load_plugin_textdomain()` moved from `init` hook to constructor.
* Dev - Remove All Sorting - Empty `loop/orderby.php` template added to ensure maximum compatibility;
* Dev - Remove All Sorting - Storefront theme compatibility added.
* Dev - Remove All Sorting - `init` hook replaced with `wp_loaded` for `remove_sorting()`.
* Dev - "Rearrange Sorting" section added.
* Dev - "Default Sorting Options" section added.
* Dev - Code refactoring. "Custom Sorting" - "Enable Section" checkbox added. Functions renamed etc.
* Tweak - Plugin renamed.

= 2.1.0 - 08/10/2016 =
* Dev - Version variable added.
* Dev - Multisite support added.
* Fix - Coder.fm link fixed.
* Tweak - Plugin renamed.
* Tweak - Author added.
* Tweak - Readme.txt header updated.
* Tweak - Language (POT) file added.

= 2.0.1 - 27/08/2015 =
* Dev - Remove All Sorting - Blaszok theme compatibility added.

= 2.0.0 - 29/07/2015 =
* Dev - Option to treat SKUs as numbers or texts when sorting, added.
* Dev - Sorting by stock quantity - added.
* Dev - Major code refactoring. Settings are moved to "WooCommerce > Settings > More Sorting Pro".

= 1.0.2 =
* 'Remove any sorting option' option added
* Sort by SKU option added
* Default sorting bug fixed

= 1.0.1 =
* 'Remove all sorting' option added

= 1.0.0 =
* Initial Release

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
