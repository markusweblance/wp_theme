<?php get_header(); ?>

<div class="content-area">
	<main id="main" class="site-main" role="main">
		
        <?php while (have_posts()){
            the_post();
            the_content();
        } ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>