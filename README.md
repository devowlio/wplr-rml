=== WP/LR Sync Folders with Real Media Library ===
Contributors: devowl, mguenter, TigrouMeow
Tags: wp/lr sync, lightroom sync, real media library, lightroom, synchronization, sync, export, image, gallery, photo, management, wplr-extension, micro add-on
Requires at least: 4.4
Requires PHP: 5.4.0
Tested up to: 5.4
Stable tag: trunk

Synchronize your folders and collections in Real Media Library (Media Library Folders for WordPress) with Lightroom (with the help of WP/LR Sync).

== Description ==
Synchronize your photos, collections, keywords, and metadata between Lightroom and [WordPress Real Media Library](https://devowl.io/wordpress-real-media-library/). All changes in your Lightroom are replicated in the folder structure of your WordPress Media Library.

== Requirements =

This plugin is an add-on that allows you to use the following plugins together, which must be installed:

- [WP/LR Sync](https://meowapps.com/wplr-sync)
- [Real Media Library](https://devowl.io/go/codecanyon/real-media-library?source=wp-lr-sync-folders-wordpress-org)

=== Features ===

- Add folders/collections automatically
- Remove and move folders/collections automatically
- Inserting an Image into multiple collections
- Respect the Lightroom image order
- Custom folder icons in your folder structure
- Galleries based on your Lightroom collections: When the Lightroom hierarchy and attachments are synchronized with your WordPress installation, you can generate dynamic galleries on your posts and pages

== Further links ==

If you want to learn more, have a problem or if you are a developer, the following links are for you:

- [WP/LR FAQ](https://meowapps.com/wplr-sync/faq)
- [Real Media Library Knowledge Base](https://help.devowl.io/kb/real-media-library)
- [devowl.io support (for Real Media Library and this plugin)](https://devowl.io/support/)
- [Git repository of WP/LR Sync Folders at GitHub](https://github.com/matzeeable/wplr-rml)

== Installation ==

1. Go to your WordPress backend
2. Navigate to "Plugins" > "Add New"
3. Search for "WP/LR Sync Folders"
4. Install and activate the plugin
5. Install [WP/LR Sync from Meow Apps](https://meowapps.com/wplr-sync)
6. Install [Real Media Library from devowl.io](https://devowl.io/go/codecanyon/real-media-library?source=wp-lr-sync-folders-wordpress-org)
7. Synchronize everything :)

== Frequently Asked Questions ==

= Total Sync shows shortcut as not linked =
When you use Total Sync, it shows shortcuts as unlinked. Don't worry about this - a Lightroom ID can only be linked to one attachment in WordPress.

= I see a lot of shortcuts, how can I delete them? =
The extension only creates links when necessary. This means that a shortcut is created when an image is added to multiple collections. However, if you want to delete it, make sure that the Real Media Library and this extension are active: Navigate to Settings > Media "Reset" tab and click "Delete WP/LR Shortcuts".

== Changelog ==

= 1.1.2 =

- Prepare for Real Media Library v4.6
- Updated plugin description

= 1.1.1 =

- Show migration message only when previously used an older version of the plugin

= 1.1 =

- Real Media Library v4.0.10 is now required as minimum version
- Fixed the synchronization mechanism and create only shortcuts when needed (see https://github.com/matzeeable/wplr-rml/issues/2)

= 1.0.2 =

- PHP 5.4 is now the minimum required PHP version
- Prepared compatibility with Real Physical Media

= 1.0.1 =

- Fixed bug with custom sorting issues

= 1.0 =

- First release.

== Screenshots ==

1. The Lightroom publish services...
2. ...is automatically synchronized with your media library
