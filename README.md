<h1><p align="center">WordPress Real Media Library Add-On Boilerplate :sparkling_heart:</p></h1>
<p align="center">This WordPress plugin demonstrates how to setup a plugin that uses React and ES6 in a WordPress plugin (Frontend Widget, WordPress backend menu page).</p>

---

[![Github All Releases](https://img.shields.io/github/downloads/matzeeable/wp-real-media-library-add-on/total.svg?colorB=green)](https://github.com/matzeeable/wp-real-media-library-add-on) 
[![GitHub tag](https://img.shields.io/github/tag/matzeeable/wp-real-media-library-add-on.svg?colorB=green)](https://github.com/matzeeable/wp-real-media-library-add-on) 
[![license](https://img.shields.io/github/license/matzeeable/wp-real-media-library-add-on.svg?colorB=green)](https://github.com/matzeeable/wp-real-media-library-add-on/blob/master/LICENSE) 
[![Slack channel](https://img.shields.io/badge/Slack-join-green.svg)](https://matthiasweb.signup.team/)

**Client-side features:** _Familiar React API & patterns (ES6)_
* [**ReactJS**](https://reactjs.org/) v16 with babel `env` preset
* [**webpack**](https://webpack.js.org/) v3 build for assets
* CSS and JS [**Sourcemap**](https://www.html5rocks.com/en/tutorials/developertools/sourcemaps/) generation for debugging purposes
* [**SASS**](http://sass-lang.com/) stylesheets compiler (`.scss` files)
* [**Bourbon**](http://bourbon.io/) mixins for SASS
* [**PostCSS**](http://postcss.org/) for transforming CSS with JavaScript (including autoprefixing)
* Generation of **minified** sources for production (JS, CSS)
* [**Grunt**](https://gruntjs.com/) for automation tasks
* Admin backend components, in this case an own page with a button (`public/src/admin.js`)
* Frontend components, in this case a simple widget (`public/src/widget.js`)

**Server-side features:** _OOP-style for building a high-quality plugin._
* PHP >= **5.3** required: An admin notice is showed when not available
* WordPress >= **4.4** required: An admin notice is showed when not available with a link to the updater
* [**Real Media Library**](https://matthias-web.com/wordpress/real-media-library) >= **3.0** required: An admin notice is showed when not available
* [**Namespace**](http://php.net/manual/en/language.namespaces.rationale.php) support
* [**Autloading**](http://php.net/manual/en/language.oop5.autoload.php) classes in connection with namespaces
* [**WP REST API v2**](http://v2.wp-api.org/) for API programming, no longer use `admin-ajax.php` for your CRUD operations
* [`SCRIPT_DEBUG`](https://codex.wordpress.org/Debugging_in_WordPress#SCRIPT_DEBUG) enables not-minified sources for debug sources (use in connection with `npm run build-dev`)
* [**Cachebuster**](http://www.adopsinsider.com/ad-ops-basics/what-is-a-cache-buster-and-how-does-it-work/) for public resources (`public`)
* Predefined `.po` files for **translating (i18n)** the plugin
* [**ApiGen**](https://github.com/ApiGen/ApiGen) for PHP Documentation
* [**JSDoc**](http://usejsdoc.org/) for JavaScript Documentation
* [**apiDoc**](http://apidocjs.com//) for API Documentation
* [**WP HookDoc**](https://github.com/matzeeable/wp-hookdoc) for Filters & Actions Documentation

## :white_check_mark: Prerequesits
* [**Node.js**](https://nodejs.org/) `npm` command globally available in CLI
* [**Grunt CLI**](https://gruntjs.com/using-the-cli) `grunt` command globally available in CLI
* [**Composer**](https://getcomposer.org/) `composer` command globally available in CLI

## :mountain_bicyclist: Getting Started

Navigate to the plugin directory, install `npm` and `composer` dependencies, and run this installation script:

#### Download boilerplate
```sh
$ cd /path/to/wordpress/wp-content/plugins
$ git clone https://github.com/matzeeable/wp-real-media-library-add-on.git ./your-plugin-name
$ cd your-plugin-name
```

#### Create plugin
```sh
$ npm run create    # Guide through plugin generation
$ npm run dev       # Start webpack in "watch" mode so that the assets are automatically compiled when a file changes
                    # You are now able to activate the plugin in your WordPress backend
```

#### Generate CLI preview (npm run create)
![generate cli](https://image.prntscr.com/image/z61WDD8RQ3GJ3Bp4pZ-ElQ.png)

## :book: Boilerplate Documentation
This boilerplate is a fork of [matzeeable/wp-reactjs-starter](https://github.com/matzeeable/wp-reactjs-starter). You can find the whole documentation [here](https://github.com/matzeeable/wp-reactjs-starter/blob/master/README.md).

#### Addition
In every `base\Base` child class implementation you can use the method `rmlVersionReached()` to check if Real Media Library is available and the minimum version is reached.

## :electric_plug: Extend Real Media Library.
Just check out the API Documentation of Real Media Library [here](https://matthias-web.com/wordpress/real-media-library/documentation/).

## Licensing / Credits
This boilerplate is MIT licensed. Originally this boilerplate is a fork of [matzeeable/wp-reactjs-starter](https://github.com/matzeeable/wp-reactjs-starter).
