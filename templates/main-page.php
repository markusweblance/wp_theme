<?php
/*
Template Name: Главная страница
*/
?>
<?php get_header() ?>
<main class="main">
    <section class="first">
        <div class="first__body">
            <h1><?= $post->post_title ?></h1>
			<?php if ( $images = carbon_get_post_meta( $post->ID, 'crb_img' ) ):
				foreach ( $images as $image ):
					?>
                    <div class="first__item">
                        <picture>
                            <source media="(max-width: 992px)"
                                    srcset="<?= wp_get_attachment_image_url( $image['img'], 'medium' ) ?>.webp" type="image/webp">
                            <source media="(max-width: 1920px)"
                                    srcset="<?= wp_get_attachment_image_url( $image['img'], 'full' ) ?>.webp" type="image/webp">
                            <source srcset="<?= wp_get_attachment_image_url( $image['img'], 'full' ) ?>">
                            <img src="<?= wp_get_attachment_image_url( $image['img'], 'full' ) ?>" alt="">
                        </picture>
                    </div>
				<?php endforeach; endif; ?>
        </div>
    </section>
</main>
<?php get_footer() ?>
