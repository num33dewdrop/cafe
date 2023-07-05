<?php
ini_set( 'log_errors', 'on' );
ini_set( 'error_log', 'php.log' );
/*===============================
タイトル表示
===============================*/
function mytheme_set() {
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'mytheme_set' );

/*===============================
タイトルキャッチフレーズ削除
===============================*/
function remove_title_description ( $title ) {
	if ( is_home() || is_front_page() ) {
		unset( $title['tagline'] );
	}
	return $title;
}
add_filter( 'document_title_parts', 'remove_title_description', 10, 1 );
/*===============================
管理画面用CSS
===============================*/
function add_custom_styles() {
	if ( defined( 'IFRAME_REQUEST' ) && IFRAME_REQUEST ) {
		?>
        <style>
            img {
                width: 150px !important;
            }
            .c-card__container {
                display: flex;
            }
            .c-card__head {
                margin-right: 15px;
            }
        </style>
		<?php
	}
}
add_action( 'wp_head', 'add_custom_styles' );

function add_admin_css() {
	wp_enqueue_style( 'my_admin_css', get_stylesheet_directory_uri() . '/style-admin.css' );
}
add_action( 'admin_enqueue_scripts', 'add_admin_css' );
/*===============================
JSファイル読み込み
===============================*/
function my_scripts_method() {
	wp_enqueue_script(
		'custom_script',
		get_template_directory_uri() . '/app.js'
	);
}

add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

/*===============================
カスタムヘッダー
===============================*/
//画像の設置
$custom_header_defaults = array(
	/*
	「default-image」は、ヘッダー画像のデフォルトの画像のパスを指定するもの
	「get_bloginfo()」引数に「template_url」を入れることで、このテンプレートまでのURLが出力
	*/
	'default-image' => get_bloginfo( 'template_url' ) . '/img/logo.png',
	'header-text'   => false,//ヘッダー画像の上にテキストをかぶせる
);
//カスタムヘッダー機能を有効化
add_theme_support( 'custom-header', $custom_header_defaults );

/*===============================
カスタムメニュー
===============================*/
/*
第一引数：wp_nav_menuの'theme_location' => 'mainmenu'と対応
第二引数：管理画面に表示される名前
*/
register_nav_menu( 'mainmenu', 'メインメニュー' );

/*===============================
カスタム投稿タイプの追加
===============================*/
add_action( 'init', 'create_post_type' );
add_theme_support( 'post-thumbnails' );
function create_post_type() {
	register_post_type( 'news', // 投稿タイプ名の定義
		array(
			'labels'             => array(
				'name'          => __( '更新情報' ), // 表示する投稿タイプ名
				'singular_name' => __( '更新情報' )
			),
			'public'             => true,// 管理画面に表示しサイト上にも表示する
			'menu_position'      => 5, //「投稿」の下に追加
			'has_archive'        => true,//trueにすると投稿した記事の一覧ページを作成することができる
			'publicly_queryable' => true,
			'show_in_rest'       => true,//新エディタ Gutenberg を有効化（REST API を有効化）
			'supports'           => array( 'title', 'editor', 'thumbnail' ),
		)
	);

	register_taxonomy( 'tax_news', array( 'news' ), array(
		'hierarchical' => true,//コンテンツを階層構造にするかどうか
		'label'        => 'ニュースカテゴリー',
		'show_ui'      => true,
		'public'       => true
	) );
	register_taxonomy_for_object_type( 'tax_news', 'news' );
}

/*===============================
抜粋末尾の文字列を[…]から変更する
===============================*/
function my_excerpt_more($more) {
	return '...';
}
add_filter('excerpt_more', 'my_excerpt_more');

