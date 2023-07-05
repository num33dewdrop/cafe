<header id="header">
    <h1 class="site-title">
        <!-- サイトのURLを取得し表示 -->
        <a class="img-container" href="<?php echo home_url(); ?>">
            <!--
			「header_image()」管理画面から登録した画像のパスを表示
			「bloginfo('name')」ブログのタイトルを表示
			-->
            <img src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?>">
        </a>
    </h1>
    <div class="hamburger-menu">
        <input type="checkbox" id="menu-btn-check">
        <label for="menu-btn-check" class="menu-btn"><span></span></label>
        <nav class="header-nav">
			<?php
			/*
			「theme_location」
			「container」というkeyで、メニューを囲うタグを指定
			「menu_class」生成されるメニューのhtmlにclass属性を付ける
			「item_wrap」生成されるメニューのフォーマットを指定
			ulタグで囲った中に、「%3$s」を入れることで、この中にメニューを表示
			*/
			wp_nav_menu( array(
				'theme_location' => 'mainmenu',
				'container'      => '',
				'menu_class'     => '',
				'item_wrap'      => '<ul>%3$s</ul>'
			) );
			?>
        </nav>
    </div>
</header>