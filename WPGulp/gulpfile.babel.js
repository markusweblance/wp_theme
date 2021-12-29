/**
 * Gulpfile.
 *
 * Gulp with WordPress.
 *
 * Implements:
 *      1. Live reloads browser with BrowserSync.
 *      2. CSS: Sass to CSS conversion, error catching, Autoprefixing, Sourcemaps,
 *         CSS minification, and Merge Media Queries.
 *      3. JS: Concatenates & uglifies Vendor and Custom JS files.
 *      4. Images: Minifies PNG, JPEG, GIF and SVG images.
 *      5. Watches files for changes in CSS or JS.
 *      6. Watches files for changes in PHP.
 *      7. Corrects the line endings.
 *      8. InjectCSS instead of browser page reload.
 *      9. Generates .pot file for i18n and l10n.
 *
 * @tutorial https://github.com/ahmadawais/WPGulp
 * @author Ahmad Awais <https://twitter.com/MrAhmadAwais/>
 */

/**
 * Load WPGulp Configuration.
 *
 * TODO: Customize your project in the wpgulp.js file.
 */
const config = require('./wpgulp.config.js');

/**
 * Load Plugins.
 *
 * Load gulp plugins and passing them semantic names.
 */
const gulp = require('gulp'); // Gulp of-course.



// CSS related plugins.
const sass = require('gulp-sass'); // Gulp plugin for Sass compilation.
const minifycss = require('gulp-uglifycss'); // Minifies CSS files.
const autoprefixer = require('gulp-autoprefixer'); // Autoprefixing magic.
const group_media = require('gulp-group-css-media-queries'); // Combine matching media queries into one.

// JS related plugins.
const concat = require('gulp-concat'); // Concatenates JS files.
const uglify = require('gulp-uglify'); // Minifies JS files.
const babel = require('gulp-babel'); // Compiles ESNext to browser compatible JS.

// Image related plugins.
const imagemin = require('gulp-imagemin'); // Minify PNG, JPEG, GIF and SVG images with imagemin.
const webp = require('gulp-webp');
// Utility related plugins.
const rename = require('gulp-rename'); // Renames files E.g. style.css -> style.min.css.
const lineec = require('gulp-line-ending-corrector'); // Consistent Line Endings for non UNIX systems. Gulp Plugin for Line Ending Corrector (A utility that makes sure your files have consistent line endings).
const filter = require('gulp-filter'); // Enables you to work on a subset of the original files by filtering them using a glob.file (E.g. structure.scss, which was later combined with other css files to generate style.css).
const notify = require('gulp-notify'); // Sends message notification to you.
const browserSync = require('browser-sync').create(); // Reloads browser and injects CSS. Time-saving synchronized browser testing.
const wpPot = require('gulp-wp-pot'); // For generating the .pot file.
const sort = require('gulp-sort'); // Recommended to prevent unnecessary changes in pot-file.
const cache = require('gulp-cache'); // Cache files in stream for later use.
const remember = require('gulp-remember'); //  Adds all the files it has ever seen back into the stream.
const plumber = require('gulp-plumber'); // Prevent pipe breaking caused by errors from gulp plugins.
const beep = require('beepbeep');
const newer = require('gulp-newer');
const gutil = require( 'gulp-util' );
const ftp = require( 'vinyl-ftp' );

//Fonts
const ttf2woff = require('gulp-ttf2woff');
const ttf2woff2 = require('gulp-ttf2woff2');

/**
 * Custom Error Handler.
 *
 * @param Mixed err
 */
const errorHandler = r => {
	notify.onError('\n\n❌  ===> ERROR: <%= error.message %>\n')(r);
	beep();

	// this.emit('end');
};

/**
 * Task: `browser-sync`.
 *
 * Live Reloads, CSS injections, Localhost tunneling.
 * @link http://www.browsersync.io/docs/options/
 *
 * @param {Mixed} done Done.
 */
const browsersync = done => {
	browserSync.init({
		proxy: config.projectURL,
		reloadDelay: 1000,
		open: config.browserAutoOpen,
		injectChanges: config.injectChanges,
		watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir']
	});
	done();
};

// Helper function to allow browser reload with Gulp 4.
const reload = done => {
	browserSync.reload();
	done();
};

/**
 * Task: `styles`.
 *
 * Compiles Sass, Autoprefixes it and Minifies CSS.
 *
 * This task does the following:
 *    1. Gets the source scss file
 *    2. Compiles Sass to CSS
 *    3. Writes Sourcemaps for it
 *    4. Autoprefixes it and generates style.css
 *    5. Renames the CSS file with suffix .min.css
 *    6. Minifies the CSS file and generates style.min.css
 *    7. Injects CSS or reloads the browser via browserSync
 */
