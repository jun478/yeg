<?php
require get_stylesheet_directory() . '/functions/events/event-single-links.php';
require get_stylesheet_directory() . '/functions/events/event-online-stream.php';
require get_stylesheet_directory() . '/functions/events/event-hero-title.php';
require get_stylesheet_directory() . '/functions/events/events-archive-calender.php';
require get_stylesheet_directory() . '/functions/events/events-archive-day.php';
require get_stylesheet_directory() . '/functions/events/events-category.php';
require get_stylesheet_directory() . '/functions/facility/facility-events.php';
require get_stylesheet_directory() . '/functions/front-page/header-slider.php';
require get_stylesheet_directory() . '/functions/front-page/top-feature-sections.php';
require get_stylesheet_directory() . '/functions/front-page/top-schedule.php';
require get_stylesheet_directory() . '/functions/header/schedule-megamenu.php';
require get_stylesheet_directory() . '/functions/transport/bus-guide.php';
require get_stylesheet_directory() . '/functions/transport/bus-overview.php';

// 親テーマのヘッダースライダーのフックを解除
function remove_parent_header_slider() {
	remove_action( 'wp', 'header_slisder_wp' );
}
add_action( 'init', 'remove_parent_header_slider' );

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function theme_enqueue_styles() {
	// 親テーマのスタイルを読み込み
	wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

	// 子テーマのスタイルを親の後に読み込み
	wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'), filemtime(get_stylesheet_directory() . '/style.css'));
}

//下記オリジナル


function top_gaiyou_shortcode() {
	ob_start();
	get_template_part('parts/top/gaiyou');
	return ob_get_clean();
}

add_shortcode('top_gaiyou_shortcode', 'top_gaiyou_shortcode');

/**
 * トップの協賛
 * 親テーマの、gaia_tcd102/functions/front-page/main-contents.phpに直接記述。。。オーバライドめんど！
 * @return type
 */
