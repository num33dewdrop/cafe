<!DOCTYPE html>
<html lang="ja">
<head>
    <!--
    「bloginfo()」はサイトのさまざまな情報を表示させる関数。
    WordPressの管理画面から入力されたプロフィールや一般設定などの情報を表示させることができる
    -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <!-- 検索結果画面のタイトルに表示 -->
    <!-- 「wp_title()」非推奨のため、titleタグなし -->
    <!--  数字の並びの自動リンク化を無効  -->
    <meta name="format-detection" content="telephone=no">
    <!--
    表示領域の設定
    「width=device-width」を記述することで、表示領域の幅を、PCやスマホなどの端末画面の幅に合わせられる。
    「initial-scale=1」は初期のズーム倍率を表します。1に設定することで、初めに表示された時の違和感をなくす。
    これらの設定にすることで、どのデバイスでも表示領域が合わせられ、ズームの倍率もページ全てが見える癖のない初期ズーム倍率になる。
    したがって、この記述が一番問題が起こりにくい、最新の内容と言える。
    -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- ブラウザのタブやブックマーク一覧などに表示される小さなアイコン -->
    <link rel="shortcut icon" href="<?php get_template_directory_uri() . 'img/favicon.ico'; ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Narrow:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Eczar:wght@500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <!-- CSSファイルである「style.css」のパスを表示 -->
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"
            integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
            crossorigin="anonymous"></script>
    <!--
    WordPress管理画面などから設定した内容が反映される
    WordPressサイドで用意してくれるhtmlをhead内に出力してくれる関数
    All in one SEO Pack等のプラグインで設定したmeta情報や、プラグイン固有のスタイルシート、javascriptファイル等
    -->
	<?php wp_head(); ?>
</head>
<!-- 表示されているウェブページの種類によって class を付与 -->
<body <?php body_class(); ?>>