<?php
require get_stylesheet_directory() . '/functions/events/events-archive-calender.php';
require get_stylesheet_directory() . '/functions/front-page/header-slider.php';

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
	wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style'));
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
	// 1. 協賛企業データの定義（20社分など、ここに追加）
	$sponsors = [
			['name' => '企業A', 'logo' => 'https://yeg.miyacojima.net/wp-content/uploads/2026/03/スクリーンショット-2026-03-24-1.41.02.png', 'url' => '/sponsor-a/'],
			['name' => '企業B', 'logo' => 'https://yeg.miyacojima.net/wp-content/uploads/2026/03/スクリーンショット-2026-03-24-1.40.18.png', 'url' => '/sponsor-b/'],
			['name' => '企業C', 'logo' => 'https://yeg.miyacojima.net/wp-content/uploads/2026/03/スクリーンショット-2026-03-24-1.40.27.png', 'url' => '/sponsor-c/'],
			['name' => '企業D', 'logo' => 'https://yeg.miyacojima.net/wp-content/uploads/2026/03/スクリーンショット-2026-03-24-1.40.40.png', 'url' => '/sponsor-d/'],
			['name' => '企業E', 'logo' => 'https://yeg.miyacojima.net/wp-content/uploads/2026/03/スクリーンショット-2026-03-24-1.40.53.png', 'url' => '/sponsor-e/'],
			['name' => '企業F', 'logo' => 'https://yeg.miyacojima.net/wp-content/uploads/2026/03/スクリーンショット-2026-03-24-1.42.43.png', 'url' => '/sponsor-f/'],
					// 実際には20社分ほどデータを並べてください
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
		.swiper-slide img {
			max-width: 100%;
			max-height: 100px;
			object-fit: contain;
			filter: grayscale(100%);
			opacity: 0.7;
			transition: 0.3s;
		}
		.swiper-slide img:hover {
			filter: grayscale(0%);
			opacity: 1;
		}

	</style>

	<div class="post_content u-clearfix">
		<div class="p-fp-section__header yeg_top_gaiyou">

			<div class="sponsor-outer">
				<h2>協賛企業</h2>
				<p>本大会のテーマ「千年先の未来へ」と、困難に立ち向かう「アララガマ魂」にご賛同し<br>次代の地域を担うリーダーたちの挑戦を熱くご支援くださる協賛企業の皆様をご紹介します。<br>多大なるご協力に心より感謝申し上げます。</p>
<!--				<div class="swiper swiper-container-sponsor">-->
<!--					<div class="swiper-wrapper">-->
<!--						--><?php //foreach ($sponsors as $s) { ?>
<!--							<div class="swiper-slide">-->
<!--								<a href="--><?php //echo esc_url($s['url']); ?><!--">-->
<!--									<img src="--><?php //echo esc_url($s['logo']); ?><!--" alt="--><?php //echo esc_attr($s['name']); ?><!--">-->
<!--								</a>-->
<!--							</div>-->
<!--						--><?php //} ?>
<!--					</div>-->
<!--				</div>-->

				<div class="p-fp-section__button">
<!--					<a class="p-fp-section__button-link c-button" href="/sponsors/">-->
<!--						協賛企業の一覧を見る-->
<!--					</a>-->
				</div>
			</div>
		</div>
	</div>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const swiper = new Swiper('.swiper-container-sponsor', {
				loop: true, // 無限ループ
				speed: 5000, // 5秒かけてゆっくり移動
				autoplay: {
					delay: 0, // 待機時間なし
					disableOnInteraction: false,
				},
				allowTouchMove: false, // マウスで止められないようにする（滑らかさ優先）
				slidesPerView: 2, // スマホ初期値
				spaceBetween: 30,
				breakpoints: {
					480: {slidesPerView: 3},
					768: {slidesPerView: 4},
					1024: {slidesPerView: 5} // PCでは5件表示
				}
			});
		});
	</script>
	<?php
	return ob_get_clean();
}

add_shortcode('sponsor_swiper', 'create_swiper_sponsor_slider');