/*===============================
ページネーション
===============================*/
function pagination( $pages = '', $range = 2 ) {
	$showitems = ( $range * 2 ) + 1;//表示するページ数(５ページを表示)
	global $paged;//現在のページ数（ワードプレスの方で用意されているグローバル変数）

	if ( empty( $paged ) ) {
		$paged = 1;
	}//デフォルトのページ数
	//全部のページ数が入っているはずだが、もし呼び出す関数の方で引数を渡していなかった場合は空なので、この中の処理が走る
	if ( $pages == '' ) {
		global $wp_query;//wp-includes/class-wp-query.php に定義されているクラスで、WordPress ブログへの複雑な投稿やページのリクエストを取り扱う。
		$pages = $wp_query->max_num_pages;//全ページ数を取得
		if ( ! $pages ) {//全ページ数が空の場合１とする
			$pages = 1;
		}
	}
	if ( 1 != $pages ) {//全ページ数が１でない場合はページネーションを表示する
		//「\n」は、改行
		echo "<div class=\"pagenation\">\n";
		echo "<ul>\n";
		//Prev:現在のページ値が１より大きい場合表示
		if ( $paged > 1 ) {
			echo "<li class=\"prev\"><a href='" . get_pagenum_link( $paged - 1 ) . "'>PREV</a></li>\n";
		}

		for ( $i = 1; $i <= $pages; $i ++ ) {
			//総ページが１でない場合 かつ
			//（$iが現在ページ＋２＋１以上 または $iが現在ページ−２−１以下でない場合） または 総ページが表示するページ数以下でない場合
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				//三項演算子での条件分岐
				echo ( $paged == $i ) ? "<li class=\"active\">" . $i . "</li>\n" : "<li><a href='" . get_pagenum_link( $i ) . "'>" . $i . "</a></li>\n";
			}
		}
		//Next:総ページ数より現在ページ数の値が小さい場合は表示
		if ( $paged < $pages ) {
			echo "<li class=\"next\"><a href='" . get_pagenum_link( $paged + 1 ) . "'>NEXT</a></li>\n";
		}
		echo "</ul>\n";
		echo "</div>\n";
	}
}

/*===============================
カスタムフィールド
===============================*/
//投稿ページへ表示するカスタムボックスを定義
add_action( 'admin_menu', 'add_custom_inputbox' );
//追加した表示項目のデータ更新・保存のためのアクションフック データベースへ
add_action( 'save_post', 'save_custom_postdata' );

/*入力項目がどの投稿タイプのページに表示させるか設定*/
function add_custom_inputbox() {
	//第一引数：編集画面のhtmlに挿入されるid属性名
	//第二引数：管理画面に表示されるカスタムフィールド名
	//第三引数：メタボックスの中に出力される関数名
	//第四引数：管理画面に表示するカスタムフィールドの場所（postなら投稿、pageなら固定ページ）
	//第五引数：配置される順序
	add_meta_box( 'concept_id', 'CONCEPT入力欄', 'custom_area', 'page', 'normal' );
	add_meta_box( 'top_banner_id', 'TOP-BANNER画像URL入力欄', 'custom_area2', 'page', 'normal' );
	add_meta_box( 'contact_id', 'CONTACT入力欄', 'custom_area3', 'page', 'normal' );
}

/*管理画面に表示される内容*/
/*ABOUT*/
function custom_area() {
	global $post;
	//get_post_meta() 第二引数には、データベースへ保存するときのkeyを指定。適当でいいが、今回は「concept_img」というkeyで、入力した情報をDBへ保存、「concept_img」というkeyで情報を取得することができる。
	echo 'CONCEPT-IMG-URL<br><input type="text" name="concept_img" value="' . get_post_meta( $post->ID, 'concept_img', true ) . '"><br>';
	echo 'CONCEPT-TITLE<br><input type="text" name="concept_title" value="' . get_post_meta( $post->ID, 'concept_title', true ) . '"><br>';
	echo 'CONCEPT-MSG<br><textarea cols="50" rows="5" name="concept_msg">' . get_post_meta( $post->ID, 'concept_msg', true ) . '</textarea><br>';
}

/*TOP-BANNER*/
function custom_area2() {
	global $post;
	echo 'TOP-MOVIE-URL<br><input type="text" name="pc_top_banner" value="' . get_post_meta( $post->ID, 'pc_top_banner', true ) . '"><br>';
	echo 'SP URL<br><input type="text" name="sp_top_banner" value="' . get_post_meta( $post->ID, 'sp_top_banner', true ) . '"><br>';
	echo 'SP-MIN URL<br><input type="text" name="sp_min_top_banner" value="' . get_post_meta( $post->ID, 'sp_min_top_banner', true ) . '"><br>';
}

/*CONTACT*/
function custom_area3() {
	global $post;
	//get_post_meta() 第二引数には、データベースへ保存するときのkeyを指定。適当でいいが、今回は「about」というkeyで、入力した情報をDBへ保存、「about」というkeyで情報を取得することができる。
	echo 'SHOP-NAME<br><input type="text" name="my_name" value="' . get_post_meta( $post->ID, 'my_name', true ) . '"><br>';
	echo 'ADDRESS<br><input type="text" name="my_address" value="' . get_post_meta( $post->ID, 'my_address', true ) . '"><br>';
	echo 'TEL<br><input type="text" name="my_tel" value="' . get_post_meta( $post->ID, 'my_tel', true ) . '"><br>';
	echo 'URL<br><input type="text" name="my_url" value="' . get_post_meta( $post->ID, 'my_url', true ) . '"><br>';
	echo 'EMAIL<br><input type="email" name="my_email" value="' . get_post_meta( $post->ID, 'my_email', true ) . '"><br>';
	echo 'BUSINESS-HOURS<br><input type="text" name="business_hours" value="' . get_post_meta( $post->ID, 'business_hours', true ) . '"><br>';
	echo 'CLOSING-DAY<br><input type="text" name="closing_day" value="' . get_post_meta( $post->ID, 'closing_day', true ) . '"><br>';
	echo 'CONTACT-MAP<br><textarea cols="50" rows="5" name="map">' . get_post_meta( $post->ID, 'map', true ) . '</textarea><br>';
}

