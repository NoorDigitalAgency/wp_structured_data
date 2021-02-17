=== Datafeedr Template ===
Contributors:
Tags:              structured, data
Requires at least: 3.0.0
Tested up to:      5.6.0
Stable tag:        1.3.2
Requires PHP:      5.3.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Simple insertion of Structured Data

== Description ==

This plugin inserts Structured data to wp_head

== Installation ==

1. Visit [https://github.com/NoorDigitalAgency/wp_structured_data/releases](https://github.com/NoorDigitalAgency/wp_structured_data/releases) and download zip
2. Upload zip file in wp plugin uploader
3. Install


== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.1.4 =
* Added abillity to target pages by absolute url, relative url or slug.

= 1.1.5 =
* patch on min required wp core/php and tested wp core v

= 1.1.6 =
* patch updated composer

= 1.1.8 =
* patch updated. Fixed array syntax cuz it caused fatal error on some wp installs

= 1.1.9 =
* patch updated. Priority on wp_head to inject structured data

= 1.1.92 =
* patch updated. Array selector causing some errors. replaced reset() select first by $array[0] selector

= 1.1.93 =
* patch updated. Array check on dataloader

= 1.2.0 =
Make use of wp_print_scripts instead of prev wp_head hook.

= 1.2.1 =
* Patch: Uses foreach instead of array_filter and utf for json_encode.

= 1.3.0 =
* Integration to yoast structured data for FAQPages.

= 1.3.1 =
* Patch: gen autoload.

= 1.3.2 =
* Patch: Multisite support.

== Arbitrary section ==