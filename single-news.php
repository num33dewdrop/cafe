<?php get_header();
get_template_part( 'content', 'menu' ); ?>
    <!-- 記事の詳細画面はsingle.phpの名前のファイルが自動的に読み込まれる -->
    <main>
        <!-- blog-->
        <section id="blog" class="">
            <h2 class="sec-title">NEWS</h2>
            <div class="wrapper wrapper-col2">
                <div id="content" class="article">
					<?php
					if ( have_posts() )://投稿があるかの確認
						while ( have_posts() ):the_post();
							?>
                            <article class="article-item">
                                <!--
								「the_title()」現在の投稿のタイトルを表示、あるいは返す。必ずループの中で使用。
								ループの外では get_the_title() を使う
								「the_permalink()」ループ の中で処理されている投稿の パーマリンク の URL を表示
								パーマリンク　　リソースや記事が永久的に保存される URL
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
                                <div class="article-body">
                                    <!-- 本文表示 -->
									<?php the_content(); ?>
                                </div>
                                <p class="article-info">
                                    <span class="label">投稿者：</span><?php the_author(); ?>　<br class="sp-show">
                                    <span class="label">投稿日：</span><?php the_time( "Y年m月j日" ); ?>　<br class="sp-show">
                                </p>
                            </article>
						<?php endwhile; ?>
                        <div class="pagenation">
                            <ul>
                                <!--
								前のページへのリンクは「previous_post_link()」
								新しい(次の)記事へは「next_post_link()」
								第一引数に「%link」と入れて、第二引数に、実際にリンクとして表示したい文字を指定
								-->
                                <li class="prev"><?php previous_post_link( '%link', 'PREV' ); ?></li>
                                <li class="next"><?php next_post_link( '%link', 'NEXT' ); ?></li>
                            </ul>
                        </div>
                        <!--COMMENTS-->
						<?php comments_template(); ?>
					<?php else: //投稿がなかった場合?>
                        <h2 class="sec-title">記事が見つかりませんでした。</h2>
                        <p>検索で見つかるかもせれません。</p><br>
						<?php get_search_form(); //検索フォームの生成　search.phpを読み込む?>
					<?php endif; ?>
                </div>
                <div class="sidebar-container">
                    <input type="checkbox" id="sidebar-btn-check">
                    <label for="sidebar-btn-check" class="sidebar-btn">サイドバー</label>
                    <!-- サイドバー -->
					<?php get_sidebar(); ?>
                </div>
            </div>
        </section>
    </main>
<?php get_footer(); ?>