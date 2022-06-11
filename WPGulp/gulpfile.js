import gulp from "gulp"
import { path } from './gulp/config/path.js'
import { plugins } from './gulp/config/plugins.js'

global.app = {
	path: path,
	gulp: gulp,
	plugins: plugins
}


import { php } from './gulp/tasks/php.js'
import { server } from './gulp/tasks/server.js'
import { scss } from './gulp/tasks/scss.js'
import { js } from './gulp/tasks/js.js'
import { img } from './gulp/tasks/img.js'
import { otfToTtf, ttfToWoff, fontsStyle } from './gulp/tasks/fonts.js'

function watcher() {
	gulp.watch(path.watch.php, php)
	gulp.watch(path.watch.scss, scss)
	gulp.watch(path.watch.js, js)
	gulp.watch(path.watch.img, img)
}

const fonts = gulp.series(otfToTtf, ttfToWoff, fontsStyle)

const mainTasks = gulp.series(gulp.parallel(scss, js, img))

const dev = gulp.series(mainTasks, gulp.parallel(watcher, server))

gulp.task('default', dev)
gulp.task('fonts', fonts)