# Changelog

#### Upcoming

* [Enhancement] Add Taskrunner Robo to project #28 *2017-09-09*
* [Feature] Save tags from bookmarklet #14
* [Enhancement] New GUI for bookmarklet #21 (Bookmarklet v2)
* [Enhancement] Waiting indicator while saving bookmark in bookmarklet #20
* [Fix] Bookmarklet uses `void` to fix #17 (Bookmarklet v3)
* [Refactor] Split post type, metabox, and taxonomy in their own classes
* [Fix] Update carbon fields and fix bookmarklet field to work with version 2
* [Fix] Composer packages require now php ^7.0 instead of ^7.1

#### v0.1.1 - 2017-06-26

* [Fix] Require `wp-admin/includes/screen.php` for get_current_screen() function otherwise a fatal error occurs. #16

#### v0.1.0 - 2017-06-26

*First basic beta version*

* [Feature] Bookmark Post Type
	* Supports:
		* Title
		* Description
		* Link
		* Private / Public
		* Tags
* [Feature] Bookmarklet
	* Use the bookmarklet to save links on the fly
	* Simply drag'n'drop the bookmarklet to your bookmar bar and click it while you're on a page, you want to save.
	* Build with Vue JS
	* Save the following information:
		* Title (dynamically grabbed from page)
		* URL (dynamically grabbed from page)
		* Description (selected text from page)
		* Private - Is link private?
	* Tags are not supported yet by bookmarklet
