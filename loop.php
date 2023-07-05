<?php
if ( have_posts() ):
	while ( have_posts() ):the_post();
		?>
        <article class="article-item">
            <!--
           「the_title()」現在の投稿のタイトルを表示、あるいは返す。必ずループの中で使用。ループの外では get_the_title() を使う
           「the_permalink()」ループ の中で処理されている投稿の パーマリンク の URL を表示。パーマリンク:リソースや記事が永久的に保存される URL
           -->
            <h2 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <!--
           「the_author_meta()」投稿の作成者を表示、ループの外で使う場合、ユーザー ID を指定する必要がある。
           「the_time("Y年m月j日")」年月日形式で投稿日時を表示
           「single_cat_title()」引数の値の後にカテゴリー名を表示。
           -->
            <p class="article-info">
                <span class="label">カテゴリ名：</span><?php the_terms( get_the_ID(), ( get_post_type() == 'post' ? 'category' : 'tax_news' ) ); ?>
            </p>
            <div class="article-main">
                <div class="article-thumbnail">
                    <!-- 本文表示 -->
					<?php if ( ! empty( get_the_post_thumbnail() ) ) : ?>
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					<?php else: ?>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/noimage.jpg" alt="no image">
					<?php endif; ?>
                </div>
                <div class="article-body">
                    <p><?php the_excerpt(); ?></p>
                </div>
            </div>
            <p class="article-info">
                <span class="label">投稿者：</span><?php the_author(); ?>　<br class="sp-show">
                <span class="label">投稿日：</span><?php the_time( "Y年m月j日" ); ?>　<br class="sp-show">
            </p>
        </article>
	<?php endwhile; ?>
<?php else: //投稿がなかった場合?>
    <div id="noItem">
        <h3>記事が見つかりませんでした。</h3>
        <p>検索で見つかるかもせれません。</p>
    </div>
	<?php get_search_form(); //検索フォームの生成　search.phpを読み込む?>
<?php endif; ?>