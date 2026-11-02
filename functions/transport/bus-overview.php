<?php
/**
 * Participant-facing shuttle bus overview for the dedicated bus page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return one route from the shared bus guide registry.
 *
 * @param array  $registry     Bus guide registry.
 * @param string $page_number Registry page number.
 * @param string $route_number Route number.
 * @return array|null
 */
function yeg_bus_overview_get_route( $registry, $page_number, $route_number ) {
	if ( empty( $registry[ $page_number ]['routes'] ) || ! is_array( $registry[ $page_number ]['routes'] ) ) {
		return null;
	}

	foreach ( $registry[ $page_number ]['routes'] as $route ) {
		if ( isset( $route['number'] ) && (string) $route_number === (string) $route['number'] ) {
			return $route;
		}
	}

	return null;
}

/**
 * Return an HTML time element for a display time.
 *
 * @param string $time Display time.
 * @return string
 */
function yeg_bus_overview_time( $time ) {
	$datetime = str_pad( (string) $time, 5, '0', STR_PAD_LEFT );

	return '<time datetime="' . esc_attr( $datetime ) . '">' . esc_html( $time ) . '</time>';
}

/**
 * Return the first-to-last time range from route rows.
 *
 * @param array  $times Route time rows.
 * @param string $key   Time field.
 * @return string
 */
function yeg_bus_overview_time_range( $times, $key ) {
	$values = array_values(
		array_filter(
			array_map(
				function ( $time ) use ( $key ) {
					return isset( $time[ $key ] ) ? (string) $time[ $key ] : '';
				},
				$times
			)
		)
	);

	if ( empty( $values ) ) {
		return '';
	}

	$first = reset( $values );
	$last  = end( $values );

	if ( $first === $last ) {
		return yeg_bus_overview_time( $first );
	}

	return yeg_bus_overview_time( $first ) . '<span aria-hidden="true">〜</span>' . yeg_bus_overview_time( $last );
}

/**
 * Render event and facility links used by a transport card.
 *
 * @param array  $events        Event link definitions.
 * @param string $facility_slug Facility slug.
 * @return string
 */
