<?php


namespace TSTheme;


class TSWoo {

	public static function start() {
		if ( in_array(
			'woocommerce/woocommerce.php',
			apply_filters( 'active_plugins',
				get_option( 'active_plugins' ) ) ) ) {
			add_filter( 'woocommerce_breadcrumb_defaults', [ static::class, 'breadcrumb' ] );
			add_filter( 'woocommerce_add_to_cart_fragments', [ static::class, 'header_add_to_cart_fragment' ] );
			add_filter( "loop_shop_per_page", [ static::class, 'pagination' ], 20 );
		}
	}

	/**
	 *  Woocommerce breadcrumbs
	 *
	 * @return string[]
	 */
	public function breadcrumb() {
		return [
			'delimiter'   => '',
			'wrap_before' => '<nav class="breadcumbs container"><span class="head">Nav:</span><ul>
							  <li><a href="/">Главная</a></li>',
			'wrap_after'  => '</ul></nav>',
			'before'      => '',
			'after'       => '',
			'home'        => '',
		];
	}


	/** Ajax Cart in header
	 *
	 * @param $fragments
	 *
	 * @return mixed
	 */
	public function header_add_to_cart_fragment() {
		$cart  = WC()->cart;
		$count = $cart->get_cart_contents_count();
		ob_start();
		?>
        <span class="cart__quantity"><?= $count ?></span>
		<?php
		$fragments['.cart__quantity'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * @return string
	 */
	public function cartQuantity() {
		$cart  = WC()->cart;
		$count = $cart->get_cart_contents_count();

		return '<span class="cart__quantity">' . $count . '</span>';
	}

	/**
	 * Product per_page (pagination)
	 * @return int
	 */
	public function pagination() {
		return 3;
	}

}