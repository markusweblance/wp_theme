import webp from 'gulp-webp'
//import imagemin from 'gulp-imagemin'

export const img = () => {
	return app.gulp.src(app.path.src.img)
		.pipe(app.plugins.newer(app.path.build.img))
		.pipe(webp())
		.pipe(app.gulp.dest(app.path.build.img))
		.pipe(app.gulp.src(app.path.src.img))
		.pipe(app.gulp.dest(app.path.build.img))
		.pipe(app.plugins.browsersync.stream())
}