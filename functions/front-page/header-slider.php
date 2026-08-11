<?php
// ヘッダースライダー フロントエンドフック登録
function child_header_slisder_wp() {

	global $dp_options, $post;
	if ( ! $dp_options ) $dp_options = get_design_plus_option();

  $show_slider = is_front_page() ? true : false;

	if ( $show_slider ) {
    
    // assets
		add_action( 'wp_head', 'child_header_slider_enqueue_assets', 15 );
    // output
		add_action( 'tcd_container_start', 'child_render_header_slider', 10 );

	}
}
add_action( 'wp', 'child_header_slisder_wp' );


// enqueue_assets
function child_header_slider_enqueue_assets(){

?>
<script id="index-header-js">
document.addEventListener( "DOMContentLoaded", function(){

  var indexHeader = document.getElementById( 'js-index-header' );
  if( indexHeader == null ) return;

  var headerHeight = ( document.getElementById('js-header') ) ? document.getElementById('js-header').offsetHeight : 0;
  var headerMsgHeight = ( document.getElementById('js-header-message') ) ? document.getElementById('js-header-message').offsetHeight : 0;
  var adminBarHeight = ( document.getElementById('wpadminbar') ) ? document.getElementById('wpadminbar').offsetHeight : 0;
  var targetHeight = window.innerHeight - headerHeight - headerMsgHeight - adminBarHeight;

  indexHeader.style.height = targetHeight + 'px';

  var indexHeaderType = indexHeader.getAttribute('data-bg-type');

  switch (indexHeaderType) {
    case 'images':
      indexImageSlider();
      break;
    case 'video':
      indexVideoPlayer();
      break;
    case 'youtube':
      indexYouTubePlayer();
      break;
  }

} );


// image slider
function indexImageSlider(){

  var indexSlider = document.getElementById( 'js-index-slider' );
  if( indexSlider == null ) return;

  var isLoading = document.getElementById('js-loadding-screen') == null ? false : true;

  var splide = new Splide( indexSlider, {
    type: 'fade',
    rewind: true,
    speed: 1500,
    autoplay: 'pause',
    intersection: {
      inView: {
        autoplay: true,
      },
      outView: {
        autoplay: false,
      },
    },
    interval: 5000,
    pagination: false,
    arrows: false
  } );

  if( !isLoading ){
    splide.mount( window.splide.Extensions );
  }else{
    window.addEventListener( "tcd_end_loading", function(){
      splide.mount( window.splide.Extensions );
    });
  }

}

// video
function indexVideoPlayer(){

  var videoPlayer = document.getElementById( 'js-video-player' );
  if( !videoPlayer ) return;

  var isLoading = document.getElementById('js-loadding-screen') == null ? false : true;
  if( isLoading ){
    videoPlayer.pause();
    window.addEventListener( "tcd_end_loading", function(){
      videoPlayer.play();
    });
  }

}

// youtube
function indexYouTubePlayer(){

  var ytPlayer = document.getElementById( 'js-youtube-player' );
  if( !ytPlayer ) return;

  var ytPlayerWapper = document.getElementById( 'js-youtube-player-wrapper' );
  ytPlayerWapper.style.width = window.innerHeight * 16 / 9 + 'px';
  ytPlayerWapper.style.height = window.innerWidth * 9 / 16 + 'px';
  window.addEventListener( "resize", function(){
    ytPlayerWapper.style.width = window.innerHeight * 16 / 9 + 'px';
    ytPlayerWapper.style.height = window.innerWidth * 9 / 16 + 'px';
  });

  var isLoading = document.getElementById('js-loadding-screen') == null ? false : true;

  // Load the IFrame Player API code asynchronously.
  var tag = document.createElement('script');
  tag.src = "https://www.youtube.com/player_api";
  var firstScriptTag = document.getElementsByTagName('script')[0];
  firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

  var ytPlayerId = ytPlayer.id;
  var ytVideoId = ytPlayerWapper.getAttribute( 'data-yt-id' );

  var player;
  window.onYouTubePlayerAPIReady = function() {
    player = new YT.Player( ytPlayerId, {
      width: '100%',
      height: '100%',
      videoId: ytVideoId,
      events: {
        'onReady': function(evt) {
          evt.target.mute();
          if( !isLoading ){
            evt.target.playVideo();
          }else{
            evt.target.stopVideo();
            var ytEvent = evt.target;
            window.addEventListener( "tcd_end_loading", function(){
              ytEvent.playVideo();
            });
          }
        },
        'onStateChange': function(evt) {
          switch (evt.data) {
            case YT.PlayerState.PLAYING:
              ytPlayerWapper.classList.add( 'is-ready' );
              break;
            case YT.PlayerState.ENDED:
              evt.target.playVideo();
              break;
          }
        }
      },
      playerVars: {
        autoplay: 1,
        mute: 1,
        cc_load_policy: 0,
        controls: 0,
        disablekb: 1,
        fs: 0,
        iv_load_policy: 3,
        modestbranding: 1,
        playsinline: 1,
      }
    });
  }

}
</script>
<?php

}



