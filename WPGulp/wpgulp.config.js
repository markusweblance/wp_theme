/**
 * WPGulp Configuration File
 *
 * 1. Edit the variables as per your project requirements.
 * 2. In paths you can add <<glob or array of globs>>.
 *
 * @package WPGulp
 */

// Project options.

// Local project URL of your already running WordPress site.
// > Could be something like "wpgulp.local" or "localhost"
// > depending upon your local WordPress setup.
const projectURL = 'https://aipin.ru/';

// Theme/Plugin URL. Leave it like it is; since our gulpfile.js lives in the root folder.
const productURL = './';
const browserAutoOpen = true;
const injectChanges = true;

// >>>>> Style options.
// Path to main .scss file.
const styleSRC = './src/scss/style.scss';

// Path to place the compiled CSS file. Default set to root folder.
const styleDestination = '../assets/css/';

// Available options → 'compact' or 'compressed' or 'nested' or 'expanded'
const outputStyle = 'expanded';
const errLogToConsole = true;
const precision = 10;


// Fonts
const srcFonts = './src/fonts/*.ttf'
const destFonts = '../assets/fonts/'

// Path to JS custom scripts folder.
const jsCustomSRC = './src/js/*.js';

// Path to place the compiled JS custom scripts file.
const jsCustomDestination = '../assets/js/';

// Compiled JS custom file name. Default set to custom i.e. custom.js.
const jsCustomFile = 'scripts';

// Images options.

// Source folder of images which should be optimized and watched.
// > You can also specify types e.g. raw/**.{png,jpg,gif} in the glob.
const imgSRC = './src/img/**/*';

// Destination folder of optimized images.
// > Must be different from the imagesSRC folder.
const imgDST = '../assets/img/';

// >>>>> Watch files paths.
// Path to all *.scss files inside css folder and inside them.
const watchStyles = './src/scss/**/*.scss';

// Path to all custom JS files.
const watchJsCustom = './src/js/*.js';

// Path to all PHP files.
const watchPhp = '../**/*.php';

// >>>>> Translation options.
// Your text domain here.
const textDomain = 'WPGULP';

// Name of the translation file.
const translationFile = 'WPGULP.pot';

// Where to save the translation files.
const translationDestination = '../languages';

// Package name.
const packageName = 'WPGULP';

// Where can users report bugs.
const bugReport = 'https://AhmadAwais.com/contact/';

// Last translator Email ID.
const lastTranslator = 'Ahmad Awais <your_email@email.com>';

// Team's Email ID.
const team = 'AhmadAwais <your_email@email.com>';


// Export.
module.exports = {
	projectURL,
	productURL,
	browserAutoOpen,
	injectChanges,
	styleSRC,
	styleDestination,
	outputStyle,
	errLogToConsole,
	precision,
	jsCustomSRC,
	jsCustomDestination,
	jsCustomFile,
	imgSRC,
	imgDST,
	watchStyles,
	watchJsCustom,
	watchPhp,
	textDomain,
	translationFile,
	translationDestination,
	packageName,
	bugReport,
	lastTranslator,
	team,
	srcFonts,
	destFonts
};
