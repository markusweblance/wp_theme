import dartSass from 'sass'
import gulpSass from 'gulp-sass'
import rename from 'gulp-rename'
import autoprefixer from 'gulp-autoprefixer'
import groupCssMediaQueries from 'gulp-group-css-media-queries'

const sass = gulpSass(dartSass)

export const scss = () => {
	return app.gulp.src(app.path.src.scss, { sourcemaps: true })
		.pipe(sass({
			outputStyle: 'expanded'
		}))
		.pipe(groupCssMediaQueries())
		.pipe(autoprefixer({
			grid: true,
			overrideBrowserslist: ['Last 3 versions'],
			cascade: true
		}))
		.pipe(rename({
			extname: '.css'
		}))
		.pipe(app.gulp.dest(app.path.build.css))
		.pipe(app.plugins.browsersync.stream())
}