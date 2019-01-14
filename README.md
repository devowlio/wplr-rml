=== WP/LR Sync Folders ===
Contributors: mguenter, TigrouMeow
Tags: lightroom, image, gallery, media, photo, export, management, admin, sync, synchronization, real media library, add-on
Requires at least: 4.4
Requires PHP: 5.4.0
Tested up to: 5.0.1
Stable tag: trunk

Displays the hierarchy of folders and collections nicely on the left side of your Media Library by syncing with the Real Media Library plugin.

== Description ==
Synchronize your photos, collections, keywords and metadata between Lightroom and WordPress Real Media Library. Any changes in your Lightroom will be replicated in your WordPress media library folder structure.

**INSTALLATION**. This plugin requires the WP/LR Sync plugin for Lightroom and WordPress Real Media Library. They are available here: [WP/LR Sync at Meow Apps](https://meowapps.com/wplr-sync) and [WP Real Media Library](https://codecanyon.net/item/wordpress-real-media-library-media-categories-folders/13155134).

**GALLERIES BASED ON YOUR LIGHTROOM COLLECTIONS**. When the Lightroom hierarchy and attachments are synced with your WordPress installation you are able to generate dynamic galleries on your posts and pages.

**Main features:**
- Add folders / collections automatically
- Remove and move folders / collections automatically
- Put an image in multiple collections
- Respect LR image order
- Own folder Icons in your folder structure

Official WP/LR FAQ: [here](https://meowapps.com/wplr-sync/faq). If you are a developer you can have a look at the GitHub repository for this Add-On [here](https://github.com/matzeeable/wplr-rml).

== Installation ==

1. Upload `wplr-rml` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Install the Lightroom plugin from here: https://meowapps.com/wplr-sync
4. Install the WP Real Media Library plugin from: [WP Real Media Library](https://codecanyon.net/item/wordpress-real-media-library-media-categories-folders/13155134)
5. Synchronize everything :)

== Frequently Asked Questions ==
= I used the previous Real Media Library extension =
If you have used already the built-in WP Real Media Library extension you navigate to `WP/LR Sync` > `Extensions`. Make sure you have still ticked "Real Media Library" in extensions and click the button `Reset with Extensions`. This does remove all the RML folders which where already synced with your media library (it does not delete attachments from WP itself). Afterwards untick the RML extension, save, activate the `WP/LR Sync Folders` plugin and do `Resync with Extensions`.

= Total Sync shows shortcut as not linked =
If you use Total Sync it shows shortcuts as unlinked. Do not worry about this - a LR id may only be associated with one attachment within WordPress.

= I see a lot of shortcuts, how can I delete them? =
The extension creates shortcuts only when necessary. That means a shortcut is created when an image is put into multiple collections. But if you want to delete them make sure you have Real Media Library and this extension active: Navigate to Settings > Media Tab "Reset" and click "Delete WP/LR shortcuts".

== Changelog ==

= 1.1.1 =
* Show migration message only when previously used an older version of the plugin

= 1.1 =
* Real Media Library v4.0.10 is now required as minimum version
* Fixed the synchronization mechanism and create only shortcuts when needed (see https://github.com/matzeeable/wplr-rml/issues/2)

= 1.0.2 =
* PHP 5.4 is now the minimum required PHP version
* Prepared compatibility with Real Physical Media

= 1.0.1 =
* Fixed bug with custom sorting issues

= 1.0 =
* First release.

== Screenshots ==
1. The Lightroom publish services...
2. ... is automatically synchronized with your Media Library