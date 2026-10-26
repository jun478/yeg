<?php
/**
 * Return the shuttle bus guide data available to the shortcode.
 *
 * @return array
 */
function yeg_get_bus_guide_registry() {
	return array(
		'01' => array(
			'page_label'  => 'Page 01',
			'event'       => '会員総会 輸送計画',
			'direction'   => '午前：JTAドームへ輸送',
			'destination' => 'JTAドーム',
			'overview'    => array(
				array( 'icon' => 'clock', 'label' => '開始時間', 'value' => '9:00' ),
				array( 'icon' => 'users', 'label' => '対象人数', 'value' => '約1,000名' ),
				array( 'icon' => 'bus', 'label' => '乗車場所', 'value' => '特設市内バス停留所3ヶ所' ),
				array( 'icon' => 'pin', 'label' => '到着場所', 'value' => 'JTAドーム' ),
				array( 'icon' => 'clock', 'label' => '所要時間', 'value' => '約20分' ),
			),
			'routes'      => array(
				array(
					'number'         => '01',
					'theme'          => 'orange',
					'name'           => '宮古島市公設市場',
					'departure_name' => '宮古島市公設市場',
					'people'         => '400名',
					'bus_count'      => '8台',
					'times'          => array(
						array( 'buses' => '1～3号車', 'departure' => '8:00', 'arrival' => '8:20' ),
						array( 'buses' => '4～6号車', 'departure' => '8:10', 'arrival' => '8:30' ),
						array( 'buses' => '7～8号車', 'departure' => '8:20', 'arrival' => '8:40' ),
					),
				),
				array(
					'number'         => '02',
					'theme'          => 'blue',
					'name'           => '旧市役所',
					'departure_name' => '旧市役所',
					'people'         => '400名',
					'bus_count'      => '8台',
					'times'          => array(
						array( 'buses' => '9～11号車', 'departure' => '8:05', 'arrival' => '8:25' ),
						array( 'buses' => '12～14号車', 'departure' => '8:10', 'arrival' => '8:30' ),
						array( 'buses' => '15～16号車', 'departure' => '8:15', 'arrival' => '8:35' ),
					),
				),
				array(
					'number'         => '03',
					'theme'          => 'green',
					'name'           => 'パイナガマ海空すこやか公園',
					'departure_name' => 'パイナガマビーチ',
					'people'         => '200名',
					'bus_count'      => '4台',
					'times'          => array(
						array( 'buses' => '17～18号車', 'departure' => '8:00', 'arrival' => '8:20' ),
						array( 'buses' => '19～20号車', 'departure' => '8:15', 'arrival' => '8:35' ),
					),
				),
			),
			'note'        => '交通状況により前後する可能性があります。余裕をもった運行計画を行います。',
		),
	);
}

/**
 * Render a decorative icon used by the bus guide.
 *
 * @param string $name Icon name.
 * @return string
 */
function yeg_bus_guide_icon( $name ) {
	$icons = array(
		'clock' => '<circle cx="12" cy="12" r="8.5"></circle><path d="M12 7.5v5l3.2 2"></path>',
		'users' => '<circle cx="9" cy="8" r="3"></circle><circle cx="16.5" cy="9" r="2.5"></circle><path d="M3.5 19c.5-4 2.4-6 5.5-6s5 2 5.5 6"></path><path d="M14.3 14c2.9 0 4.8 1.7 5.2 5"></path>',
		'bus'   => '<rect x="4" y="3.5" width="16" height="15" rx="2.5"></rect><path d="M4 9h16M7.5 14h.01M16.5 14h.01M7 18.5v2M17 18.5v2"></path>',
		'pin'   => '<path d="M20 10c0 5.5-8 11-8 11S4 15.5 4 10a8 8 0 1 1 16 0Z"></path><circle cx="12" cy="10" r="2.5"></circle>',
		'info'  => '<circle cx="12" cy="12" r="9"></circle><path d="M12 10.5V17M12 7h.01"></path>',
	);

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return '<svg class="yeg-bus-guide__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $icons[ $name ] . '</svg>';
}

