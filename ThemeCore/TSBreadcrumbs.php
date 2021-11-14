<?php


namespace TSTheme;


class TSBreadcrumbs {

	public static function breadcrumbs() {

		// получаем номер текущей страницы
		$page_num = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

		$separator = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 aria-hidden="true" role="img" width="11.63" height="15" preserveAspectRatio="xMidYMid meet"
                 viewBox="0 0 992 1280">
                <g transform="translate(992 0) scale(-1 1)">
                    <path
                            d="M595 1120q0 13-10 23l-50 50q-10 10-23 10t-23-10L23 727q-10-10-10-23t10-23l466-466q10-10 23-10t23 10l50 50q10 10 10 23t-10 23L192 704l393 393q10 10 10 23zm384 0q0 13-10 23l-50 50q-10 10-23 10t-23-10L407 727q-10-10-10-23t10-23l466-466q10-10 23-10t23 10l50 50q10 10 10 23t-10 23L576 704l393 393q10 10 10 23z"
                            fill="#999"/>
                </g>
            </svg>'; //  разделяем обычным слэшем, но можете чем угодно другим

		// For Custom Post Type
		$custom_post_types = [ 'technology', 'mag_products', 'primenenie', 'projects', 'news' ];

		// если главная страница сайта
		if ( is_front_page() ) {

			if ( $page_num > 1 ) {
				echo '<li><a href="' . home_url() . '">' . __( 'Главная', 'magnet' ) . '</a></li>' . $separator . $page_num . '-я страница';
			} else {
				echo 'Вы находитесь на главной странице';
			}

		} else { // не главная

			echo '<li><a href="' . home_url() . '">' . __( 'Главная', 'magnet' ) . '</a></li>' . $separator;


			if ( is_single() ) { // записи

				global $post;


				if ( $post->post_type != 'post' ) {
					foreach ( $custom_post_types as $post_type ) {
						if ( $post->post_type == $post_type ) {
							$obj = get_post_type_object( $post_type );
							if ( $obj->has_archive ) {
								echo '<a href="' . get_post_type_archive_link( $post_type ) . '">' . $obj->labels->name . '</a>';
							} else {
								echo '<span class="breadcrumbs__notlink">' . $obj->labels->name . '</span>';
							}
							echo $separator;
							echo '<li class="current"><span>' . $post->post_title . '</span></li>';
						}
					}

				} else {

					the_category( ', ' );
					echo $separator;
					the_title();

				}

			} elseif ( is_archive() ) {
				foreach ( $custom_post_types as $post_type ) {
					$obj = get_post_type_object( $post_type );
					if ( get_queried_object()->name == $post_type ) {
						echo '<span>' . $obj->labels->name . '</span>';
					}
				}
			} elseif ( is_page() ) { // страницы WordPress

				echo '<span>' . get_the_title() . '</span>';

			} elseif ( is_category() ) {

				single_cat_title();

			} elseif ( is_tag() ) {

				single_tag_title();

			} elseif ( is_day() ) { // архивы (по дням)

				echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $separator;
				echo '<a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '">' . get_the_time( 'F' ) . '</a>' . $separator;
				echo get_the_time( 'd' );

			} elseif ( is_month() ) { // архивы (по месяцам)

				echo '<a href="' . get_year_link( get_the_time( 'Y' ) ) . '">' . get_the_time( 'Y' ) . '</a>' . $separator;
				echo get_the_time( 'F' );

			} elseif ( is_year() ) { // архивы (по годам)

				echo get_the_time( 'Y' );

			} elseif ( is_author() ) { // архивы по авторам

				global $author;
				$userdata = get_userdata( $author );
				echo 'Опубликовал(а) ' . $userdata->display_name;

			} elseif ( is_404() ) { // если страницы не существует

				echo 'Ошибка 404';

			}

			if ( $page_num > 1 ) { // номер текущей страницы
				echo ' (' . $page_num . '-я страница)';
			}

		}

	}
}