// Имя папки проекта
import * as nodePath from 'path'
const rootFolder = nodePath.basename(nodePath.resolve())

const buildFolder = `..`
const srcFolder = `./src`
const projecturl = `http://wordpress.loc/`

export const path = {
	build: {
		js: `${buildFolder}/assets/js/`,
		img: `${buildFolder}/assets/img/`,
		css: `${buildFolder}/assets/css/`,
		fonts: `${buildFolder}/assets/fonts/`,
	},
	src: {
		js: `${srcFolder}/js/app.js`,
		img: `${srcFolder}/img/**/*.{jpg,jpeg,png,gif,webp}`,
		scss: `${srcFolder}/scss/style.scss`,
	},
	watch: {
		js: `${srcFolder}/js/**/*.js`,
		scss: `${srcFolder}/scss/**/*.scss`,
		php: `${buildFolder}/**/*.php`,
		img: `${srcFolder}/img/**/*.{jpg,jpeg,png,gif,webp}`,
	},
	projectURL: projecturl,
	srcfolder: srcFolder,
	rootfolder: rootFolder,	
}