function create_swiper_sponsor_slider() {
	// 1. 協賛企業データの定義
	$sponsors = [
//			['name' => 'SAVEホールディングス株式会社', 'logo' => get_stylesheet_directory_uri() . '/img/sponsors/slider-save-holdings.png', 'url' => 'https://www.saveclean.com/company/company-profile.php'],
//			['name' => '有限会社あさひ冷凍食品', 'logo' => get_stylesheet_directory_uri() . '/img/sponsors/slider-asahi-reito.jpg', 'url' => 'https://asahi-ff.com/'],
	];

	// SwiperのCSS/JSをCDNから読み込み
	wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
	wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);

	ob_start();
	?>
	<style>
		.sponsor-outer {
			padding: 40px 0;
			max-width: 1200px;
			margin: 0 auto;
		}
		.sponsor-featured {
			display: grid;
			grid-template-columns: repeat(2, minmax(0, 1fr));
			gap: 24px;
			margin: 32px 0;
		}
		.sponsor-featured__item {
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0;
			min-width: 0;
		}
		.sponsor-featured__item--compact > a {
			display: block;
			width: 70%;
		}
		.sponsor-featured__image {
			display: block;
			width: 100%;
			height: auto;
			object-fit: contain;
			transition: opacity 0.2s ease;
		}
		.sponsor-featured__image:hover,
		.sponsor-featured__item a:focus-visible .sponsor-featured__image {
			opacity: 0.8;
		}
		.swiper-container-sponsor {
			width: 100%;
			overflow: hidden;
			position: relative;
		}
		/* 滑らかな動き（Linear）のための設定 */
		.swiper-wrapper {
			transition-timing-function: linear !important;
		}
		.swiper-slide {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 120px; /* ロゴの高さに合わせて調整 */
		}
		.swiper-container-sponsor .swiper-slide img {
			max-width: 100%;
			max-height: 80px;
			object-fit: contain;
			filter: grayscale(100%);
			opacity: 0.7;
			transition: 0.3s;
		}
		.swiper-container-sponsor .swiper-slide img:hover {
			filter: grayscale(0%);
			opacity: 1;
		}
		@media (max-width: 767px) {
			.sponsor-featured {
				grid-template-columns: minmax(0, 1fr);
				gap: 16px;
				margin: 24px 0;
			}
		}

	</style>

	<div class="post_content u-clearfix">
		<div class="p-fp-section__header yeg_top_gaiyou">

			<div class="sponsor-outer">
				<h2>協賛企業</h2>
				<p>本大会のテーマ「千年先の未来へ」と、困難に立ち向かう「アララガマ魂」にご賛同し<br>次代の地域を担うリーダーたちの挑戦を熱くご支援くださる協賛企業の皆様をご紹介します。<br>多大なるご協力に心より感謝申し上げます。</p>
				<div class="sponsor-featured">
					<figure class="sponsor-featured__item">
						<a href="<?php echo esc_url('https://www.karada-genki.com/'); ?>" target="_blank" rel="noopener noreferrer">
							<img class="sponsor-featured__image" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/img/sponsors/featured-karada-genki.jpg'); ?>" alt="からだ元気治療院" loading="lazy" decoding="async">
						</a>
					</figure>
					<figure class="sponsor-featured__item">
						<a href="<?php echo esc_url('https://www.milliongroup-japan.com/'); ?>" target="_blank" rel="noopener noreferrer">
							<img class="sponsor-featured__image" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/img/sponsors/featured-million-group.png'); ?>" alt="MILLION GROUP" loading="lazy" decoding="async">
						</a>
					</figure>
					<figure class="sponsor-featured__item sponsor-featured__item--compact">
						<a href="<?php echo esc_url('https://asahi-ff.com/'); ?>" target="_blank" rel="noopener noreferrer">
							<img class="sponsor-featured__image" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/img/sponsors/featured-asahi-reito.png'); ?>" alt="有限会社あさひ冷凍食品" loading="lazy" decoding="async">
						</a>
					</figure>
					<figure class="sponsor-featured__item sponsor-featured__item--compact">
						<a href="<?php echo esc_url('https://www.saveclean.com/company/company-profile.php'); ?>" target="_blank" rel="noopener noreferrer">
							<img class="sponsor-featured__image" src="<?php echo esc_url(get_stylesheet_directory_uri() . '/img/sponsors/featured-save-holdings.png'); ?>" alt="SAVEホールディングス株式会社" loading="lazy" decoding="async">
						</a>
					</figure>
				</div>
				<div class="swiper swiper-container-sponsor">
					<div class="swiper-wrapper">
						<?php foreach ($sponsors as $s) { ?>
							<div class="swiper-slide">
							<a href="<?php echo esc_url($s['url']); ?>" target="_blank" rel="noopener noreferrer">
									<img src="<?php echo esc_url($s['logo']); ?>" alt="<?php echo esc_attr($s['name']); ?>">
								</a>
							</div>
						<?php } ?>
					</div>
				</div>

<!--				<div class="p-fp-section__button">-->
<!--					<a class="p-fp-section__button-link c-button" href="/sponsors/">-->
<!--						協賛企業の一覧を見る-->
<!--					</a>-->
<!--				</div>-->
			</div>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const sponsorCount = <?php echo (int) count($sponsors); ?>;
			const sponsorSlidesPerView = function (maxSlidesPerView) {
				return Math.min(maxSlidesPerView, Math.max(1, sponsorCount - 1));
			};
			const swiper = new Swiper('.swiper-container-sponsor', {
				loop: sponsorCount > 1, // 無限ループ
				speed: 8000, // 5秒かけてゆっくり移動
				autoplay: {
					delay: 0, // 待機時間なし
					disableOnInteraction: false,
				},
				allowTouchMove: false, // マウスで止められないようにする（滑らかさ優先）
				slidesPerView: sponsorSlidesPerView(2), // スマホ初期値
				spaceBetween: 30,
				breakpoints: {
					480: {slidesPerView: sponsorSlidesPerView(3)},
					768: {slidesPerView: sponsorSlidesPerView(4)},
					1024: {slidesPerView: sponsorSlidesPerView(5)} // PCでは最大5件表示
				}
			});
		});
	</script>
	<?php
	return ob_get_clean();
}

add_shortcode('sponsor_swiper', 'create_swiper_sponsor_slider');
