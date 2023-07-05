$(function () {
    /*================================================================
    フッター最下部
    ================================================================*/
    let $ftr = $('#footer');
    if (window.innerHeight > $ftr.offset().top + $ftr.outerHeight()) {
        $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) + 'px;'});
    }
    /*================================================================
    全件表示
    ================================================================*/
    $(".js-showFullBtn").on('click', function () {
        let $this = $(this),
            $parent = $this.parents(".js-showFullContainer"),
            $target = $parent.find(".js-showFullTarget"),
            $targetPos = $parent.find(".js-subTitlePos");
        $this.toggleClass("active");
        $target.toggleClass("active");
        if ($this.hasClass("active")) {
            $this.text('閉じる');
        } else {
            $this.text('もっと見る');
            $("body , html").scrollTop($targetPos.offset().top);
        }
    });
    /*================================================================
    ハンバーガー解除
    ================================================================*/
    $(".header-nav li a").on('click', function () {
        $(this).parents(".header-nav").siblings("#menu-btn-check").removeAttr("checked").prop("checked", false).change();
    });
    /*================================================================
    サイドバー解除
    ================================================================*/
    let over_flg = true;
    $(".sidebar-container").hover(function () {
        over_flg = true;
    }, function () {
        over_flg = false;
    });
    $("body").on('click', function () {
        if (over_flg === false) $("#sidebar-btn-check").removeAttr("checked").prop("checked", false).change();
    });
    /*================================================================
    サイドバーカレント表示
    ================================================================*/
    let currentBtn = $(".sidebar-btn"),
        currentListBtn = $("#sidebar .current-menu-item a");
    if (currentListBtn.length) currentBtn.text(currentListBtn.text());
});