function yeg_bus_overview_card_links( $events, $facility_slug = '' ) {
	if ( empty( $events ) && '' === $facility_slug ) {
		return '';
	}

	ob_start();
	?>
	<div class="yeg-bus-overview__card-links">
		<?php foreach ( $events as $event ) : ?>
			<a class="yeg-bus-overview__card-link" href="<?php echo esc_url( home_url( '/events/' . $event['slug'] . '/' ) ); ?>">
				<?php echo esc_html( $event['label'] ); ?>
			</a>
		<?php endforeach; ?>
		<?php if ( '' !== $facility_slug ) : ?>
			<a class="yeg-bus-overview__card-link yeg-bus-overview__card-link--map" href="<?php echo esc_url( home_url( '/facility/' . $facility_slug . '/' ) ); ?>">
				<?php esc_html_e( '会場マップ', 'gaia_tcd102_child' ); ?>
			</a>
		<?php endif; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render a card backed by a route in the shared registry.
 *
 * @param array $registry Bus guide registry.
 * @param array $card     Card definition.
 * @return string
 */
function yeg_bus_overview_route_card( $registry, $card ) {
	$route = yeg_bus_overview_get_route( $registry, $card['page'], $card['route'] );
	if ( ! $route || empty( $route['times'] ) ) {
		return '';
	}

	$guide       = $registry[ $card['page'] ];
	$status      = isset( $card['status'] ) ? $card['status'] : 'bus';
	$status_text = 'walk' === $status ? '徒歩移動' : 'バスあり';
	$destination = ! empty( $route['destination_name'] ) ? $route['destination_name'] : $guide['destination'];
	$title_id    = wp_unique_id( 'yeg-bus-overview-card-' );
	$show_detail = 'walk' !== $status && ( ! isset( $card['show_detail'] ) || $card['show_detail'] );

	ob_start();
	?>
	<article class="yeg-bus-overview__card yeg-bus-overview__card--<?php echo esc_attr( sanitize_html_class( $status ) ); ?>" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<header class="yeg-bus-overview__card-header">
			<span class="yeg-bus-overview__status"><?php echo esc_html( $status_text ); ?></span>
			<p class="yeg-bus-overview__departure-time">
				<span><?php esc_html_e( '出発', 'gaia_tcd102_child' ); ?></span>
				<strong><?php echo yeg_bus_overview_time_range( $route['times'], 'departure' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
			</p>
		</header>

		<h4 id="<?php echo esc_attr( $title_id ); ?>" class="yeg-bus-overview__card-title"><?php echo esc_html( $card['title'] ); ?></h4>

		<div class="yeg-bus-overview__route" aria-label="<?php echo esc_attr( $route['departure_name'] . 'から' . $destination ); ?>">
			<div>
				<span class="yeg-bus-overview__point-label"><?php esc_html_e( '出発地', 'gaia_tcd102_child' ); ?></span>
				<strong><?php echo esc_html( $route['departure_name'] ); ?></strong>
			</div>
			<span class="yeg-bus-overview__route-arrow" aria-hidden="true">→</span>
			<div>
				<span class="yeg-bus-overview__point-label"><?php esc_html_e( '目的地', 'gaia_tcd102_child' ); ?></span>
				<strong><?php echo esc_html( $destination ); ?></strong>
			</div>
		</div>

		<p class="yeg-bus-overview__arrival-time">
			<span><?php esc_html_e( '到着予定', 'gaia_tcd102_child' ); ?></span>
			<strong><?php echo yeg_bus_overview_time_range( $route['times'], 'arrival' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
		</p>

		<?php if ( ! empty( $card['note'] ) ) : ?>
			<p class="yeg-bus-overview__card-note"><?php echo esc_html( $card['note'] ); ?></p>
		<?php endif; ?>

		<?php if ( $show_detail ) : ?>
			<details class="yeg-bus-overview__details">
				<summary><?php esc_html_e( '号車別の運行時刻', 'gaia_tcd102_child' ); ?></summary>
				<ul class="yeg-bus-overview__times">
					<?php foreach ( $route['times'] as $time ) : ?>
						<li>
							<span class="yeg-bus-overview__bus-number">
								<?php if ( ! empty( $time['service'] ) ) : ?>
									<small><?php echo esc_html( $time['service'] ); ?></small>
								<?php endif; ?>
								<strong><?php echo esc_html( $time['buses'] ); ?></strong>
							</span>
							<span class="yeg-bus-overview__time-pair">
								<?php echo yeg_bus_overview_time( $time['departure'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span aria-hidden="true">→</span>
								<?php echo yeg_bus_overview_time( $time['arrival'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</details>
		<?php endif; ?>

		<?php echo yeg_bus_overview_card_links( $card['events'], $card['facility'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Render a custom walking or information card.
 *
 * @param array $card Card definition.
 * @return string
 */
function yeg_bus_overview_custom_card( $card ) {
	$status   = isset( $card['status'] ) ? $card['status'] : 'walk';
	$title_id = wp_unique_id( 'yeg-bus-overview-card-' );

	ob_start();
	?>
	<article class="yeg-bus-overview__card yeg-bus-overview__card--<?php echo esc_attr( sanitize_html_class( $status ) ); ?>" aria-labelledby="<?php echo esc_attr( $title_id ); ?>">
		<header class="yeg-bus-overview__card-header">
			<span class="yeg-bus-overview__status"><?php echo esc_html( $card['status_label'] ); ?></span>
			<?php if ( ! empty( $card['time_label'] ) ) : ?>
				<p class="yeg-bus-overview__departure-time yeg-bus-overview__departure-time--text"><strong><?php echo esc_html( $card['time_label'] ); ?></strong></p>
			<?php endif; ?>
		</header>

		<h4 id="<?php echo esc_attr( $title_id ); ?>" class="yeg-bus-overview__card-title"><?php echo esc_html( $card['title'] ); ?></h4>

		<?php if ( ! empty( $card['departure'] ) && ! empty( $card['destination'] ) ) : ?>
			<div class="yeg-bus-overview__route" aria-label="<?php echo esc_attr( $card['departure'] . 'から' . $card['destination'] ); ?>">
				<div>
					<span class="yeg-bus-overview__point-label"><?php esc_html_e( '出発地', 'gaia_tcd102_child' ); ?></span>
					<strong><?php echo esc_html( $card['departure'] ); ?></strong>
				</div>
				<span class="yeg-bus-overview__route-arrow" aria-hidden="true">→</span>
				<div>
					<span class="yeg-bus-overview__point-label"><?php esc_html_e( '目的地', 'gaia_tcd102_child' ); ?></span>
					<strong><?php echo esc_html( $card['destination'] ); ?></strong>
				</div>
			</div>
		<?php endif; ?>

		<p class="yeg-bus-overview__card-note"><?php echo esc_html( $card['text'] ); ?></p>
		<?php echo yeg_bus_overview_card_links( $card['events'], $card['facility'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</article>
	<?php
	return ob_get_clean();
}

/**
 * Render one group of route cards.
 *
 * @param array $registry Bus guide registry.
 * @param array $cards    Card definitions.
 */
function yeg_bus_overview_card_grid( $registry, $cards ) {
	?>
	<div class="yeg-bus-overview__grid">
		<?php foreach ( $cards as $card ) : ?>
			<?php echo yeg_bus_overview_route_card( $registry, $card ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Render the full shuttle bus overview.
 *
 * @return string
 */
function yeg_bus_overview_shortcode() {
	if ( ! function_exists( 'yeg_get_bus_guide_registry' ) ) {
		return '';
	}

	$registry = yeg_get_bus_guide_registry();
	$day1_morning = array(
		array( 'page' => '01', 'route' => '01', 'title' => '会員総会（宮古島市公設市場前）', 'events' => array( array( 'label' => '会員総会', 'slug' => 'general-assembly-20261113' ) ), 'facility' => 'jta-dome-miyakojima' ),
		array( 'page' => '01', 'route' => '02', 'title' => '会員総会（旧宮古島市役所前）', 'events' => array( array( 'label' => '会員総会', 'slug' => 'general-assembly-20261113' ) ), 'facility' => 'jta-dome-miyakojima' ),
		array( 'page' => '01', 'route' => '03', 'title' => '会員総会（パイナガマビーチ）', 'events' => array( array( 'label' => '会員総会', 'slug' => 'general-assembly-20261113' ) ), 'facility' => 'jta-dome-miyakojima' ),
		array( 'page' => '02', 'route' => '01', 'title' => '開会式（宮古島市公設市場前）', 'events' => array( array( 'label' => '開会式', 'slug' => 'opening-ceremony-20261113' ) ), 'facility' => 'jta-dome-miyakojima' ),
		array( 'page' => '02', 'route' => '02', 'title' => '開会式（旧宮古島市役所前）', 'events' => array( array( 'label' => '開会式', 'slug' => 'opening-ceremony-20261113' ) ), 'facility' => 'jta-dome-miyakojima' ),
		array( 'page' => '02', 'route' => '03', 'title' => '開会式（パイナガマビーチ）', 'events' => array( array( 'label' => '開会式', 'slug' => 'opening-ceremony-20261113' ) ), 'facility' => 'jta-dome-miyakojima' ),
	);
	$day1_afternoon = array(
		array( 'page' => '04', 'route' => '01', 'title' => '事務局研修', 'events' => array( array( 'label' => '事務局研修', 'slug' => 'secretariat-training-20261113' ) ), 'facility' => 'hotel-atorremerald-miyakojima' ),
		array( 'page' => '05', 'route' => '01', 'title' => 'OB・OG研修／会員増強研究会', 'events' => array( array( 'label' => 'OB・OG研修', 'slug' => 'ob-og-program-20261113' ), array( 'label' => '会員増強研究会', 'slug' => 'day1-training-c-member-growth-20261113' ) ), 'facility' => 'yard-miyakojima' ),
		array( 'page' => '06', 'route' => '01', 'title' => '広報DX研究会／YEG経済圏構築研究会', 'events' => array( array( 'label' => '広報DX研究会', 'slug' => 'day1-training-b-pr-dx-20261113' ), array( 'label' => 'YEG経済圏構築研究会', 'slug' => 'day1-training-f-economic-zone-20261113' ) ), 'facility' => 'miyakojima-future-creation-center' ),
		array( 'page' => '07', 'route' => '01', 'title' => '政策提言研究会', 'events' => array( array( 'label' => '政策提言研究会', 'slug' => 'day1-training-e-policy-20261113' ) ), 'facility' => 'hotel-shigira-mirage' ),
		array( 'page' => '08', 'route' => '01', 'title' => 'ビジョン策定研究会', 'events' => array( array( 'label' => 'ビジョン策定研究会', 'slug' => 'day1-training-d-vision-20261113' ) ), 'facility' => 'hotel-shigira-mirage' ),
		array( 'page' => '08', 'route' => '02', 'title' => '国際ビジネス研究会', 'events' => array( array( 'label' => '国際ビジネス研究会', 'slug' => 'day1-training-i-international-business-20261113' ) ), 'facility' => 'hotel-breezebay-marina' ),
		array( 'page' => '08', 'route' => '03', 'title' => 'YEGビジネス共創研究会', 'events' => array( array( 'label' => 'YEGビジネス共創研究会', 'slug' => 'day1-training-g-business-co-creation-20261113' ) ), 'facility' => 'shigira-seven-miles-resort-shimauta' ),
		array( 'page' => '09', 'route' => '01', 'title' => 'スタートアップ支援研究会', 'events' => array( array( 'label' => 'スタートアップ支援研究会', 'slug' => 'day1-training-h-startup-20261113' ) ), 'facility' => 'hotel-atorremerald-miyakojima' ),
		array( 'page' => '10', 'route' => '01', 'title' => '安里繁信氏講演', 'events' => array( array( 'label' => '初日研修A', 'slug' => 'day1-training-a-keynote-20261113' ) ), 'facility' => 'matida-civic-theater' ),
	);
	$day2_routes = array(
		array( 'page' => '11', 'route' => '01', 'title' => '次年度会長研修', 'events' => array( array( 'label' => '次年度会長研修', 'slug' => 'next-president-training-20261114' ) ), 'facility' => 'jta-dome-miyakojima' ),
		array( 'page' => '15', 'route' => '01', 'title' => '分科会D「潮風と考える」', 'events' => array( array( 'label' => '分科会D', 'slug' => 'breakout-d-20261114' ) ), 'facility' => 'hilton-okinawa-miyako-island-resort' ),
		array( 'page' => '15', 'route' => '02', 'title' => '分科会E「ざわわに育む」', 'events' => array( array( 'label' => '分科会E', 'slug' => 'breakout-e-20261114' ) ), 'facility' => 'alternative-farm-miyakojima' ),
		array( 'page' => '15', 'route' => '03', 'title' => '分科会I「千年先を描く」', 'events' => array( array( 'label' => '分科会I', 'slug' => 'breakout-i-20261114' ) ), 'facility' => 'underground-dam-museum-fukuzato-dam' ),
		array( 'page' => '16', 'route' => '01', 'title' => '分科会F「島から教わる」', 'events' => array( array( 'label' => '分科会F', 'slug' => 'breakout-f-20261114' ) ), 'facility' => 'yukishio-museum-karimata' ),
		array( 'page' => '16', 'route' => '02', 'title' => '分科会G「未来を切り拓く」', 'status' => 'walk', 'show_detail' => false, 'note' => '人頭税石へ各自徒歩で移動してください。', 'events' => array( array( 'label' => '分科会G', 'slug' => 'breakout-g-20261114' ) ), 'facility' => 'hirara-city-area' ),
		array( 'page' => '16', 'route' => '03', 'title' => '分科会H「地域価値を読む」', 'events' => array( array( 'label' => '分科会H', 'slug' => 'breakout-h-20261114' ) ), 'facility' => 'rosewood-miyakojima' ),
	);
	$notice_cards = array(
		array( 'page' => '12', 'title' => '副会長研修', 'events' => array( array( 'label' => '副会長研修', 'slug' => 'vice-president-training-20261114' ) ), 'facility' => 'hotel-atorremerald-miyakojima' ),
		array( 'page' => '13', 'title' => '専務理事研修', 'events' => array( array( 'label' => '専務理事研修', 'slug' => 'executive-director-training-20261114' ) ), 'facility' => 'miyakojima-future-creation-center' ),
		array( 'page' => '14', 'title' => '日本YEG三大事業に挑戦しよう', 'events' => array( array( 'label' => '日本YEG三大事業に挑戦しよう', 'slug' => 'three-major-projects-training-20261114' ) ), 'facility' => 'hirara-port-marine-terminal' ),
		array( 'page' => '17', 'title' => '分科会J「人間力を磨く」', 'events' => array( array( 'label' => '分科会J', 'slug' => 'breakout-j-20261114' ) ), 'facility' => 'matida-civic-theater' ),
	);
	$boarding_points = array(
		array(
			'number' => '01',
			'name'   => '宮古島市公設市場前',
			'query'  => '宮古島市公設市場',
			'url'    => 'https://www.google.com/maps/place/%E5%85%AC%E8%A8%AD%E5%B8%82%E5%A0%B4%E5%89%8D/@24.8039798,125.2787759,417m/data=!3m1!1e3!4m14!1m7!3m6!1s0x34f4538b127c2599:0x19097169989fdfed!2z5a6u5Y-k5bO25biC5YWs6Kit5biC5aC0!8m2!3d24.8039621!4d125.2787251!16s%2Fg%2F11b86xgq9w!3m5!1s0x34f4538b729fee33:0xb52c1be6809fd634!8m2!3d24.8043094!4d125.2787354!16s%2Fg%2F11hcjtr59y?entry=ttu&g_ep=EgoyMDI2MTAyOC4wIKXMDSoASAFQAw%3D%3D',
		),
		array(
			'number' => '02',
			'name'   => '旧宮古島市役所前',
			'query'  => '旧宮古島市役所',
			'url'    => 'https://www.google.com/maps/place/%E5%B8%82%E5%BD%B9%E6%89%80%E5%89%8D%EF%BC%88%E5%AE%AE%E5%8F%A4%E5%B3%B6%E5%B8%82%EF%BC%89%EF%BC%88%E3%83%90%E3%82%B9%EF%BC%89%EF%BC%88%E5%AE%AE%E5%8F%A4%E7%A9%BA%E6%B8%AF%E7%B7%9A%E3%83%BB%E4%B8%8B%E3%82%8A%EF%BC%89/@24.8055312,125.2808549,209m/data=!3m2!1e3!4b1!4m6!3m5!1s0x34f453f3457037e9:0xa8d9742447beec8e!8m2!3d24.80553!4d125.2815!16s%2Fg%2F11hcjtrqsp?entry=ttu&g_ep=EgoyMDI2MTAyOC4wIKXMDSoASAFQAw%3D%3D',
		),
		array(
			'number' => '03',
			'name'   => 'パイナガマビーチ',
			'query'  => 'パイナガマビーチ',
			'url'    => 'https://www.google.com/maps/place/%E3%83%91%E3%82%A4%E3%83%8A%E3%82%AC%E3%83%9E%E3%83%93%E3%83%BC%E3%83%81/@24.8017186,125.2705291,79m/data=!3m1!1e3!4m6!3m5!1s0x34f45477e5fe593d:0x8632c1cb7271d247!8m2!3d24.8017005!4d125.2705867!16s%2Fg%2F11h12t6r_z?entry=ttu&g_ep=EgoyMDI2MTAyOC4wIKXMDSoASAFQAw%3D%3D',
		),
	);

	ob_start();
	?>
	<div class="yeg-bus-overview">
		<section class="yeg-bus-overview__hero" aria-labelledby="yeg-bus-overview-intro">
			<p class="yeg-bus-overview__eyebrow">第44回 全国リーダーズ研修会</p>
			<h2 id="yeg-bus-overview-intro" class="no-toc">参加プログラムから交通案内を確認</h2>
			<p>会場へ向かうバスを中心に、乗車場所と出発時刻を日付別にまとめています。</p>
			<nav class="yeg-bus-overview__date-nav" aria-label="運行日を選ぶ">
				<a class="yeg-bus-overview__date-nav-link yeg-bus-overview__date-nav-link--day1" href="#yeg-bus-day1"><span>DAY 1</span>11月13日（金）</a>
				<a class="yeg-bus-overview__date-nav-link yeg-bus-overview__date-nav-link--day2" href="#yeg-bus-day2"><span>DAY 2</span>11月14日（土）</a>
			</nav>
		</section>

		<div class="yeg-bus-overview__alert" role="note">
			<strong>ご利用前にご確認ください</strong>
			<p>交通状況により時刻が前後する場合があります。時間に余裕をもって乗車場所へお越しください。現地集合や徒歩移動のプログラムもあります。</p>
		</div>

		<section class="yeg-bus-overview__section yeg-bus-overview__section--boarding" aria-labelledby="yeg-bus-boarding-title">
			<div class="yeg-bus-overview__section-heading">
				<p>BOARDING POINTS</p>
				<h2 id="yeg-bus-boarding-title" class="no-toc">共通乗車場所</h2>
			</div>
			<div class="yeg-bus-overview__boarding-grid">
				<?php foreach ( $boarding_points as $point ) : ?>
					<?php $map_url = isset( $point['url'] ) ? $point['url'] : add_query_arg( array( 'api' => '1', 'query' => $point['query'] ), 'https://www.google.com/maps/search/' ); ?>
					<article class="yeg-bus-overview__boarding-card">
						<div class="yeg-bus-overview__boarding-heading">
							<span class="yeg-bus-overview__boarding-icon" aria-hidden="true">
								<svg viewBox="0 0 24 24" focusable="false">
									<rect x="4" y="3" width="16" height="16" rx="3"></rect>
									<path d="M4 10h16M7 19v2M17 19v2"></path>
									<circle cx="8" cy="15" r="1"></circle>
									<circle cx="16" cy="15" r="1"></circle>
								</svg>
							</span>
							<h3><?php echo esc_html( $point['name'] ); ?></h3>
						</div>
						<a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener noreferrer">Googleマップを見る</a>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="yeg-bus-overview__hub-note"><strong>JTAドーム</strong>は、大会中の乗換地点および午後の各研修会場行きバスの出発拠点です。</p>
		</section>

		<section id="yeg-bus-day1" class="yeg-bus-overview__day yeg-bus-overview__day--day1" aria-labelledby="yeg-bus-day1-title">
			<header class="yeg-bus-overview__day-header">
				<p>DAY 1</p>
				<h2 id="yeg-bus-day1-title" class="no-toc">11月13日（金）</h2>
			</header>

			<section class="yeg-bus-overview__group" aria-labelledby="yeg-bus-day1-morning">
				<h3 id="yeg-bus-day1-morning">午前｜JTAドームへ</h3>
				<?php yeg_bus_overview_card_grid( $registry, $day1_morning ); ?>

				<article class="yeg-bus-overview__loop-card" aria-labelledby="yeg-bus-loop-title">
					<div class="yeg-bus-overview__loop-header">
						<span class="yeg-bus-overview__status">巡回バス</span>
						<p><strong><?php echo yeg_bus_overview_time( '10:30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>〜<?php echo yeg_bus_overview_time( '13:30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>／20分間隔</p>
					</div>
					<h4 id="yeg-bus-loop-title">市内巡回バス</h4>
					<p class="yeg-bus-overview__loop-route">パイナガマビーチ <span aria-hidden="true">→</span> 旧宮古島市役所 <span aria-hidden="true">→</span> 宮古島市公設市場 <span aria-hidden="true">→</span> JTAドーム</p>
					<ul class="yeg-bus-overview__loop-stops">
						<li><strong>パイナガマビーチ発</strong><span><?php echo yeg_bus_overview_time( '10:30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>〜<?php echo yeg_bus_overview_time( '13:10' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li>
						<li><strong>旧宮古島市役所発</strong><span><?php echo yeg_bus_overview_time( '10:40' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>〜<?php echo yeg_bus_overview_time( '13:20' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li>
						<li><strong>宮古島市公設市場発</strong><span><?php echo yeg_bus_overview_time( '10:50' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>〜<?php echo yeg_bus_overview_time( '13:30' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span></li>
					</ul>
					<?php echo yeg_bus_overview_card_links( array(), 'jta-dome-miyakojima' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</article>
			</section>

			<section class="yeg-bus-overview__group" aria-labelledby="yeg-bus-day1-afternoon">
				<h3 id="yeg-bus-day1-afternoon">午後｜JTAドームから各研修会場へ</h3>
				<?php yeg_bus_overview_card_grid( $registry, $day1_afternoon ); ?>
			</section>

			<section class="yeg-bus-overview__group" aria-labelledby="yeg-bus-day1-after">
				<h3 id="yeg-bus-day1-after">研修終了後の移動</h3>
				<div class="yeg-bus-overview__grid">
					<?php
					echo yeg_bus_overview_route_card(
						$registry,
						array(
							'page'     => '03',
							'route'    => '01',
							'title'    => '当年度会長研修／国際交流研究会',
							'events'   => array(
								array( 'label' => '当年度会長研修', 'slug' => 'current-president-training-20261113' ),
								array( 'label' => '国際交流研究会', 'slug' => 'day1-training-j-international-exchange-20261113' ),
							),
							'facility' => 'turiba-seaside-park',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo yeg_bus_overview_custom_card(
						array(
							'status'       => 'walk',
							'status_label' => '徒歩移動',
							'time_label'   => '研修終了後',
							'title'        => '事務局研修',
							'departure'    => 'ホテルアトールエメラルド宮古島',
							'destination'  => '漲水シンボル緑地',
							'text'         => $registry['04']['follow_up']['text'],
							'events'       => array( array( 'label' => '事務局研修', 'slug' => 'secretariat-training-20261113' ) ),
							'facility'     => 'turiba-seaside-park',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo yeg_bus_overview_custom_card(
						array(
							'status'       => 'walk',
							'status_label' => '徒歩移動',
							'time_label'   => '研修終了後',
							'title'        => 'スタートアップ支援研究会',
							'departure'    => 'ホテルアトールエメラルド宮古島',
							'destination'  => '漲水シンボル緑地',
							'text'         => $registry['09']['follow_up']['text'],
							'events'       => array( array( 'label' => 'スタートアップ支援研究会', 'slug' => 'day1-training-h-startup-20261113' ) ),
							'facility'     => 'turiba-seaside-park',
						)
					); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			</section>
		</section>

		<section id="yeg-bus-day2" class="yeg-bus-overview__day yeg-bus-overview__day--day2" aria-labelledby="yeg-bus-day2-title">
			<header class="yeg-bus-overview__day-header">
				<p>DAY 2</p>
				<h2 id="yeg-bus-day2-title" class="no-toc">11月14日（土）</h2>
			</header>

			<section class="yeg-bus-overview__group" aria-labelledby="yeg-bus-day2-routes">
				<h3 id="yeg-bus-day2-routes">朝｜各会場へ</h3>
				<?php yeg_bus_overview_card_grid( $registry, $day2_routes ); ?>
			</section>

			<section class="yeg-bus-overview__group" aria-labelledby="yeg-bus-day2-local">
				<h3 id="yeg-bus-day2-local">現地集合の研修・分科会</h3>
				<div class="yeg-bus-overview__grid">
					<?php foreach ( $notice_cards as $notice_card ) : ?>
						<?php
						echo yeg_bus_overview_custom_card(
							array(
								'status'       => 'local',
								'status_label' => '現地集合',
								'time_label'   => '行きのバスなし',
								'title'        => $notice_card['title'],
								'text'         => isset( $registry[ $notice_card['page'] ]['notice'] ) ? $registry[ $notice_card['page'] ]['notice'] : '',
								'events'       => $notice_card['events'],
								'facility'     => $notice_card['facility'],
							)
						); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					<?php endforeach; ?>
				</div>
			</section>
		</section>

		<footer class="yeg-bus-overview__footer-note">
			<h2 class="no-toc">研修・分科会終了後の移動について</h2>
			<p>研修終了後のJTAドーム行きバスは、乗車場所や出発時刻を現地スタッフへご確認ください。各イベントページでは、運行資料の画像も確認できます。</p>
			<div class="yeg-bus-overview__footer-links">
				<a href="<?php echo esc_url( home_url( '/events/?calender=2026-11' ) ); ?>">プログラム一覧を見る</a>
				<a href="<?php echo esc_url( home_url( '/facility/' ) ); ?>">会場案内を見る</a>
			</div>
		</footer>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'yeg_bus_overview', 'yeg_bus_overview_shortcode' );

/**
 * Replace only the dedicated bus page body with the current overview.
 *
 * @param string $content Page content.
 * @return string
 */
function yeg_replace_bus_page_content( $content ) {
	if ( ! is_page( 'bus' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	return yeg_bus_overview_shortcode();
}
add_filter( 'the_content', 'yeg_replace_bus_page_content', 99 );

/**
 * Disable the classic-editor TOC on the replaced bus page only.
 *
 * @param array $options TCD table-of-contents options.
 * @return array
 */
function yeg_disable_bus_page_toc( $options ) {
	if ( is_page( 'bus' ) ) {
		$options['display'] = 0;
	}

	return $options;
}
add_filter( 'tcdce_toc_default_options', 'yeg_disable_bus_page_toc' );
