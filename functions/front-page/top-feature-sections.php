<?php

add_shortcode( 'top_feature_sections', 'yeg_top_feature_sections_shortcode' );
function yeg_top_feature_sections_shortcode() {
  $upload_dir = wp_upload_dir();
  $video_url  = trailingslashit( $upload_dir['baseurl'] ) . 'bunkakai.mp4';
  $poster_url = trailingslashit( $upload_dir['baseurl'] ) . 'bunkakai-poster.jpg';

  ob_start();
  ?>
  <!--研修会で出会う学び-->
  <section class="p-fp-section yeg-top-bunkakai-pv">
    <div class="l-inner yeg-top-bunkakai-pv__inner">
      <div class="p-fp-section__header">
        <h2 class="p-fp-section__headline c-headline">研修会で出会う学び</h2>
<!--        <p class="p-fp-section__desc">全国の仲間とともに、研究会や分科会を通じてどのような学びや気づきを得られるのか。<br>全国リーダーズ研修会の魅力を映像でご紹介します。</p>-->
      </div>

      <div class="yeg-top-bunkakai-pv__media">
        <video class="yeg-top-bunkakai-pv__video" controls playsinline preload="metadata" poster="<?php echo esc_url( $poster_url ); ?>" aria-label="全国リーダーズ研修会での学びを紹介する動画">
          <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
          お使いのブラウザは動画再生に対応していません。
        </video>
      </div>
    </div>
  </section>

  <!--リーダーズガイド-->
  <section class="p-fp-section yeg-top-leaders-guide">
    <div class="l-inner yeg-top-leaders-guide__inner">
      <div class="p-fp-section__header">
        <h2 class="p-fp-section__headline c-headline">リーダーズガイド</h2>
      </div>

      <div class="yeg-top-leaders-guide__embed" style="position:relative;padding-top:max(60%,324px);width:100%;height:0;">
        <iframe style="position:absolute;border:none;width:100%;height:100%;left:0;top:0;" src="https://online.fliphtml5.com/akihj/0922-5Hx9/" title="" seamless="seamless" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true"></iframe>
      </div>
      <div class="p-fp-section__button">
        <a class="p-fp-section__button-link c-button" href="https://online.fliphtml5.com/akihj/0922-5Hx9/" target="_blank" rel="noopener">リーダーズガイドを<br>大きな画面で見る</a>
      </div>
    </div>
  </section>

  <!-- リーダーズかりゆしウェア-->
  <section class="p-fp-free-space p-fp-section yeg-kariyusi" style="">
    <div class="l-inner">
      <div class="p-fp-section__header yeg-top-section-header">
        <h2 class="p-fp-section__headline c-headline" style="padding: 40px 0px;">リーダーズかりゆしウェア</h2>
      </div>
      <div class="yeg-kariyusi__content">
        <div class="yeg-kariyusi__image">
          <a href="https://yegmall.myshopify.com/products/%E3%83%AA%E3%83%BC%E3%83%80%E3%83%BC%E3%82%BA%E3%81%8B%E3%82%8A%E3%82%86%E3%81%97%E3%82%A6%E3%82%A7%E3%82%A2" target="_blank">
            <picture>
              <source media="(max-width: 767px)" srcset="<?php echo esc_url( content_url( 'uploads/2026/08/IMG_3322.jpeg' ) ); ?>">
              <img src="https://leaders.yeg.jp/cms/wp-content/uploads/2026/10/kariyusi_wear.jpg" alt="リーダーズかりゆしウェア">
            </picture>
          </a>
        </div>
        <div class="yeg-kariyusi__body">
          <div class="yeg_kariyusi_button">
            <a href="https://yegmall.myshopify.com/products/%E3%83%AA%E3%83%BC%E3%83%80%E3%83%BC%E3%82%BA%E3%81%8B%E3%82%8A%E3%82%86%E3%81%97%E3%82%A6%E3%82%A7%E3%82%A2" class="c-button" target="_blank">未来共創市場で購入！</a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
  return ob_get_clean();
}