/**
 * Render a shuttle bus guide selected from the static registry.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function yeg_bus_guide_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'page'       => '',
			'show_title' => 'true',
		),
		$atts,
		'yeg_bus_guide'
	);

	$registry = yeg_get_bus_guide_registry();
	$page     = (string) $atts['page'];

	if ( ! isset( $registry[ $page ] ) ) {
		return '';
	}

	$guide      = $registry[ $page ];
	$show_title = ! in_array( strtolower( trim( (string) $atts['show_title'] ) ), array( '0', 'false', 'no', 'off' ), true );
	$title_id   = wp_unique_id( 'yeg-bus-guide-title-' );

	ob_start();
	?>
	<section class="yeg-bus-guide"<?php echo $show_title ? ' aria-labelledby="' . esc_attr( $title_id ) . '"' : ' aria-label="' . esc_attr__( '交通案内', 'gaia_tcd102_child' ) . '"'; ?>>
		<?php if ( $show_title ) : ?>
			<h2 id="<?php echo esc_attr( $title_id ); ?>" class="yeg-bus-guide__title yeg-event-detail__title"><?php esc_html_e( '交通案内', 'gaia_tcd102_child' ); ?></h2>
		<?php endif; ?>

		<div class="yeg-bus-guide__panel">
			<header class="yeg-bus-guide__masthead">
				<div class="yeg-bus-guide__masthead-icon"><?php echo yeg_bus_guide_icon( 'bus' ); ?></div>
				<div class="yeg-bus-guide__masthead-content">
					<p class="yeg-bus-guide__page-label"><?php echo esc_html( $guide['page_label'] ); ?></p>
					<p class="yeg-bus-guide__event"><?php echo esc_html( $guide['event'] ); ?></p>
					<h3 class="yeg-bus-guide__direction"><?php echo esc_html( $guide['direction'] ); ?></h3>
				</div>
			</header>

			<div class="yeg-bus-guide__body">
				<dl class="yeg-bus-guide__overview">
					<?php foreach ( $guide['overview'] as $item ) : ?>
						<div class="yeg-bus-guide__overview-item">
							<dt>
								<span class="yeg-bus-guide__overview-icon"><?php echo yeg_bus_guide_icon( $item['icon'] ); ?></span>
								<span class="yeg-bus-guide__overview-label"><?php echo esc_html( $item['label'] ); ?></span>
							</dt>
							<dd><?php echo esc_html( $item['value'] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>

				<div class="yeg-bus-guide__routes">
					<?php foreach ( $guide['routes'] as $route ) : ?>
						<?php $route_title_id = $title_id . '-route-' . $route['number']; ?>
						<section class="yeg-bus-guide__route yeg-bus-guide__route--<?php echo esc_attr( sanitize_html_class( $route['theme'] ) ); ?>" aria-labelledby="<?php echo esc_attr( $route_title_id ); ?>">
							<header class="yeg-bus-guide__route-summary">
								<p class="yeg-bus-guide__route-number"><span>Route</span> <?php echo esc_html( $route['number'] ); ?></p>
								<h4 id="<?php echo esc_attr( $route_title_id ); ?>" class="yeg-bus-guide__route-title"><?php echo esc_html( $route['name'] ); ?></h4>
								<dl class="yeg-bus-guide__route-facts">
									<div>
										<dt><?php esc_html_e( '移動人数', 'gaia_tcd102_child' ); ?></dt>
										<dd><?php echo esc_html( $route['people'] ); ?></dd>
									</div>
									<div>
										<dt><?php esc_html_e( '運行台数', 'gaia_tcd102_child' ); ?></dt>
										<dd><?php echo esc_html( $route['bus_count'] ); ?></dd>
									</div>
								</dl>
							</header>

							<div class="yeg-bus-guide__route-detail">
								<div class="yeg-bus-guide__route-bar">
									<span class="yeg-bus-guide__route-bar-icon"><?php echo yeg_bus_guide_icon( 'bus' ); ?></span>
									<strong class="yeg-bus-guide__route-place"><?php echo esc_html( $route['departure_name'] ); ?></strong>
									<span class="yeg-bus-guide__route-arrow" aria-hidden="true">→</span>
									<strong class="yeg-bus-guide__route-place"><?php echo esc_html( $guide['destination'] ); ?></strong>
								</div>
								<p class="yeg-bus-guide__scroll-hint"><?php esc_html_e( '時刻表は横にスクロールできます', 'gaia_tcd102_child' ); ?></p>
								<div class="yeg-bus-guide__table-wrap" tabindex="0" role="region" aria-label="<?php echo esc_attr( $route['departure_name'] . 'から' . $guide['destination'] . 'までの運行時刻表' ); ?>">
									<table class="yeg-bus-guide__table">
										<caption class="yeg-bus-guide__caption"><?php echo esc_html( $route['departure_name'] . 'から' . $guide['destination'] . 'までの運行時刻' ); ?></caption>
										<thead>
											<tr>
												<th scope="col"><?php esc_html_e( '号車', 'gaia_tcd102_child' ); ?></th>
												<th scope="col"><?php echo esc_html( '出発（' . $route['departure_name'] . '）' ); ?></th>
												<th scope="col"><?php echo esc_html( '到着（' . $guide['destination'] . '）' ); ?></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $route['times'] as $time ) : ?>
												<tr>
													<th scope="row"><?php echo esc_html( $time['buses'] ); ?></th>
													<td><time datetime="<?php echo esc_attr( str_pad( $time['departure'], 5, '0', STR_PAD_LEFT ) ); ?>"><?php echo esc_html( $time['departure'] ); ?></time></td>
													<td><time datetime="<?php echo esc_attr( str_pad( $time['arrival'], 5, '0', STR_PAD_LEFT ) ); ?>"><?php echo esc_html( $time['arrival'] ); ?></time></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					<?php endforeach; ?>
				</div>

				<div class="yeg-bus-guide__note" role="note">
					<span class="yeg-bus-guide__note-icon"><?php echo yeg_bus_guide_icon( 'info' ); ?></span>
					<p>※<?php echo esc_html( $guide['note'] ); ?></p>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'yeg_bus_guide', 'yeg_bus_guide_shortcode' );

/**
 * Append the Page 01 guide to the designated event detail.
 *
 * @param string $content Post content.
 * @return string
 */
function yeg_append_general_assembly_bus_guide( $content ) {
	if ( ! is_singular( 'events' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( 'general-assembly-20261113' !== get_post_field( 'post_name', get_the_ID() ) ) {
		return $content;
	}

	if ( has_shortcode( $content, 'yeg_bus_guide' ) ) {
		return $content;
	}

	return $content . do_shortcode( '[yeg_bus_guide page="01"]' );
}
add_filter( 'the_content', 'yeg_append_general_assembly_bus_guide' );