// output
function child_render_header_slider( $dp_options ){

  $slider_type = $dp_options['index_slider_type'];
  $slider_copy = $dp_options['index_slider_copy'];
  $bg_type = $dp_options['index_slider_bg_type'];

?>
<div id="js-index-header" class="p-fp-slider" data-bg-type="<?php echo esc_attr( $bg_type ); ?>">
<?php

  // ショルダーコピー
  if( $slider_type == 'type1' ){

?>
  <div class="p-fp-slider_copy u-flex-align-center">
    <?php if( $slider_copy ){ ?>
    <p class="p-fp-slider_copy-text"><?php echo esc_html( $slider_copy ); ?></p>
    <?php } ?>
    <div class="p-fp-slider_copy-menu">
      <button class="p-fp-slider_copy-menu__buttom p-menu-button u-button-reset js-menu-button" type="button">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
<?php

  }


  // コンテンツ
  $headline = is_mobile() && $dp_options['index_slider_catchphrase_sp'] ? $dp_options['index_slider_catchphrase_sp'] : $dp_options['index_slider_catchphrase'];
  $logo_image_id = is_mobile() && $dp_options['index_slider_logo_sp'] ? $dp_options['index_slider_logo_sp'] : $dp_options['index_slider_logo'];
  $logo_image_meta = wp_get_attachment_metadata( $logo_image_id );
  $is_retina = $dp_options['index_slider_logo_retina'] == 'yes' ? true : false;
  if( $headline || $logo_image_meta ){

?>
  <div class="p-fp-slider__content u-flex-align-center">
    <div class="p-fp-slider__content-inner l-inner">
<?php if( $headline ){ ?>
      <h2 class="p-fp-slider__headline c-animation--text"><?php echo wp_kses_post( nl2br( $headline ) ); ?></h2>
<?php 
      
      }

      if( $logo_image_meta ){
        $logo_image_width = $is_retina ? round( $logo_image_meta['width'] / 2, 1) : $logo_image_meta['width'];
        $logo_image = wp_get_attachment_image( $logo_image_id, 'full', false, array( 'style' => 'width:' . $logo_image_width . 'px;' ) );

?>
      <div class="p-fp-slider__logo c-animation--text" style="ma">
        <?php echo $logo_image; ?>
        <div class="yeg_line_btn">
          <a href="https://lin.ee/Wf4afMy" target="_blank"><span>リーダーズ公式LINE</span></a>
        </div>

      </div>
<?php } ?>
    </div>
  </div>
<?php

  }

  // オーバーレイ
  $overlay = tcd_convert_overlay_color( $dp_options['index_slider_overlay_color'], $dp_options['index_slider_overlay_opacity'] );

?>
  <div class="p-fp-slider__overlay c-overlay" style="margin-top:5px; background-color:rgba(<?php echo $overlay; ?>);"></div>
<?php

  // 画像
  $bg_no_image = $dp_options['index_slider_bg_no_image'];
  if( $bg_type == 'images' ){

    $bg_images = is_mobile() && $dp_options['index_slider_bg_images_sp'] ? $dp_options['index_slider_bg_images_sp'] : $dp_options['index_slider_bg_images'];
    $bg_images = $bg_images ? explode( ',', $bg_images ) : array();

    if( !empty( $bg_images ) ){

?>
  <div id="js-index-slider" class="p-fp-slider__bg p-fp-slider__images splide">
    <div class="p-fp-slider__images-track splide__track">
      <ul class="p-fp-slider__images-list splide__list">
<?php
      foreach( $bg_images as $bg_image_id ){
        $bg_image = wp_get_attachment_image_src( $bg_image_id, 'full' );
        if( !$bg_image ) continue;
?>
        <li class="p-fp-slider__images-item splide__slide">
          <div class="p-fp-slider__images-item__bg" style="background:url(<?php echo esc_attr( $bg_image[0] ); ?>) no-repeat center; background-size:cover;"></div>
        </li>
<?php
      }
?>
      </ul>
    </div>
  </div>
<?php

    }
  // mp4動画
  }elseif( $bg_type == 'video' && auto_play_movie() ){

    //$video_url = wp_get_attachment_url( $dp_options['index_slider_bg_video'] );
    $video_list = [
            '/wp-content/uploads/2026/02/AdobeStock_1805563908.mov',
            '/wp-content/uploads/2026/03/AdobeStock_234956678.mov',
						'/wp-content/uploads/2026/03/796308926.648145.mp4'
    ];
    $rand_index = array_rand($video_list, 1);
    $video_url = $video_list[$rand_index];
    if( $video_url ){

?>
  <div class="p-fp-slider__bg p-fp-slider__video">
    <video id="js-video-player" class="p-fp-slider__video-bg" preload="auto" autoplay playsinline muted loop>
      <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4" />
    </video>
  </div>
<?php

    }

  // youtube
  }elseif( $bg_type == 'youtube' && auto_play_movie() ){

    $youtube_url = $dp_options['index_slider_bg_youtube'];

    if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[\w\-?&!#=,;]+/[\w\-?&!#=/,;]+/|(?:v|e(?:mbed)?)/|[\w\-?&!#=,;]*[?&]v=)|youtu\.be/)([\w-]{11})(?:[^\w-]|\Z)%i', $youtube_url, $matches)) {

?>
  <div class="p-fp-slider__bg p-fp-slider__youtube">
    <div id="js-youtube-player-wrapper" class="p-fp-slider__youtube-bg" data-yt-id="<?php echo esc_attr($matches[1]); ?>">
      <div id="js-youtube-player"></div>
    </div>
  </div>
<?php

    }

  // 代替画像
  }else{

    $alternate_image = wp_get_attachment_image_src( $dp_options['index_slider_bg_no_image'], 'full' );
    if( $alternate_image ){

?>
  <div class="p-fp-slider__bg p-fp-slider__alternate">
    <div class="p-fp-slider__alternate-bg" style="background:url(<?php echo esc_attr( $alternate_image[0] ); ?>) no-repeat center; background-size:cover;"></div>
  </div>
<?php

    }

  }

?>
</div>
<?php

}