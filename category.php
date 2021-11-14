<?php get_header() ?>
<?php
$wp_query = new WP_Query([
	'posts_per_page' => 5,
	'cat' => get_queried_object_id(),
	'paged' => $_GET['page'] ? $_GET['page'] : 1,
]);
foreach ($wp_query->posts as $post){
	echo $post->post_title . '<br>';
}
echo paginate_links([
	'format' => '?page=%#%',
	'type' => 'list',
]);
wp_reset_query();
?>

<?php get_footer() ?>
