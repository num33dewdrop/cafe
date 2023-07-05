<?php
if (is_404()) {
	get_header();
	get_template_part( 'content', 'menu' );
	?>
    <main>
        <section id="err-404" class="wrapper wrapper-col1 bg-cream">
            <h2 class="sec-title"><span class="title-name">ERROR：404</span></h2>
            <div style="text-align: center">
                <p>申し訳ありませんが、要求されたページが見つかりませんでした。</p>
            </div>
        </section>
    </main>
	<?php
	get_footer();
	exit;
}
?>
<?php get_header(); ?>
    <!--
	get_template_part('スラッグ名','テンプレ名');で
	スラッグ名-テンプレート名.phpを読み込む
	-->
<?php get_template_part( 'content', 'menu' ); ?>
    <main>
        <section id="mainvisual">
            <!--
            <picture>により実現できることは次の2つ。
            1.画面幅によって、表示する画像を切り替える
            2.ブラウザが対応していない形式の画像なら、別の画像を表示する
            複数の<source>と、1つの<img>を囲う形で使う。
            <picture>
            (max-width: 600px)の時
              srcset="img/mainvisual-sp.jpg"を表示
            <source media="(max-width: 600px)" srcset="<?php //echo get_post_meta($post->ID, 'sp_top_banner', true); ?>">
            </picture>
            -->
            <video autoplay muted loop poster="" width="100%" class="sp-hide">
                <source src="<?php echo get_post_meta( $post->ID, 'pc_top_banner', true ); ?>">
            </video>
            <picture class="sp-show">
                <source media="(max-width: 768px)"
                        srcset="<?php echo get_post_meta( $post->ID, 'sp_top_banner', true ); ?>">
            </picture>
        </section>
        <section id="label">
            <p><?php bloginfo( 'description' ); ?></p>
        </section>
        <section id="concept" class="wrapper wrapper-col2 bg-cream">
            <div class="col2-item">
                <img src="<?php echo get_post_meta( $post->ID, 'concept_img', true ); ?>" alt="CONCEPT-IMG">
            </div>
            <div class="col2-item">
                <h2 class="sec-title"><span class="title-name">CONCEPT</span></h2>
                <p class="concept-title"><?php echo get_post_meta( $post->ID, 'concept_title', true ); ?></p>
                <p class="concept-msg"><?php echo get_post_meta( $post->ID, 'concept_msg', true ); ?></p>
            </div>
        </section>
        <section id="menu" class="wrapper wrapper-col1 js-showFullContainer">
            <h2 class="sec-title js-subTitlePos"><span class="title-name">MENU</span></h2>
            <ul class="card-list">
				<?php dynamic_sidebar( 'MENU-AREA' ); ?>
            </ul>
            <div class="read-more">
                <button class="underline inner js-showFullBtn">もっと見る</button>
            </div>
        </section>
        <section id="pickup" class="wrapper wrapper-col1 bg-cream">
            <h2 class="sec-title"><span class="title-name">PICKUP</span></h2>
            <ul class="card-list">
                <!--
				「dynamic_sidebar()」ワードプレスで用意されている関数
				引数として、function.phpで定義したウィジェットのエリア「register_sidebar()」の名前と同じものにする。
				-->
				<?php dynamic_sidebar( 'PICKUP-AREA' ); ?>
            </ul>
            <div class="read-more">
                <a href="<?php echo get_category_link( get_cat_ID( '全てのカテゴリー' ) ); ?>"
                   class="underline inner">もっと見る</a>
            </div>
        </section>
        <section id="news" class="wrapper wrapper-col1">
            <h2 class="sec-title"><span class="title-name">NEWS</span></h2>
            <!--
           「description list(dl）= 説明リスト」
           「description term(dt）= 説明する言葉」
           「definition / description(dd）= 定義文もしくは説明文」
           という形でdt要素をdd要素で説明する「記述リスト」
           -->
            <dl>
				<?php
				$args       = array(
					'post_type'      => 'news',// 投稿タイプを指定
					'posts_per_page' => 5,// 表示する記事数
				);
				$news_query = new WP_Query( $args );
				if ( $news_query->have_posts() ):
					while ( $news_query->have_posts() ):$news_query->the_post(); ?>
                        <dt><?php the_time( 'Y-m-d H:i' ); ?></dt>
                        <dd>
                            <a href="<?php the_permalink(); ?>" class="underline inner">
								<?php the_title(); ?>
                            </a>
                        </dd>
					<?php endwhile;
				endif;
				wp_reset_postdata();
				?>
            </dl>
            <div class="read-more">
                <a href="<?php echo get_term_link( 'news', 'tax_news' ); ?>" class="inner underline">もっと見る</a>
            </div>
        </section>
        <section id="contact" class="wrapper wrapper-col2 bg-cream">
            <div class="col2-item contact-map">
				<?php echo get_post_meta( $post->ID, 'map', true ); ?>
            </div>
            <div class="col2-item">
                <h2 class="sec-title">
                    <span class="title-name"><?php bloginfo( 'name' ); ?></span>
                </h2>
                <dl>
                    <dt>Address</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_address', true ); ?></dd>
                    <dt>Tel</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_tel', true ); ?></dd>
                    <dt>URL</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_url', true ); ?></dd>
                    <dt>Mail</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_email', true ); ?></dd>
                </dl>
                <div class="contact-btn">
                    <a href="<?php echo home_url( '/contact/' ); ?>" class="btn">お問い合わせフォーム</a>
                </div>
            </div>
        </section>
    </main>
    <!-- 「footer.php」を取得 -->
<?php get_footer(); ?>