//保存用処理関数
function save_process( $post_data, $post_id, $key ) {
	//カスタムフィールドに入力された情報を取り出す
	$data = ( isset( $post_data ) ) ? $post_data : '';
	//内容が変わっていた場合、保存してた情報を更新
	if ( $data != get_post_meta( $post_id, $key, true ) ) {
		//「update_post_meta()」
		//第一引数に「$post_id」
		//第二引数に保存したいkeyの情報、「my_name」というkeyを指定
		//第三引数に上書きたい値。
		update_post_meta( $post_id, $key, $data );
	} elseif ( $data == '' ) {
		//「delete_post_meta」
		//第三引数にはDBの情報を指定
		//ある固定ページIDの中にある、keyが「about」の情報、さらにその中の「この値を持っているものを削除してください」というかたちになる。
		delete_post_meta( $post_id, $key, get_post_meta( $post_id, $key, true ) );
	}
}

/*投稿ボタンを押した際のデータの更新と保存*/
//引数には、カスタムフィールドを入力した固定ページのIDが送られてくる
function save_custom_postdata( $post_id ) {
	/*CONCEPT*/
	save_process( $_POST['concept_img'], $post_id, 'concept_img' );
	save_process( $_POST['concept_title'], $post_id, 'concept_title' );
	save_process( $_POST['concept_msg'], $post_id, 'concept_msg' );
	/*TOP-BANNER*/
	save_process( $_POST['pc_top_banner'], $post_id, 'pc_top_banner' );
	save_process( $_POST['sp_top_banner'], $post_id, 'sp_top_banner' );
	save_process( $_POST['sp_min_top_banner'], $post_id, 'sp_min_top_banner' );
	/*CONTACT*/
	save_process( $_POST['my_name'], $post_id, 'my_name' );
	save_process( $_POST['my_address'], $post_id, 'my_address' );
	save_process( $_POST['my_tel'], $post_id, 'my_tel' );
	save_process( $_POST['my_url'], $post_id, 'my_url' );
	save_process( $_POST['my_email'], $post_id, 'my_email' );
	save_process( $_POST['business_hours'], $post_id, 'business_hours' );
	save_process( $_POST['closing_day'], $post_id, 'closing_day' );
	save_process( $_POST['map'], $post_id, 'map' );
}

/*===============================
カスタムウィジェット
===============================*/
//お決まりパターン
//ウィジェットエリアを作成する関数がどれなのかを登録する
add_action( 'widgets_init', 'my_widgets_area' );
//ウィジェット自体の作成するクラスがどれなのかを登録する
add_action( 'widgets_init', function () {
	register_widget( 'Menu_widgets' );
	register_widget( 'Pickup_widgets' );
} );

//関数名は「add_action()」で登録した「my_widgets_area」という関数名
function my_widgets_area() {
	//表示画面のdynamic_sidebarに渡すときはnameかid
	register_sidebar( array(
		'name'          => 'MENU-AREA',//管理画面で表示したいエリアの名前
		'id'            => 'widgets_menu',//管理画面にウィジェットエリアが表示される時に付くID属性名
		'before_widget' => '<li class="card-item js-showFullTarget">',
		'after_widget'  => '</li>'//管理画面のウィジェットエリアを表示するHTMLを、何で囲むか？という形で指定
	) );

	register_sidebar( array(
		'name'          => 'PICKUP-AREA',//管理画面で表示したいエリアの名前
		'id'            => 'widgets_pickup',//管理画面にウィジェットエリアが表示される時に付くID属性名
		'before_widget' => '<li class="card-item">',
		'after_widget'  => '</li>'//管理画面のウィジェットエリアを表示するHTMLを、何で囲むか？という形で指定
	) );

	//サイドバーは、ウィジェット自体は既に、ワードプレスの方でデフォルトで用意されている。ウィジェットエリアだけ作ってあげれば大丈夫
	//「before_title」「after_title」
	//ウィジェット自体を表示するときに、そのタイトル部分の前後に付けるものを指定
	register_sidebar( array(
		'name'          => 'right_sidebar',
		'id'            => 'my_sidebar',
		'before_widget' => '<div class="sidebar-item">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2>',
		'after_title'   => '</h2>'
	) );
}