gulp.task('styles', () => {
	return gulp
		.src(config.styleSRC, {allowEmpty: true})
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: config.outputStyle,
				precision: config.precision
			})
		)
		.pipe(group_media()) // Merge Media Queries
		.on('error', sass.logError)
		.pipe(autoprefixer({
			overrideBrowserslist: ['last 5 versions'],
			cascade: true
		}))
		.pipe(lineec()) // Consistent Line Endings for non UNIX systems.
		.pipe(gulp.dest(config.styleDestination))
		.pipe(filter('**/*.css')) // Filtering stream to only css files.
		.pipe(browserSync.stream());
});


/**
 * Task: `customJS`.
 *
 * Concatenate and uglify custom JS scripts.
 *
 * This task does the following:
 *     1. Gets the source folder for JS custom files
 *     2. Concatenates all the files and generates custom.js
 *     3. Renames the JS file with suffix .min.js
 *     4. Uglifes/Minifies the JS file and generates custom.min.js
 */
gulp.task('customJS', () => {
	return gulp
		.src(config.jsCustomSRC, {since: gulp.lastRun('customJS')}) // Only run on changed files.
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env', // Preset to compile your modern JS to ES5.
						{
							targets: {browsers: config.BROWSERS_LIST} // Target browser list to support.
						}
					]
				]
			})
		)
		.pipe(remember(config.jsCustomSRC)) // Bring all files back to stream.
		.pipe(concat(config.jsCustomFile + '.js'))
		.pipe(lineec()) // Consistent Line Endings for non UNIX systems.
		.pipe(gulp.dest(config.jsCustomDestination));
});

/**
 * Task: `images`.
 *
 * Minifies PNG, JPEG, GIF and SVG images.
 *
 * This task does the following:
 *     1. Gets the source of images raw folder
 *     2. Minifies PNG, JPEG, GIF and SVG images
 *     3. Generates and saves the optimized images
 *
 * This task will run only once, if you want to run it
 * again, do it with the command `gulp images`.
 *
 * Read the following to change these options.
 * @link https://github.com/sindresorhus/gulp-imagemin
 */
gulp.task('images', () => {
	return gulp
		.src(config.imgSRC)
		.pipe(newer(config.imgDST))
		.pipe(
			webp({
				quality: 90
			})
		)
		.pipe(gulp.dest(config.imgDST))
		.pipe(gulp.src(config.imgSRC))
		.pipe(newer(config.imgDST))
		.pipe(
			cache(
				imagemin({
					progressive: true,
					svgoPlugins: [{removeViewBox: false}],
					interlaced: true,
					optimizationLevel: 3 // 0 to 7
				})
			)
		)
		.pipe(gulp.dest(config.imgDST));
});

/**
 * Task: `clear-images-cache`.
 *
 * Deletes the images cache. By running the next "images" task,
 * each image will be regenerated.
 */
gulp.task('clearCache', function (done) {
	return cache.clearAll(done);
});

/**
 * WP POT Translation File Generator.
 *
 * This task does the following:
 * 1. Gets the source of all the PHP files
 * 2. Sort files in stream by path or any custom sort comparator
 * 3. Applies wpPot with the variable set at the top of this file
 * 4. Generate a .pot file of i18n that can be used for l10n to build .mo file
 */
gulp.task('translate', () => {
	return gulp
		.src(config.watchPhp)
		.pipe(sort())
		.pipe(
			wpPot({
				domain: config.textDomain,
				package: config.packageName,
				bugReport: config.bugReport,
				lastTranslator: config.lastTranslator,
				team: config.team
			})
		)
		.pipe(gulp.dest(config.translationDestination + '/' + config.translationFile));
});

/**
 * Fonts
 */
gulp.task('fonts', () => {
	return gulp
		.src(config.srcFonts)
		.pipe(ttf2woff())
		.pipe(gulp.dest(config.destFonts))
		.pipe(gulp.src(config.srcFonts))
		.pipe(ttf2woff2())
		.pipe(gulp.dest(config.destFonts));
})


gulp.task( 'deploy', function () {

	const conn = ftp.create( {
		host:     'aipin.ru',
		user:     'aipin_dev',
		password: '1F4g2R8d',
		parallel: 5,
		log:      gutil.log
	} );

	const globs = [
		'../assets/css/style.css'
	];

	// using base = '.' will transfer everything to /public_html correctly
	// turn off buffering in gulp.src for best performance

	return gulp.src( globs, { base: '.', buffer: true } )
		.pipe( conn.dest( '/www/aipin.ru/wp-content/themes/aipin_theme/assets' ) );

} );


/**
 * Watch Tasks.
 *
 * Watches for file changes and runs specific tasks.
 */
gulp.task(
	'default',
	gulp.parallel('styles', 'customJS', 'images', browsersync, () => {
		gulp.watch(config.watchPhp, reload); // Reload on PHP file changes.
		gulp.watch(config.watchStyles, gulp.series('styles', 'deploy', reload)); // Reload on SCSS file changes.
		gulp.watch(config.watchJsCustom, gulp.series('customJS', reload)); // Reload on customJS file changes.
		gulp.watch(config.imgSRC, gulp.series('images', reload));
	})
);
