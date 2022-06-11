<?php


namespace TSTheme;


class TSThemeFunctions
{
	public static array $disable_template = [];

	public static function start()
	{
		add_action('admin_init', [static::class, 'disable_content_editor']);
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
}
