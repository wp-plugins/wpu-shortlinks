=== WPU ShortLinks ===
Contributors: parselearn
Donate link: http://wpu.ir/
Tags: ShortLink , shorter link , link , wpu , url shortener , yourls , custom url , short link, short url, shorturl ,  url generator, url , uri shortner , social url , linker , Twitter, Facebook, Google+, Social Sharing, social share, TF Social Share, twitter button, twitter facebook share, twitter share, bookmark, bookmarking, bookmarks,button,facebook share, google, google +1, google plus, google plus one, Like, plus 1, plus one, Share, share button, share buttons, share links, share this, Shareaholic, sharedaddy, sharethis, sharing, shortcode, sociable, social, social bookmarking, social bookmarks, social share, social sharing, tweet, tweet button, twitter button, twitter share, widget, wpmu
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows automatic url shortening of post links using wpu.ir Services using the API recently provided by WP-Parsi.

== Description ==

Allows automatic url shortening of post links using [WPU.IR](http://wpu.ir/ "WP-Parsi ShortLinks System") Services using the API recently provided by WP-Parsi.

* Generate ShortLinks from Post Content URLs
* Generate ShortLinks with Bulk Action
* Request ShortLink With Admin Bar Shortcut
* Adds very simple social sharing buttons for Twitter, Facebook and Google+ to the end of your posts , based by [Social Sharing by Danny](https://wordpress.org/plugins/dvk-social-sharing/)
* [Firefox Addon](https://addons.mozilla.org/en-US/firefox/addon/wpu-shortlinks/ "A simple URL Shortener Addon based on wpu.ir website.")

== Installation ==

1. Upload `wpu-ShortLinks` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==
= Shortcode (use in post editor): =
`[wpu]Post ShortLink[/wpu]`
Specific Post
`[wpu id=74]Wordpress for life :)[/wpu]`

= Function: =
`wpu_ShortLink(post_id,display)`

Use in post loop:
`<a href="<?php wpu_ShortLink() ?>">ShortLink</a>`

Specific Post:
`<a href="<?php wpu_ShortLink(74) ?>">ShortLink</a>`

Request custom URI:
`$ShortLink = wpu_get_ShortLink("http://www.google.com");`

Social Sharing
`
$args = array(
    'element' => 'div',
    'social_options' => 'twitter, facebook, googleplus',
    'twitter_username' => 'yourTwitterUsername',
    'before_text' => 'Share this post:',
    'twitter_text' => 'Twitter',
    'facebook_text' => 'Facebook',
    'googleplus_text' => 'Google+'
);
wpu_social_sharing($echo=true,$args)`

== Screenshots ==

1. Settings
2. Post ShortLink of Publish Widget
3. Generate ShortLinks with Bulk Action
4. Request ShortLink With Admin Bar Menu
5. Show ShortLink & Social Sharing Buttons of Post

== Changelog ==

= 1.1 =
Fixed Bug Request ShortLink With Admin Bar Menu
Fixed Bug ShortCode Generator Post Editor Button
Two Method Show Automatic ShortLink of Post
Adds Social Sharing Buttons

= 1.0 =
Generate ShortLinks from Post Content URLs
Generate ShortLinks with Bulk Action
Request ShortLink With Admin Bar Menu

= 0.3 =
validate url fixed

= 0.2 =
Webservice bug fixed

= 0.1.4 =
Add languages file
Show ShortLink in admin posts

= 0.1.3 =
Fix bug for get post link

= 0.1.2 =
Fix bug for get post link

= 0.1.1 =
Add new function for custom request
`$ShortLink = wpu_get_ShortLink("www.google.com");`

= 0.1 =
* Start the project...

== Upgrade Notice ==

= 0.1.1 =
Add new function for custom request
`$ShortLink = wpu_get_ShortLink("www.google.com");`

= 0.1 =
* Start the project...