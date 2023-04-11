# UND Generationentandem Wordpress Theme

The WordPress theme from UND Generationentandem based on Zurb's [Foundation for Sites 6](https://foundation.zurb.com/sites.html) and Ole Fredrik's [FoundationPress](https://github.com/olefredrik/FoundationPress). Both projects are no longer maintained. As Zurb Foundation is no longer in active development.

The theme needs to be refactored on another framework, which is still being maintained.
All contributions are welcome!

## Requirements

Make sure all dependencies have been installed before moving on:

- [WordPress](https://wordpress.org/) >= 5.9
- [PHP](https://secure.php.net/manual/en/install.php) >= 7.4.0
- [Composer](https://getcomposer.org/download/)
- [Node.js](http://nodejs.org/) >= 18.14.1
- [NPM](https://docs.npmjs.com/) >= 9.5.0

If the PHP intl extension is missing:

- Search for the php config file (php.ini, usually in the same folder as the php executable) and open it
- Make sure the line "extension=intl" is existing and not commented
- Restart the web server
- Check if the extension is enabled using phpinfo() or php -i

For more information check: [Where is the intl PHP extension?](https://www.dotkernel.com/php-troubleshooting/where-is-the-intl-php-extension-problem-solved/).

## Quickstart

Clone or download this repository. Then install the necessary Node.js and Composer dependencies:

```bash
$ composer install
$ npm install
```

### Compile assets for production

When building for production, the CSS and JS will be minified. To minify the assets run:

```bash
$ npm run build
```

### Available CLI commands

- `composer phpcs` : checks all PHP files against [PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/).
- `composer phpcs:fix` : checks all PHP files against phpcs and fixes issues automatically.
- `composer lint` : checks all PHP files for syntax errors.
- `npm run build` : compiles and minifies SASS/JS files to css/js for production.
- `npm run dev` : generates all SASS/JS files with source maps.
- `npm run dev:watch` : watches all SASS/JS files and recompiles them to css/js with soure maps when they change.
- `npm run standard` : checks all JavaScript files against [JavaScript Standard Style](https://standardjs.com/).
- `npm run standard:fix` : checks all JavaScript files against standard and fixes issues automatically.
- `npm run stylelint` : checks all SASS files against [Stylelint is a mighty, modern CSS linter](https://stylelint.io/).
- `npm run stylelint:fix` : checks all SASS files against stylelint and fixes issues automatically.
- `npm run bundle` : generates a .zip archive for distribution, excluding development and system files.

Running the bundle command will place a .zip archive of the theme in the main directory. This excludes the developer files/directories like `/node_modules`, `/src`, etc. to keep the theme lightweight for transferring the theme to a staging or production server.

### Project structure

In the `/src` folder you will find the working files for all assets. Every build the output will be saved to the `theme/public` folder. The contents of this folder is the compiled SASS/JS code.

The `/page-templates` folder contains templates that can be selected in the Pages section of the WordPress admin panel. To create a new page-template, simply create a new file in this folder and make sure to give it a template name.

### Styles and Sass Compilation

 * `style.css`: Do not worry about this file. (For some reason) it's required by WordPress. All styling are handled in the Sass files described below

 * `src/assets/scss/app.scss`: Imports all styles
 * `src/assets/scss/global/*.scss`: Global settings
 * `src/assets/scss/components/*.scss`: Buttons etc.
 * `src/assets/scss/modules/*.scss`: Topbar, footer etc.
 * `src/assets/scss/templates/*.scss`: Page template styling
 * `theme/public/css/app.css`: This file is loaded in the `<head>` section of the theme, and contains the compiled styles.


### JavaScript Compilation

All JavaScript files, including Foundation's modules, are imported through the `src/assets/js/app.js` file. The files are imported using module dependency with [webpack](https://webpack.js.org/) as the module bundler.

Foundation modules are loaded in the `src/assets/js/app.js` file. By default all components are loaded.

 * `theme/public/js/app.js`: This file is loaded before the `</body>` section of the theme, and contains the compiled javascript.
