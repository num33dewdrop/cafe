<?php /*Template Name: HOME --トップページ-- */ ?>
<?php get_header(); ?>
<?php get_template_part( 'content', 'menu' ); ?>
    <main>
        <section id="mainvisual">
            <video autoplay muted loop poster="<?php echo get_post_meta( $post->ID, 'sp_top_banner', true ); ?>" width="100%" class="sp-hide">
                <source src="<?php echo get_post_meta( $post->ID, 'pc_top_banner', true ); ?>">
            </video>
            <picture class="top-img sp-show ">
                <source media="(max-width: 414px)"
                        srcset="<?php echo get_post_meta( $post->ID, 'sp_min_top_banner', true ); ?>">
                <img src="<?php echo get_post_meta( $post->ID, 'sp_top_banner', true ); ?>" alt="SP-IMG">
            </picture>
        </section>
        <section id="label">
            <p><?php bloginfo( 'description' ); ?></p>
        </section>
        <section id="concept" class="wrapper wrapper-col2 wrapper-col2-reverse bg-cream">
            <div class="col2-item col2-item-img sp-hide">
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
				<?php dynamic_sidebar( 'PICKUP-AREA' ); ?>
            </ul>
            <div class="read-more">
                <a href="<?php echo get_category_link( get_cat_ID( '全てのカテゴリー' ) ); ?>"
                   class="underline inner">もっと見る</a>
            </div>
        </section>
        <section id="news" class="wrapper wrapper-col1">
            <h2 class="sec-title"><span class="title-name">NEWS</span></h2>
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
        <section id="map" class="wrapper wrapper-col2 wrapper-col2-reverse bg-cream">
            <div class="col2-item contact-map sp-hide">
				<?php echo get_post_meta( $post->ID, 'map', true ); ?>
            </div>
            <div class="col2-item">
                <h2 class="sec-title"><span class="title-name">ACCESS</span></h2>
                <dl>
                    <dt>営業時間</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'business_hours', true ); ?></dd>
                    <dt>定休日</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'closing_day', true ); ?></dd>
                    <dt>住所</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_address', true ); ?></dd>
                    <dt>電話番号</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_tel', true ); ?></dd>
                    <dt>URL</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_url', true ); ?></dd>
                    <dt>E-mail</dt>
                    <dd><?php echo get_post_meta( $post->ID, 'my_email', true ); ?></dd>
                </dl>
                <div class="contact-btn">
                    <a href="<?php echo home_url( '/contact/' ); ?>" class="btn">お問い合わせフォーム</a>
                </div>
            </div>
        </section>
    </main>
<?php get_footer(); ?>