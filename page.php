<?php

get_header(); ?>

<div class="content-area">
    <main id="main" class="site-main" role="main">
		<h1><?= $post->post_title ?></h1>
		<?php the_content(); ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
