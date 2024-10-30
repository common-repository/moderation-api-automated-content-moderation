=== Moderation API: Automated Content Moderation ===
Contributors: moderationapi
Donate link: https://moderationapi.com/
Tags: ai moderation, toxic, nsfw, profanity, spam
Requires at least: 5.8
Tested up to: 6.6.2
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use Moderation API to automatically moderate comments on your WordPress site.

== Description ==

Use Moderation API to automatically moderate comments on your WordPress site. Detects a large range of content such as bullying, discrimination, sentiment, NSFW, PII, and much more.

* Automatically remove unwanted comments
* Automatically approve safe comments
* Combine with human reviews
* Works in 200+ languages

Terms
* [Terms of Service](https://moderationapi.com/terms-of-service)
* [Privacy Policy](https://moderationapi.com/privacy-policy)
* [DPA](https://moderationapi.com/data-processing-agreement)

== Installation ==

1. Install from the WordPress plugin directory or upload the `moderation-api` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Login or create an account on [moderationapi.com](https://moderationapi.com/)
4. Press the "Connect" button on the plugin settings page

== Frequently Asked Questions ==

= Is Moderation API free? =

Moderation API is a paid service. You can use the free tier to get started and upgrade to a paid plan to get more powerful features.

= Where can I see if Moderation API is working? =

We recommend to use the review queue to see if Moderation API is working. You might want to update the queue settings to show all comments instead of just flagged ones. You can also see the API response in the WordPress admin under "Comments".

= Why do I need to connect my WordPress site to Moderation API? =

You need to connect your WordPress site to Moderation API to use the service. This is so we can moderate content for you.

= How do I select what I want to flag? =

You can select various types of content to flag such as bullying, discrimination, sentiment, NSFW, PII, and much more in the Moderation API dashboard. We have a comprehensive list of categories, attributes, and taxonomies you can select.

You can also create your own custom rules and guidelines using AI agents.

= Can I moderate other types of content besides comments? =

The free plugin can only moderate comments. If you want to moderate other types of content such as posts, pages, or custom post types, please get in touch with us.

= Which languages does Moderation API support? =

Moderation API supports 200+ languages. Some models work better for certain languages. See the language support when you add a model to your project.

= What happens to my flagged content? =

You can choose what to do with your flagged content. You can automatically approve it, send it to the review queue, or delete it.

= Do I have to use the review queue? =

No. You can automatically approve or delete content that is flagged by the AI. This way the plugin is a set and forget solution, and you can get deeper insights into flagged content by using the review queue.

= Can I use Moderation API with other plugins? =

Yes, the plugin plays nicely with other plugins. If other plugins are also moderating comments, these will most likely run before Moderation API, and the value from the other plugins will be used if Moderation API does not flag the content.

== Screenshots ==

1. Select how you want to handle content flagged by the AI. 
2. Optionally use the review queue to review content. You can configure the review queue to automatically approve content that is not flagged, or simply use it to get insights into flagged content.
3. Take actions on flagged content such as approve, block, or delete.
4. Charts lets you understand the impact of the Moderation API.
5. Focus your moderation efforts on specific time periods, languages, or content types.

== Changelog ==

= 1.0.0 =
* Initial release

= 1.0.1 =
* Add error column to comments table
* Fix issue where comments were not being added to the review queue
* Add flagged labels to comments table

= 1.0.2 =
* Fixed order of operations for comment moderation. Comments are now moderated before WordPress adds them to the database.
* Add admin notice to activate Moderation API account.

== Upgrade Notice ==

= 1.0.1 =
* Important: Fixes critical issue with the review queue. Please update to ensure comments are processed correctly.

= 1.0.2 =
* If other plugins are also moderating comments, these will most likely run before Moderation API. If Moderation API does not flag a comment the value from the other plugins will be used.