<?php


namespace TSTheme;


class TSThemeFunctions {

	public static array $disable_template = [];

	public static function start() {
		add_action( 'admin_init', [ static::Class, 'disable_content_editor' ] );
		add_filter('wp_generate_attachment_metadata', [ static::Class, 'gt_webp_generation' ]);
	}


	public static function disable_content_editor() {

		if ( isset( $_GET['post'] ) ) {
			$post_ID = $_GET['post'];
		} else if ( isset( $_POST['post_ID'] ) ) {
			$post_ID = $_POST['post_ID'];
		}

		if ( ! isset( $post_ID ) || empty( $post_ID ) ) {
			return;
		}

		foreach ( self::$disable_template as $item ) {
			$page_template = get_post_meta( $post_ID, '_wp_page_template', true );
			if ( $page_template == $item['template'] ) {
				remove_post_type_support( $item['type'], 'editor' );
			}
		}
	}

	/**
	 * Генерирует webp копии изображений сразу после загрузки изображения в медиабиблиотеку
	 *
	 * - новые файлы сохраняет с именем name.ext.webp, например, thumb.jpg.webp
	 */
	public static function gt_webp_generation($metadata) {
		$uploads = wp_upload_dir(); // получает папку для загрузки медиафайлов

		$file = $uploads['basedir'] . '/' . $metadata['file']; // получает исходный файл
		$ext = wp_check_filetype($file); // получает расширение файла

		if ( $ext['type'] == 'image/jpeg' ) { // в зависимости от расширения обрабатаывает файлы разными функциями
			$image = imagecreatefromjpeg($file); // создает изображение из jpg

		} elseif ( $ext['type'] == 'image/png' ){
			$image = imagecreatefrompng($file); // создает изображение из png
			imagepalettetotruecolor($image); // восстанавливает цвета
			imagealphablending($image, false); // выключает режим сопряжения цветов
			imagesavealpha($image, true); // сохраняет прозрачность

		}
		imagewebp($image, $uploads['basedir'] . '/' . $metadata['file'] . '.webp', 90); // сохраняет файл в webp

		foreach ($metadata['sizes'] as $size) { // перебирает все размеры файла и также сохраняет в webp
			$file = $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'];
			$ext = $size['mime-type'];

			if ( $ext == 'image/jpeg' ) {
				$image = imagecreatefromjpeg($file);

			} elseif ( $ext == 'image/png' ){
				$image = imagecreatefrompng($file);
				imagepalettetotruecolor($image);
				imagealphablending($image, false);
				imagesavealpha($image, true);
			}

			imagewebp($image, $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'] . '.webp', 90);

		}

		return $metadata;
	}


}