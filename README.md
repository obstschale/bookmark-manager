# Bookmark Manager

[ ![Codeship Status for obstschale/bookmark-manager](https://app.codeship.com/projects/4a227700-3c83-0135-baef-327939c5f2be/status?branch=master)](https://app.codeship.com/projects/228889)

	WordPress Plugin


Bookmark Manager is a plugin for WordPress. Use your WordPress site as bookmark manager to save and manage web links. A bookmarklet helps you to save links while you browse the web.

## Installation

There are two ways to install the plugin:

1. Download the latest version as ZIP from [bookmark-manager/releases](https://github.com/obstschale/bookmark-manager/releases) (Recommended)
2. Clone repository and install dependencies (for developers)

### Installation for Developers

```sh
# cd into wp-content/plugins
$ git clone git@github.com:obstschale/bookmark-manager.git
$ cd bookmark-manager

# Install dependencies
$ composer install
$ yarn # Alternatively, npm install

# Build assets
$ yarn run production
```

#### Pitfall - Bookmarklet

When developing locally you likely want to use `yarn run dev`. This will watch all assests and rebuild them if the change. However, this will not minify the files!

If you need to work on the bookmarklet itself ([bookmark-this.js](resources/js/bookmarklet/bookmark-this.js)) **you will need a minified version** otherwise browsers won't accept it. So keep that in mind!


## GNU General Public License v2.0

This plugin is licensed under GNU General Public License v2.0. For more information read the [LICENSE](LICENSE) document.

