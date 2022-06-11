export const server = () => {
	app.plugins.browsersync.init({
		proxy: app.path.projectURL,
		reloadDelay: 1000,
		open: true,
		injectChanges: true,
		watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir']
	})
}