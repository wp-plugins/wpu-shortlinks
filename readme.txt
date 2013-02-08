=== WPU Shortlinks ===
Contributors: parselearn
Donate link: http://wpu.ir/
Tags: shortlink , shorter link , link , wpu , url shortener , yourls , custom url , short link, short url, shorturl ,  url generator, url , uri shortner , social url , linker 
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 0.1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows automatic url shortening of post links using wpu.ir Services using the API recently provided by WP-Parsi.

== Description ==

Allows automatic url shortening of post links using [WPU.IR](http://wpu.ir/ "WP-Parsi Shortlinks System") Services using the API recently provided by WP-Parsi.

== Installation ==

1. Upload `wpu-shortlinks` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Shortcode (use in post editor): =
`[wpu]Post Shortlink[/wpu]`
Specific Post
`[wpu id=74]Wordpress for life :)[/wpu]`

= Function: =
`wpu_shortlink(post_id,display)`

Use in post loop:
`<a href="<?php wpu_shortlink() ?>">shortlink</a>`

Specific Post:
`<a href="<?php wpu_shortlink(74) ?>">shortlink</a>`

Request custom URI:
`$shortlink = wpu_get_shortlink("http://www.google.com");`

== Changelog ==

= 0.1.4 =
Add languages file
Show shortlink in admin posts

= 0.1.3 =
Fix bug for get post link

= 0.1.2 =
Fix bug for get post link

= 0.1.1 =
Add new function for custom request
`$shortlink = wpu_get_shortlink("www.google.com");`

= 0.1 =
* Start the project...

== Upgrade Notice ==

= 0.1.1 =
Add new function for custom request
`$shortlink = wpu_get_shortlink("www.google.com");`

= 0.1 =
* Start the project...