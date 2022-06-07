<?php


namespace TSTheme;


class TSThemeFunctions
{

	public static array $disable_template = [];

	public static function start()
	{
		add_action('admin_init', [static::class, 'disable_content_editor']);
		add_filter('wp_generate_attachment_metadata', [static::class, 'gt_webp_generation']);
		add_filter('wp_save_image_editor_file', [static::class, 'gt_webp_generation_edit'], 10, 5);
		add_action('delete_attachment', [static::class, 'del_webp_generation'], 10, 2);
	}


	public static function disable_content_editor()
	{

		if (isset($_GET['post'])) {
			$post_ID = $_GET['post'];
		} else if (isset($_POST['post_ID'])) {
			$post_ID = $_POST['post_ID'];
		}

		if (!isset($post_ID) || empty($post_ID)) {
			return;
		}

		foreach (self::$disable_template as $item) {
			$page_template = get_post_meta($post_ID, '_wp_page_template', true);
			if ($page_template == $item['template']) {
				remove_post_type_support($item['type'], 'editor');
			}
		}
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

		foreach ($metadata['sizes'] as $size) { // перебирает все размеры файла и также сохраняет в webp
			$file = $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'];
			$ext = $size['mime-type'];

			if ($ext == 'image/jpeg') {
				$image = imagecreatefromjpeg($file);
			} elseif ($ext == 'image/png') {
				$image = imagecreatefrompng($file);
				imagepalettetotruecolor($image);
				imagealphablending($image, false);
				imagesavealpha($image, true);
			}

			imagewebp($image, $uploads['basedir'] . $uploads['subdir'] . '/' . $size['file'] . '.webp', 90);
		}

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


	/**
	 * Генерирует webp копии изображений после редактрования изображения
	 * @param mixed $saved
	 * @param mixed $filename
	 * @param mixed $image
	 * @param mixed $mime_type
	 * @param mixed $post_id
	 * 
	 * @return [type]
	 */
	public static function gt_webp_generation_edit($saved, $filename, $image, $mime_type, $post_id)
	{

		$saved = $image->save($filename, $mime_type);
		$file = $saved['path']; // получает исходный файл


		if ($saved['mime-type'] == 'image/jpeg') { // в зависимости от расширения обрабатаывает файлы разными функциями
			$new_image = imagecreatefromjpeg($file); // создает изображение из jpg

		} elseif ($saved['mime-type'] == 'image/png') {
			$new_image = imagecreatefrompng($file); // создает изображение из png
			imagepalettetotruecolor($new_image); // восстанавливает цвета
			imagealphablending($new_image, false); // выключает режим сопряжения цветов
			imagesavealpha($new_image, true); // сохраняет прозрачность

		} else {
			return $saved;
		}
		imagewebp($new_image, $file . '.webp', 90); // сохраняет файл в webp

		return $saved;
	}
}
