# Changelog

### v0.1.1 - 2017-06-26

* [Fix] Require `wp-admin/includes/screen.php` for get_current_screen() function otherwise a fatal error occurs. #16

### v0.1.0 - 2017-06-26

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