//ウィジェット自体を作成するクラス
//クラス名は「add_action()」で登録した「my_widgets_item1」というクラス名
//WP_Widget」というクラスを継承
class Menu_widgets extends WP_Widget {
	//初期化（管理画面で表示するウィジェットの名前を設定する）
	/*
	初期化をするメソッドを作っている。コンストラクタ。
	初期化用のメソッドは、必ずクラス名と同じ名前にしてあげる。
	*/
	public function __construct() {
		parent::__construct(
			'Menu_widgets', // Base ID
			__( 'MENU-WIDGETS', 'text_domain' ), // Name
			[ 'description' => __( 'MENUのウィジェットです。', 'text_domain' ), ] // Args
		);
	}
	//ウィジェットの入力項目を作成する処理
	/*
	入力項目のメソッド名は、必ず「form()」というメソッド
	ワードプレスの方から自動的に、このウィジェットに入力された情報というのが渡ってくる
	それを受け取る引数$instance
	*/
	public function form( $instance ) {
		//入力された情報をサニタイズして変数へ格納
		//「esc_attr()」ワードプレスで用意されているHTMLのタグを取り除く関数
		$title   = ( isset( $instance['title'] ) ) ? $instance['title'] : __( '新しいタイトル', 'text_domain' );
		$price   = ( isset( $instance['price'] ) ) ? $instance['price'] : __( '新しい値段', 'text_domain' );
		$img_url = ( isset( $instance['img_url'] ) ) ? $instance['img_url'] : __( '新しい画像URL', 'text_domain' );
		//入力された情報をサニタイズして表示
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
				<?php _e( 'タイトル:' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'price' ); ?>">
				<?php _e( '値段：' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'price' ); ?>"
                   name="<?php echo $this->get_field_name( 'price' ); ?>" type="text"
                   value="<?php echo esc_attr( $price ); ?>"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'img_url' ); ?>"><?php _e( '画像URL：' ); ?></label>
            <input type="text" id="<?php echo $this->get_field_id( 'img_url' ); ?>"
                   name="<?php echo $this->get_field_name( 'img_url' ); ?>" value="<?php echo esc_attr( $img_url ); ?>">
        </p>
		<?php
	}
	//ウィジェットに入力された情報を保存する処理
	//メソッド名は必ず「update」
	//引数には新しく入力された情報と、データベースに元々保存されている情報が渡ってくる
	public function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
		$instance['title']   = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : ''; //サニタイズ html,phpタグを取り除く
		$instance['price']   = ( ! empty( $new_instance['price'] ) ) ? trim( $new_instance['price'] ) : ''; //サニタイズ html,phpタグを取り除く
		$instance['img_url'] = ( ! empty( $new_instance['img_url'] ) ) ? strip_tags( $new_instance['img_url'] ) : '';

		return $instance;
	}
	//管理画面から入力されたウィジェットを画面に表示する処理
	//メソッド名は、必ず「widget」、引数を二つ取る
	/*
	第一引数には、ウィジェットエリア自体の情報が、配列の形式で渡ってくる。「register_sidebar()」の関数を使って渡した連想配列。
	第二引数の方に、データベースに保存された情報が渡ってくる。
	*/
	function widget( $args, $instance ) {
		//配列を変数に展開
		/*
		「extract()」というPHPの関数を使って、配列を変数に展開。
		配列のkey名がそのまま変数名として使える。
		今回は、ウィジェットエリアで定義した情報は使わないので、書いても書かなくても大丈夫。
		*/
		extract( $args );
		//ウィジェットから入力された情報を取得
		/*
		「apply_filters()」ワードプレスで用意された関数
		第一引数に、「widget_title」と指定。
		ワードプレスの「フィルターフック」という機能。
		「widget_title」という名前の付いた関数を呼び出し、取得した情報をその関数で何かしら処理をして、それを返す。
		単純にデータベースに保存された情報を取得し、変数へ格納するのではなく、何かしらの処理を挟みたい場合に使える。
		今回は、フィルターフックとして「widget_title」といったものを用意していないので、特に何か処理が走るといったことはない。
		単純にデータベースに保存された情報を取得して、変数に格納するだけ。
		直接変数に格納してしまっても大丈夫だが、今後の改修で必要になった時便利。
		*/
		//ウィジェットから入力された情報を取得
		$title   = apply_filters( 'widget_title', $instance['title'] );
		$price   = apply_filters( 'widget_price', $instance['price'] );
		$img_url = apply_filters( 'widget_img_url', $instance['img_url'] );
		//ウィジェットから入力された情報がある場合、htmlを表示する
		if ( ! empty( $title ) ) {
			echo $before_widget;
			?>
            <div class="img-container">
				<?php if ( ! empty( $img_url ) ) : ?>
                    <img src="<?php echo $img_url; ?>" alt="<?php echo $title; ?>">
				<?php else: ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/img/noimage.jpg" alt="no image">
				<?php endif; ?>
            </div>
            <div class="card-info">
                <p class="card-title"><?php echo $title; ?></p>
                <p class="card-price">¥ <?php echo $price; ?> -</p>
            </div>
			<?php
			echo $after_widget;
		}
	}
}

