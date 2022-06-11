<?php


namespace TSTheme;
use TSTheme\TS_Image_Editor_GD;

class TSImageWebp
{


	public static function start()
	{
		add_filter('wp_image_editors', [static::class, 'image_editors']);
		add_filter('wp_generate_attachment_metadata', [static::class, 'gt_webp_generation']);
		add_action('delete_attachment', [static::class, 'del_webp_generation'], 10, 2);
	}


	public static function image_editors()
	{
		return	[TS_Image_Editor_GD::class];
	}

	/**
	 * Генерирует webp копии изображений сразу после загрузки изображения в медиабиблиотеку
	 *
	 * - новые файлы сохраняет с именем name.ext.webp, например, thumb.jpg.webp
	 */
	public static function gt_webp_generation($metadata)
	{
		$uploads = wp_upload_dir(); // получает папку для загрузки медиафайлов

		$file = $uploads['basedir'] . '/' . $metadata['file']; // получает исходный файл
		$ext = wp_check_filetype($file); // получает расширение файла

		if ($ext['type'] == 'image/jpeg') { // в зависимости от расширения обрабатаывает файлы разными функциями
			$image = imagecreatefromjpeg($file); // создает изображение из jpg

		} elseif ($ext['type'] == 'image/png') {
			$image = imagecreatefrompng($file); // создает изображение из png
			imagepalettetotruecolor($image); // восстанавливает цвета
			imagealphablending($image, false); // выключает режим сопряжения цветов
			imagesavealpha($image, true); // сохраняет прозрачность

		} else {
			return $metadata;
		}

		imagewebp($image, $uploads['basedir'] . '/' . $metadata['file'] . '.webp', 90); // сохраняет файл в webp

		return $metadata;
	}

	/**
	 * Удаление сгенерированных файлов webp
	 * @param $post_id
	 * @param $post
	 */
	public static function del_webp_generation($post_id, $post)
	{
		$uploads = wp_upload_dir();
		$file    = get_attached_file($post_id);
		wp_delete_file_from_directory($file . '.webp', $uploads['basedir']);

		$backup_sizes = get_post_meta($post->ID, '_wp_attachment_backup_sizes', true);
		$metadata         = wp_get_attachment_metadata($post_id);

		$intermediate_dir = path_join($uploads['basedir'], dirname($file));
		foreach ($metadata['sizes'] as $size => $sizeinfo) {
			$intermediate_file = str_replace(wp_basename($file), $sizeinfo['file'], $file);

			if (!empty($intermediate_file)) {
				$intermediate_file = path_join($uploads['basedir'], $intermediate_file);

				if (!wp_delete_file_from_directory($intermediate_file . '.webp', $intermediate_dir)) {
					$deleted = false;
				}
			}
		}

		if (is_array($backup_sizes)) {
			$del_dir = path_join($uploads['basedir'], dirname($metadata['file']));

			foreach ($backup_sizes as $size) {
				$del_file = path_join(dirname($metadata['file']), $size['file']);

				if (!empty($del_file)) {
					$del_file = path_join($uploads['basedir'], $del_file);

					if (!wp_delete_file_from_directory($del_file . '.webp', $del_dir)) {
						$deleted = false;
					}
				}
			}
		}
	}
}
