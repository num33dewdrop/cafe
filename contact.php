<?php /* Template Name: CONTACT --お問い合せ-- */ ?>
<?php get_header(); ?>
<?php get_template_part( 'content', 'menu' ); ?>
    <main>
        <section id="contact" class="wrapper wrapper-col1">
            <!-- 投稿、固定ページのタイトルを表示 -->
            <h2 class="sec-title"><?php echo get_the_title(); ?></h2>
			<?php
			if ( have_posts() ):
				while ( have_posts() ):the_post();
					?>
                    <!-- ループ内容（本文） -->
                    <!--
					「the_ID()」現在の投稿、固定ページの ID を表示。必ずループの中で使用
					管理画面で投稿する際にclass属性を付けることができる。
					「post_class()」で出力
					-->
                    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <!-- 投稿、固定ページの本文を出力 -->
						<?php the_content(); ?>
                    </div>
				<?php
				endwhile;
			endif;
			?>
        </section>
        <section id="map" class="wrapper wrapper-col2 wrapper-col2-reverse bg-cream">
            <div class="col2-item contact-map">
				<?php echo get_post_meta( $post->ID, 'map', true ); ?>
            </div>
            <div class="col2-item">
                <h2 class="sec-title">
                    <span class="title-name">ACCESS</span>
                </h2>
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
            </div>
        </section>
    </main>
<?php get_footer(); ?>