class Pickup_widgets extends WP_Widget {
	//初期化（管理画面で表示するウィジェットの名前を設定する）
	/*
	初期化をするメソッドを作っている。コンストラクタ。
	初期化用のメソッドは、必ずクラス名と同じ名前にしてあげる。
	*/
	public function __construct() {
		parent::__construct(
			'Pickup_widgets', // Base ID
			__( 'PICKUP-WIDGETS', 'text_domain' ), // Name
			[ 'description' => __( 'BLOG-PICKUPのウィジェットです。', 'text_domain' ), ] // Args
		);
	}
	//ウィジェットの入力項目を作成する処理
	/*
	入力項目のメソッド名は、必ず「form()」というメソッド
	ワードプレスの方から自動的に、このウィジェットに入力された情報というのが渡ってくる
	それを受け取る引数$instance
	*/
	public function form( $instance ) {
		//入力された情報をサニタイズして変数へ格納
		//「esc_attr()」ワードプレスで用意されているHTMLのタグを取り除く関数
		$page_id = ( isset( $instance['page_id'] ) ) ? $instance['page_id'] : __( '新しい記事ID', 'text_domain' );
		//入力された情報をサニタイズして表示
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'page_id' ); ?>">
				<?php _e( '記事ID:' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'page_id' ); ?>"
                   name="<?php echo $this->get_field_name( 'page_id' ); ?>" type="text"
                   value="<?php echo esc_attr( $page_id ); ?>"/>
        </p>
		<?php
	}
	//ウィジェットに入力された情報を保存する処理
	//メソッド名は必ず「update」
	//引数には新しく入力された情報と、データベースに元々保存されている情報が渡ってくる
	public function update( $new_instance, $old_instance ) {
		$instance            = $old_instance;
		$instance['page_id'] = ( ! empty( $new_instance['page_id'] ) ) ? strip_tags( $new_instance['page_id'] ) : ''; //サニタイズ html,phpタグを取り除く

		return $instance;
	}
	//管理画面から入力されたウィジェットを画面に表示する処理
	//メソッド名は、必ず「widget」、引数を二つ取る
	/*
	第一引数には、ウィジェットエリア自体の情報が、配列の形式で渡ってくる。「register_sidebar()」の関数を使って渡した連想配列。
	第二引数の方に、データベースに保存された情報が渡ってくる。
	*/
	function widget( $args, $instance ) {
		//配列を変数に展開
		extract( $args );
		//ウィジェットから入力された情報を取得
		$page_id = apply_filters( 'widget_page_id', $instance['page_id'] );

		$ar    = array(
			'post_type'   => 'post',
			'post__in'    => array( $page_id ),
			'post_status' => array( 'publish' ),
			'order'       => 'asc',
			'orderby'     => 'post_date'
		);
		$query = new WP_Query( $ar );

		//ウィジェットから入力された情報がある場合、htmlを表示する
		if ( ! empty( $page_id ) ) {
			if ( $query->have_posts() ):
				while ( $query->have_posts() ):$query->the_post();
					echo $before_widget; ?>
                    <a href="<?php echo get_permalink(); ?>" class="img-container">
						<?php
						if ( has_post_thumbnail() ):
							//記事に設定されているアイキャッチ画像を表示
							the_post_thumbnail( 'thumbnail' );
						else:
							//アイキャッチ画像がない場合に表示する画像
							?>
                            <img src="<?php echo get_template_directory_uri(); ?>/img/noimage.jpg" alt="no image">
						<?php
						endif;
						?>
                    </a>
                    <div class="card-info">
                        <p class="card-title"><?php the_title(); ?></p>
                    </div>
					<?php echo $after_widget;
				endwhile;
			else:
				echo '投稿はありません';
			endif;
			wp_reset_postdata();
		}
	}
}