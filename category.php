<?php get_header(); ?>
<?php get_template_part( 'content', 'menu' ); ?>
<main>
    <!-- blog_list -->
    <!-- 記事一覧ページのファイル名は、名前が決まっている。category.php -->
    <section id="blog_list" class="">
        <h2 class="sec-title">BLOG</h2>
        <div class="wrapper wrapper-col2">
            <div id="content" class="article">
                <!-- 記事のループ -->
				<?php get_template_part( 'loop' ); ?>
                <!-- ページネーション
				「function_exists("pagination")」引数に指定した関数があるかどうか
				「$wp_query->max_num_pages」ページ数を自作関数paginationに渡す
				-->
				<?php if ( function_exists( "pagination" ) ) {
					pagination( $wp_query->max_num_pages );
				} ?>
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
