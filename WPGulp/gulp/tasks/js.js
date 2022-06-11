import webpack from "webpack-stream"

export const js = () => {
	return app.gulp.src(app.path.src.js, { sourcemaps: true })
		.pipe(webpack({
			mode: 'production',
			output: {
				filename: 'app.js'
			},
			optimization: {
				minimize: false
			},
		}))
		.pipe(app.gulp.dest(app.path.build.js))
		.pipe(app.plugins.browsersync.stream())
}