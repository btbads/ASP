<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	/* WordPress Administration menu */
	function woo_ce_admin_menu() {

		add_submenu_page( 'woocommerce', __( 'Store Export', 'woo_ce' ), __( 'Store Export', 'woo_ce' ), 'manage_options', 'woo_ce', 'woo_ce_html_page' );

	}
	add_action( 'admin_menu', 'woo_ce_admin_menu' );

	function woo_ce_template_header( $title = '', $icon = 'tools' ) {

		global $woo_ce;

		if( $title )
			$output = $title;
		else
			$output = $woo_ce['menu'];
		$icon = woo_is_admin_icon_valid( $icon ); ?>
<div class="wrap">
	<div id="icon-<?php echo $icon; ?>" class="icon32"><br /></div>
	<h2><?php echo $output; ?></h2>
<?php
	}

	function woo_ce_template_footer() { ?>
</div>
<?php
	}

	function woo_ce_support_donate() {

		global $woo_ce;

		$output = '';
		$show = true;
		if( function_exists( 'woo_vl_we_love_your_plugins' ) ) {
			if( in_array( $woo_ce['dirname'], woo_vl_we_love_your_plugins() ) )
				$show = false;
		}
		if( function_exists( 'woo_cd_admin_init' ) )
			$show = false;
		if( $show ) {
			$donate_url = 'http://www.visser.com.au/#donations';
			$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . $woo_ce['dirname'];
			$output = '
	<div id="support-donate_rate" class="support-donate_rate">
		<p>' . sprintf( __( '<strong>Like this Plugin?</strong> %s and %s.', 'woo_ce' ), '<a href="' . $donate_url . '" target="_blank">' . __( 'Donate to support this Plugin', 'woo_ce' ) . '</a>', '<a href="' . add_query_arg( array( 'rate' => '5' ), $rate_url ) . '#postform" target="_blank">rate / review us on WordPress.org</a>' ) . '</p>
	</div>
';
		}
		echo $output;

	}

	function woo_ce_return_count( $dataset ) {

		global $wpdb;

		$count_sql = null;
		switch( $dataset ) {

			/* WooCommerce */

			case 'products':
				$post_type = 'product';
				$count = wp_count_posts( $post_type );
				break;

			case 'tags':
				$term_taxonomy = 'product_tag';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'categories':
				$term_taxonomy = 'product_cat';
				$count = wp_count_terms( $term_taxonomy );
				break;

			case 'orders':
				$post_type = 'shop_order';
				$count = wp_count_posts( $post_type );
				break;

			case 'coupons':
				$post_type = 'shop_coupon';
				$count = wp_count_posts( $post_type );
				break;

			case 'customers':
				$post_type = 'shop_order';
				$count_sql = $wpdb->prepare( "SELECT DISTINCT `post_author` FROM  `" . $wpdb->posts . "` WHERE `post_type` = '%s'", $post_type );
				break;

		}
		if( isset( $count ) || $count_sql ) {
			if( isset( $count ) ) {
				if( is_object( $count ) ) {
					$count_object = $count;
					$count = 0;
					foreach( $count_object as $key => $item )
						$count = $item + $count;
				}
				return $count;
			} else {
				if( $count_sql )
					$count = $wpdb->get_var( $count_sql );
				else
					$count = 0;
			}
			return $count;
		} else {
			return 0;
		}

	}

	function woo_ce_export_dataset( $dataset ) {

		global $wpdb, $woo_ce, $export;

		$csv = '';
		foreach( $dataset as $datatype ) {

			$csv = null;
			switch( $datatype ) {

				case 'products':
					$fields = woo_ce_get_product_fields( 'summary' );
					$export->fields = array_intersect_assoc( $fields, $export->fields );
					if( $export->fields ) {
						foreach( $export->fields as $key => $field )
							$export->columns[] = woo_ce_get_product_field( $key );
					}
					$size = count( $export->columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= escape_csv_value( $export->columns[$i] ) . "\n";
						else
							$csv .= escape_csv_value( $export->columns[$i] ) . $export->delimiter;
					}
					$products = woo_ce_get_products();
					if( $products ) {
						foreach( $products as $product ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $product->$key ) ) {
									if( is_array( $value ) ) {
										foreach( $value as $array_key => $array_value ) {
											if( !is_array( $array_value ) )
												$csv .= escape_csv_value( $array_value );
										}
									} else {
										$csv .= escape_csv_value( $product->$key );
									}
								}
								$csv .= $export->delimiter;
							}
							$csv .= "\n";

						}
						unset( $products, $product );
					}
					break;

				case 'categories':
					$term_taxonomy = 'product_cat';
					$args = array(
						'hide_empty' => 0
					);
					$categories = get_terms( $term_taxonomy, $args );
					if( $categories ) {
						$columns = array(
							__( 'Category', 'woo_ce' )
						);
						for( $i = 0; $i < count( $columns ); $i++ ) {
							if( $i == ( count( $columns ) - 1 ) )
								$csv .= $columns[$i] . "\n";
							else
								$csv .= $columns[$i] . $export->delimiter;
						}
						foreach( $categories as $category ) {
							$csv .= 
								$category->name
								 . 
							"\n";
						}
						unset( $categories, $category );
					}
					break;

				case 'tags':
					$term_taxonomy = 'product_tag';
					$args = array(
						'hide_empty' => 0
					);
					$tags = get_terms( $term_taxonomy, $args );
					if( $tags ) {
						$columns = array(
							__( 'Tags', 'woo_ce' )
						);
						for( $i = 0; $i < count( $columns ); $i++ ) {
							if( $i == ( count( $columns ) - 1 ) )
								$csv .= $columns[$i] . "\n";
							else
								$csv .= $columns[$i] . $export->delimiter;
						}
						foreach( $tags as $tag ) {
							$csv .= 
								$tag->name
								 . 
							"\n";
						}
						unset( $tags, $tag );
					}
					break;

				case 'orders':
					$fields = woo_ce_get_sale_fields( 'summary' );
					$export->fields = array_intersect_assoc( $fields, $export->fields );
					if( $export->fields ) {
						foreach( $export->fields as $key => $field )
							$export->columns[] = woo_ce_get_sale_field( $key );
					}
					$size = count( $export->columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= '"' . $export->columns[$i] . "\"\n";
						else
							$csv .= '"' . $export->columns[$i] . '"' . $export->delimiter;
					}
					$orders = woo_ce_get_orders();
					if( $orders ) {
						foreach( $orders as $order ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $order->$key ) ) {
									if( is_array( $value ) ) {
										foreach( $value as $array_key => $array_value ) {
											if( !is_array( $array_value ) )
												$csv .= escape_csv_value( $array_value );
										}
									} else {
										$csv .= escape_csv_value( $order->$key );
									}
								}
								$csv .= $export->delimiter;
							}
							$csv .= "\n";
						}
						unset( $orders, $order );
					}
					break;

				case 'coupons':
					$fields = woo_ce_get_coupon_fields( 'summary' );
					$export->fields = array_intersect_assoc( $fields, $export->fields );
					if( $export->fields ) {
						foreach( $export->fields as $key => $field )
							$export->columns[] = woo_ce_get_coupon_field( $key );
					}
					$size = count( $export->columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= escape_csv_value( $export->columns[$i] ) . "\n";
						else
							$csv .= escape_csv_value( $export->columns[$i] ) . $export->delimiter;
					}
					$coupons = woo_ce_get_coupons();
					if( $coupons ) {
						foreach( $coupons as $coupon ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $coupon->$key ) ) {
									if( is_array( $value ) ) {
										foreach( $value as $array_key => $array_value ) {
											if( !is_array( $array_value ) )
												$csv .= escape_csv_value( $array_value );
										}
									} else {
										$csv .= escape_csv_value( $coupon->$key );
									}
								}
								$csv .= $export->delimiter;
							}
							$csv .= "\n";
						}
						unset( $coupons, $coupon );
					}
					break;

				case 'customers':
					$fields = woo_ce_get_customer_fields( 'summary' );
					$export->fields = array_intersect_assoc( $fields, $export->fields );
					if( $export->fields ) {
						foreach( $export->fields as $key => $field )
							$export->columns[] = woo_ce_get_customer_field( $key );
					}
					$size = count( $export->columns );
					for( $i = 0; $i < $size; $i++ ) {
						if( $i == ( $size - 1 ) )
							$csv .= escape_csv_value( $export->columns[$i] ) . "\n";
						else
							$csv .= escape_csv_value( $export->columns[$i] ) . $export->delimiter;
					}
					$orders = woo_ce_get_orders( 'customers' );
					if( $orders ) {
						foreach( $orders as $order ) {
							foreach( $export->fields as $key => $field ) {
								if( isset( $order->$key ) ) {
									if( is_array( $value ) ) {
										foreach( $value as $array_key => $array_value ) {
											if( !is_array( $array_value ) )
												$csv .= escape_csv_value( $array_value );
										}
									} else {
										$csv .= escape_csv_value( $order->$key );
									}
								}
								$csv .= $export->delimiter;
							}
							$csv .= "\n";
						}
						unset( $orders, $order );
					}
					break;

			}

			if( isset( $woo_ce['debug'] ) && $woo_ce['debug'] )
				echo '<code>' . str_replace( "\n", '<br />', $csv ) . '</code>' . '<br />';
			else
				echo $csv;

		}

	}

	function woo_ce_get_orders( $export = 'orders' ) {

		global $wpdb;

		$post_type = 'shop_order';
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'post_status' => woo_ce_post_statuses()
		);
		$orders = get_posts( $args );
		if( $orders ) {
			foreach( $orders as $key => $order ) {
				switch( $export ) {

					case 'orders':
						$orders[$key]->purchase_id = $order->ID;
						$orders[$key]->purchase_total = get_post_meta( $order->ID, '_order_total', true );
						$orders[$key]->payment_gateway = get_post_meta( $order->ID, '_payment_method', true );
						$orders[$key]->shipping_method = get_post_meta( $order->ID, '_shipping_method', true );
						$orders[$key]->purchase_date = mysql2date( 'd/m/Y', strtotime( $order->post_date ) );
						break;

					case 'customers':
						$orders[$key]->first_name = get_post_meta( $order->ID, '_billing_first_name', true );
						$orders[$key]->last_name = get_post_meta( $order->ID, '_billing_last_name', true );
						$orders[$key]->full_name = $order->first_name . ' ' . $order->last_name;
						$orders[$key]->billing_address = get_post_meta( $order->ID, '_billing_address_1', true );
						$orders[$key]->billing_address_alt = get_post_meta( $order->ID, '_billing_address_2', true );
						if( $order->billing_address_alt )
							$orders[$key]->billing_address .= ' ' . $order->billing_address_alt;
						$orders[$key]->billing_city = get_post_meta( $order->ID, '_billing_city', true );
						$orders[$key]->billing_postcode = get_post_meta( $order->ID, '_billing_postcode', true );
						$orders[$key]->billing_state = get_post_meta( $order->ID, '_billing_state', true );
						$orders[$key]->billing_country = get_post_meta( $order->ID, '_billing_country', true );
						$orders[$key]->billing_phone = get_post_meta( $order->ID, '_billing_phone', true );
						$orders[$key]->billing_email = get_post_meta( $order->ID, '_billing_email', true );
						break;

				}
			}
		}
		return $orders;

	}

	function woo_ce_get_coupons() {

		$post_type = 'shop_coupon';
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'post_status' => woo_ce_post_statuses()
		);
		$coupons = get_posts( $args );
		return $coupons;

	}

	function woo_ce_get_products() {

		$post_type = 'product';
		$args = array(
			'post_type' => $post_type,
			'numberposts' => -1,
			'post_status' => woo_ce_post_statuses()
		);
		$products = get_posts( $args );
		if( $products ) {
			$weight_unit = get_option( 'woocommerce_weight_unit' );
			$dimension_unit = get_option( 'woocommerce_dimension_unit' );
			$height_unit = $dimension_unit;
			$width_unit = $dimension_unit;
			$length_unit = $dimension_unit;
			foreach( $products as $key => $product ) {
				$products[$key]->sku = get_post_meta( $product->ID, '_sku', true );
				$products[$key]->name = $product->post_title;
				$products[$key]->permalink = $product->post_name;
				$products[$key]->description = woo_ce_clean_html( $product->post_content );
				$products[$key]->excerpt = woo_ce_clean_html( $product->post_excerpt );
				$products[$key]->price = get_post_meta( $product->ID, '_price', true );
				$products[$key]->sale_price = get_post_meta( $product->ID, '_sale_price', true );
				$products[$key]->weight = get_post_meta( $product->ID, '_weight', true );
				$products[$key]->weight_unit = $weight_unit;
				$products[$key]->height = get_post_meta( $product->ID, '_height', true );
				$products[$key]->height_unit = $height_unit;
				$products[$key]->width = get_post_meta( $product->ID, '_width', true );
				$products[$key]->width_unit = $width_unit;
				$products[$key]->length = get_post_meta( $product->ID, '_length', true );
				$products[$key]->length_unit = $length_unit;
				$products[$key]->category = woo_ce_get_product_categories( $product->ID );
				$products[$key]->tag = woo_ce_get_product_tags( $product->ID );
				$products[$key]->quantity = get_post_meta( $product->ID, '_stock', true );
				$products[$key]->external_link = get_post_meta( $product->ID, '_product_url', true );
				$products[$key]->product_status = woo_ce_format_product_status( $product->post_status );
				$products[$key]->comment_status = woo_ce_format_comment_status( $product->comment_status );
			}
		}
		return $products;

	}

	function woo_ce_get_product_categories( $product_id = null ) {

		global $export;

		$output = '';
		$term_taxonomy = 'product_cat';
		$categories = wp_get_object_terms( $product_id, $term_taxonomy );
		if( $categories ) {
			$size = count( $categories );
			for( $i = 0; $i < $size; $i++ ) {
				if( $categories[$i]->parent == '0' ) {
					$output .= $categories[$i]->name . $export->category_separator;
				} else {
					// Check if Parent -> Child
					$parent_category = get_term( $categories[$i]->parent, $term_taxonomy );
					// Check if Parent -> Child -> Subchild
					if( $parent_category->parent == '0' ) {
						$output .= $parent_category->name . '>' . $categories[$i]->name . $export->category_separator;
						$output = str_replace( $parent_category->name . $export->category_separator, '', $output );
					} else {
						$root_category = get_term( $parent_category->parent, $term_taxonomy );
						$output .= $root_category->name . '>' . $parent_category->name . '>' . $categories[$i]->name . $export->category_separator;
						$output = str_replace( array(
							$root_category->name . '>' . $parent_category->name . $export->category_separator,
							$parent_category->name . $export->category_separator
						), '', $output );
					}
					unset( $root_category, $parent_category );
				}
			}
			$output = substr( $output, 0, -1 );
		} else {
			$output .= __( 'Uncategorized', 'woo_ce' );
		}
		return $output;

	}

	function woo_ce_get_product_tags( $product_id ) {

		global $export;

		$output = '';
		$term_taxonomy = 'product_tag';
		$tags = wp_get_object_terms( $product_id, $term_taxonomy );
		if( $tags ) {
			$size = count( $tags );
			for( $i = 0; $i < $size; $i++ ) {
				$tag = get_term( $tags[$i]->term_id, $term_taxonomy );
				$output .= $tag->name . $export->category_separator;
			}
			$output = substr( $output, 0, -1 );
		}
		return $output;

	}

	function woo_ce_generate_csv_header( $dataset = '' ) {

		$filename = 'woo-export_' . $dataset . '.csv';

		header( 'Content-type: application/csv' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

	}

	function woo_ce_has_value( $value = '' ) {

		switch( $value ) {

			case '0':
				$value = null;
				break;

			default:
				if( is_string( $value ) )
					$value = htmlspecialchars_decode( $value );
				break;

		}
		return $value;

	}

	if( !function_exists( 'escape_csv_value' ) ) {
		function escape_csv_value( $value ) {

			$value = str_replace( '"', '""', $value ); // First off escape all " and make them ""
			$value = str_replace( PHP_EOL, ' ', $value );
			return '"' . $value . '"'; // If I have new lines or commas escape them

		}
	}

	function woo_ce_post_statuses() {

		$output = array(
			'publish',
			'pending',
			'draft',
			'future',
			'private',
			'trash'
		);
		return $output;

	}

	function woo_ce_get_product_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array( 
			'name' => 'sku',
			'label' => __( 'SKU', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'name',
			'label' => __( 'Product Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'permalink',
			'label' => __( 'Permalink', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'description',
			'label' => __( 'Description', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'excerpt',
			'label' => __( 'Excerpt', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'price',
			'label' => __( 'Price', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'sale_price',
			'label' => __( 'Sale Price', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'weight',
			'label' => __( 'Weight', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'weight_unit',
			'label' => __( 'Weight Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'height',
			'label' => __( 'Height', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'height_unit',
			'label' => __( 'Height Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'width',
			'label' => __( 'Width', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'width_unit',
			'label' => __( 'Width Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'length',
			'label' => __( 'Length', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'length_unit',
			'label' => __( 'Length Unit', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'category',
			'label' => __( 'Category', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'tag',
			'label' => __( 'Tag', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'quantity',
			'label' => __( 'Quantity', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'external_link',
			'label' => __( 'External Link', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'product_status',
			'label' => __( 'Product Status', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'comment_status',
			'label' => __( 'Comment Status', 'woo_ce' ),
			'default' => 1
		);

/*
		$fields[] = array( 
			'name' => '',
			'label' => __( '', 'woo_ce' ),
			'default' => 1
		);
*/

		/* Allow Plugin/Theme authors to add support for additional Product columns */
		$fields = apply_filters( 'woo_ce_product_fields', $fields );

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_product_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_product_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	function woo_ce_get_sale_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array( 
			'name' => 'purchase_id',
			'label' => __( 'Purchase ID', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'purchase_total',
			'label' => __( 'Purchase Total', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'payment_gateway',
			'label' => __( 'Payment Gateway', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'shipping_method',
			'label' => __( 'Shipping Method', 'woo_ce' ),
			'default' => 0
		);
		$fields[] = array( 
			'name' => 'payment_status',
			'label' => __( 'Payment Status', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'purchase_date',
			'label' => __( 'Purchase Date', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'notes',
			'label' => __( 'Notes', 'woo_ce' ),
			'default' => 0
		);
/*
		$fields[] = array( 
			'name' => '',
			'label' => __( '', 'woo_ce' ),
			'default' => 1
		);
*/

		/* Allow Plugin/Theme authors to add support for additional Sale columns */
		$fields = apply_filters( 'woo_ce_sale_fields', $fields );

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_sale_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_sale_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	function woo_ce_get_coupon_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array( 
			'name' => 'coupon_code',
			'label' => __( 'Coupon Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'coupon_type',
			'label' => __( 'Coupon Type', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'coupon_amount',
			'label' => __( 'Coupon Amount', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'product_ids',
			'label' => __( 'Product ID\'s', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'from',
			'label' => __( 'From', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'to',
			'label' => __( 'To', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'alone',
			'label' => __( 'Alone', 'woo_ce' ),
			'default' => 1
		);

		/* Allow Plugin/Theme authors to add support for additional Coupon columns */
		$fields = apply_filters( 'woo_ce_coupon_fields', $fields );

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_coupon_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_coupon_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	function woo_ce_get_customer_fields( $format = 'full' ) {

		$fields = array();
		$fields[] = array( 
			'name' => 'full_name',
			'label' => __( 'Full Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'first_name',
			'label' => __( 'First Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'last_name',
			'label' => __( 'Last Name', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_address',
			'label' => __( 'Street Address', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_city',
			'label' => __( 'City', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_state',
			'label' => __( 'State', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_postcode',
			'label' => __( 'ZIP Code', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_country',
			'label' => __( 'Country', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_phone',
			'label' => __( 'Phone Number', 'woo_ce' ),
			'default' => 1
		);
		$fields[] = array( 
			'name' => 'billing_email',
			'label' => __( 'E-mail Address', 'woo_ce' ),
			'default' => 1
		);

		/* Allow Plugin/Theme authors to add support for additional Customer columns */
		$fields = apply_filters( 'woo_ce_customer_fields', $fields );

		switch( $format ) {

			case 'summary':
				$output = array();
				$size = count( $fields );
				for( $i = 0; $i < $size; $i++ )
					$output[$fields[$i]['name']] = 'on';
				return $output;
				break;

			case 'full':
			default:
				return $fields;

		}

	}

	function woo_ce_get_customer_field( $name = null, $format = 'name' ) {

		$output = '';
		if( $name ) {
			$fields = woo_ce_get_customer_fields();
			$size = count( $fields );
			for( $i = 0; $i < $size; $i++ ) {
				if( $fields[$i]['name'] == $name ) {
					switch( $format ) {

						case 'name':
							$output = $fields[$i]['label'];
							break;

						case 'full':
							$output = $fields[$i];
							break;

					}
					$i = $size;
				}
			}
		}
		return $output;

	}

	function woo_ce_admin_active_tab( $tab_name = null, $tab = null ) {

		if( isset( $_GET['tab'] ) && !$tab )
			$tab = $_GET['tab'];
		else
			$tab = 'overview';

		$output = '';
		if( isset( $tab_name ) && $tab_name ) {
			if( $tab_name == $tab )
				$output = ' nav-tab-active';
		}
		echo $output;

	}

	function woo_ce_tab_template( $tab = '' ) {

		global $woo_ce;

		if( !$tab )
			$tab = 'overview';

		/* Store Exporter Deluxe */
		$woo_cd_exists = false;
		if( !function_exists( 'woo_cd_admin_init' ) ) {
			$woo_cd_url = 'http://www.visser.com.au/woocommerce/plugins/exporter-deluxe/';
			$woo_cd_link = sprintf( '<a href="%s" target="_blank">' . __( 'Store Exporter Deluxe', 'woo_ce' ) . '</a>', $woo_cd_url );
		} else {
			$woo_cd_exists = true;
		}

		switch( $tab ) {

			case 'export':
				if( isset( $_POST['dataset'] ) )
					$dataset = $_POST['dataset'];
				else
					$dataset = 'products';

				$products = woo_ce_return_count( 'products' );
				$categories = woo_ce_return_count( 'categories' );
				$tags = woo_ce_return_count( 'tags' );
				$sales = woo_ce_return_count( 'orders' );
				$coupons = woo_ce_return_count( 'coupons' );
				$customers = woo_ce_return_count( 'customers' );

				$product_fields = woo_ce_get_product_fields();
				$sale_fields = woo_ce_get_sale_fields();
				$customer_fields = woo_ce_get_customer_fields();
				$coupon_fields = woo_ce_get_coupon_fields();
				break;

			case 'tools':
				/* Product Importer Deluxe */
				if( function_exists( 'woo_pd_init' ) ) {
					$woo_pd_url = add_query_arg( 'page', 'woo_pd' );
					$woo_pd_target = false;
				} else {
					$woo_pd_url = 'http://www.visser.com.au/woocommerce/plugins/product-importer-deluxe/';
					$woo_pd_target = ' target="_blank"';
				}
				break;

		}
		if( $tab )
			include_once( $woo_ce['abspath'] . '/templates/admin/woo-admin_ce-export_' . $tab . '.php' );

	}

	/* End of: WordPress Administration */

}
?>