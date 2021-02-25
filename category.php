<?php
// 메뉴에 따라 카테고리, 메뉴명, active(클릭된 상태) 변경해주기
$menu_no = $_GET['menu_no'];
?>

<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<body>
<!-- navbar-->
<header class="header mb-5">
    <!--
    *** TOPBAR ***
    _________________________________________________________
    -->
    <?php
    include 'topbar.php'
    ?>
    <!-- *** TOP BAR END ***-->

    <!--
    *** HEADER ***
    _________________________________________________________
    -->
    <?php
    include 'header.php'
    ?>
    <!-- *** HEADER END ***-->

    <div id="all">
    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- breadcrumb-->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">품목</a></li>
                            <!--카테고리(cat_no)-->
                            <li class="breadcrumb-item" id="cat_second">상의</li>
                            <li aria-current="page" class="breadcrumb-item active" id="cat_third">반팔</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <!--
                    *** MENUS AND FILTERS ***
                    _________________________________________________________
                    -->
                    <div class="card sidebar-menu mb-4">
                        <div class="card-header">
                            <h3 class="h4 card-title">카테고리</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column category-menu" id="category-menu">
                                <li><a href="category.php?menu_no=2" class="nav-link top">상의 <span class="badge badge-secondary">6</span></a>
                                    <ul class="list-unstyled top_ul">
                                        <li><a href="category.php?menu_no=5" class="nav-link" >반팔</a></li>
                                        <li><a href="category.php?menu_no=6" class="nav-link" id="banpal">긴팔</a></li>
                                        <li><a href="category.php?menu_no=7" class="nav-link">민소매</a></li>
                                        <li><a href="category.php?menu_no=8" class="nav-link">셔츠</a></li>
                                        <li><a href="category.php?menu_no=9" class="nav-link">맨투맨</a></li>
                                        <li><a href="category.php?menu_no=10" class="nav-link">카라 티셔츠</a></li>
                                        <li><a href="category.php?menu_no=11" class="nav-link">후드</a></li>
                                        <li><a href="category.php?menu_no=12" class="nav-link">니트</a></li>
                                        <li><a href="category.php?menu_no=13" class="nav-link">기타 상의</a></li>
                                    </ul>
                                </li>
                                <li><a href="category.php?menu_no=3" class="nav-link outer">아우터 <span class="badge badge-light">0</span></a>
                                    <ul class="list-unstyled outer_ul">
                                        <li><a href="category.php?menu_no=14" class="nav-link">후드 집업</a></li>
                                        <li><a href="category.php?menu_no=15" class="nav-link">라이더 자켓</a></li>
                                        <li><a href="category.php?menu_no=16" class="nav-link">블루종/MA-1</a></li>
                                        <li><a href="category.php?menu_no=17" class="nav-link">코트</a></li>
                                        <li><a href="category.php?menu_no=18" class="nav-link">패딩</a></li>
                                        <li><a href="category.php?menu_no=19" class="nav-link">트레이닝 상의</a></li>
                                        <li><a href="category.php?menu_no=20" class="nav-link">기타 아우터</a></li>
                                    </ul>
                                </li>
                                <li><a href="category.php?menu_no=4" class="nav-link bottom">바지  <span class="badge badge-secondary">0</span></a>
                                    <ul class="list-unstyled bottom_ul">
                                        <li><a href="category.php?menu_no=21" class="nav-link">데님 팬츠</a></li>
                                        <li><a href="category.php?menu_no=22" class="nav-link">숏 팬츠</a></li>
                                        <li><a href="category.php?menu_no=23" class="nav-link">슬랙스</a></li>
                                        <li><a href="category.php?menu_no=24" class="nav-link">트레이닝 바지</a></li>
                                        <li><a href="category.php?menu_no=25" class="nav-link">기타 바지</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!--브랜드 선택-->
                    <!--
                   <div class="card sidebar-menu mb-4">
                        <div class="card-header">
                            <h3 class="h4 card-title">Brands <a href="#" class="btn btn-sm btn-danger pull-right"><i class="fa fa-times-circle"></i> Clear</a></h3>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Armani  (10)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Versace  (12)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Carlo Bruni  (15)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Jack Honey  (14)
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn-default btn-sm btn-primary"><i class="fa fa-pencil"></i> Apply</button>
                            </form>
                        </div>
                    </div>-->

                    <!--색상 선택-->
                    <!--<div class="card sidebar-menu mb-4">
                        <div class="card-header">
                            <h3 class="h4 card-title">Colours <a href="#" class="btn btn-sm btn-danger pull-right"><i class="fa fa-times-circle"></i> Clear</a></h3>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"><span class="colour white"></span> White (14)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"><span class="colour blue"></span> Blue (10)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"><span class="colour green"></span>  Green (20)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"><span class="colour yellow"></span>  Yellow (13)
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"><span class="colour red"></span>  Red (10)
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn-default btn-sm btn-primary"><i class="fa fa-pencil"></i> Apply</button>
                            </form>
                        </div>
                    </div>-->
                    <!--배너-->
                    <!--<div class="banner"><a href="#"><img src="img/clothes/top/banner.jpg" alt="sales 2014" class="img-fluid"></a></div>-->
                </div>

                <div class="col-lg-9">
                    <div class="box">
                        <h1 id="menu_title"></h1>
                    </div>
                    <div class="box info-bar">
                        <div class="row">
                            <?if($menu_no == "5" || $menu_no == "6" || $menu_no == "7" || $menu_no == "8" || $menu_no == "9"|| $menu_no == "10") {?>
                                <div class="col-md-12 col-lg-3 products-showing">전체 <strong>1개</strong> 상품</div>
                            <?} elseif($menu_no == "2") {?>
                                <div class="col-md-12 col-lg-3 products-showing">전체 <strong>6개</strong> 상품</div>
                            <?} else {?>
                                <div class="col-md-12 col-lg-3 products-showing">전체 <strong>0개</strong> 상품</div>
                            <?}?>
                            <!--<div class="col-md-12 col-lg-9 products-number-sort">
                                <form class="form-inline d-block d-lg-flex justify-content-between flex-column flex-md-row">-->
                                    <!--<div class="products-number"><strong>Show</strong><a href="#" class="btn btn-sm btn-primary">12</a><a href="#" class="btn btn-outline-secondary btn-sm">24</a><a href="#" class="btn btn-outline-secondary btn-sm">All</a><span>products</span></div>-->
                                    <!--<div class="products-sort-by mt-7 mt-lg-7">
                                        <select name="sort-by" class="form-control">
                                            <option>인기순</option>
                                            <option>신상품순</option>
                                            <option>가격높은순</option>
                                            <option>가격낮은순</option>
                                        </select>
                                    </div>
                                </form>
                            </div>-->
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 text-left text-lg-left">
                                <ul class="menu list-inline mb-0">
                                    <li class="list-inline-item" ><a href="#">인기순</a></li>
                                    <li class="list-inline-item" ><a href="#">신상품순</a></li>
                                    <li class="list-inline-item" ><a href="#">가격높은순</a></li>
                                    <li class="list-inline-item" ><a href="#">가격낮은순</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row products">
                        <? if($menu_no == "5" || $menu_no == "2") {?>
                            <div class="col-lg-4 col-md-6">
                                <div class="product">
                                    <div class="flip-container">
                                        <div class="flipper">
                                            <div class="front"><a href="detail.php?menu_no=5&product_no=160"><img src="img/clothes/top/short/short_sleeves.jpg" alt="" class="img-fluid"></a></div>
                                            <div class="back"><a href="detail.php?menu_no=5&product_no=160"><img src="img/clothes/top/short/short_sleeves.jpg" alt="" class="img-fluid"></a></div>
                                        </div>
                                    </div><a href="detail.php?menu_no=5&product_no=160" class="invisible"><img src="img/clothes/top/short/short_sleeves.jpg" alt="" class="img-fluid"></a>
                                    <div class="text">
                                        <h3><a href="detail.php?menu_no=5&product_no=160">기본 반팔</a></h3>
                                        <p class="price">
                                            <del></del>9,000원
                                        </p>
                                        <p class="buttons"><a href="detail.php?menu_no=5&product_no=160" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
                                    </div>
                                    <!-- /.text-->
                                    <div class="ribbon gift">
                                        <div class="theribbon">GIFT</div>
                                        <div class="ribbon-background"></div>
                                    </div>
                                    <!-- /.ribbon-->
                                </div>
                                <!-- /.product            -->
                            </div>
                        <?}?>
                        <? if($menu_no == "6" || $menu_no == "2") {?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php?menu_no=6&product_no=100"><img src="img/clothes/top/long/long_sleeves.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php?menu_no=6&product_no=100"><img src="img/clothes/top/long/long_sleeves.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php?menu_no=6&product_no=100" class="invisible"><img src="img/clothes/top/long/long_sleeves.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php?menu_no=6&product_no=100">기본 긴팔</a></h3>
                                    <p class="price">
                                        <del></del>69,000원
                                    </p>
                                    <p class="buttons"><a href="detail.php?menu_no=6&product_no=100" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <?}?>
                        <? if($menu_no == "7" || $menu_no == "2") {?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php?menu_no=7&product_no=110"><img src="img/clothes/top/sleeveless/sleeveless.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php?menu_no=7&product_no=110"><img src="img/clothes/top/sleeveless/sleeveless.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php?menu_no=7&product_no=110" class="invisible"><img src="img/clothes/top/sleeveless/sleeveless.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php?menu_no=7&product_no=110">기본 민소매</a></h3>
                                    <p class="price">
                                        <del>20,000원</del>7,000원
                                    </p>
                                    <p class="buttons"><a href="detail.php?menu_no=7&product_no=110" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon sale">
                                    <div class="theribbon">SALE</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                                <div class="ribbon gift">
                                    <div class="theribbon">GIFT</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <?}?>
                        <? if($menu_no == "8" || $menu_no == "2") {?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php?menu_no=8&product_no=120"><img src="img/clothes/top/shirts/shirts.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php?menu_no=8&product_no=120"><img src="img/clothes/top/shirts/shirts.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php?menu_no=8&product_no=120" class="invisible"><img src="img/clothes/top/shirts/shirts.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php?menu_no=8&product_no=120">기본 셔츠</a></h3>
                                    <p class="price">
                                        <del></del>23,000원
                                    </p>
                                    <p class="buttons"><a href="detail.php?menu_no=8&product_no=120" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <?}?>
                        <? if($menu_no == "9" || $menu_no == "2") {?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php?menu_no=9&product_no=140"><img src="img/clothes/top/manToman/manToman.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php?menu_no=9&product_no=140"><img src="img/clothes/top/manToman/manToman.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php?menu_no=9&product_no=140" class="invisible"><img src="img/clothes/top/manToman/manToman.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php?menu_no=9&product_no=140">기본 맨투맨</a></h3>
                                    <p class="price">
                                        <del></del>50,000원
                                    </p>
                                    <p class="buttons"><a href="detail.php?menu_no=9&product_no=140" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
                                </div>
                                <!-- /.text-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <?}?>
                        <? if($menu_no == "10" || $menu_no == "2") {?>
                        <div class="col-lg-4 col-md-6">
                            <div class="product">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php?menu_no=10&product_no=150"><img src="img/clothes/top/karaTshirts/karaTshirts.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php?menu_no=10&product_no=150"><img src="img/clothes/top/karaTshirts/karaTshirts.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php?menu_no=10&product_no=150" class="invisible"><img src="img/clothes/top/karaTshirts/karaTshirts.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3><a href="detail.php?menu_no=10&product_no=150">기본 카라티셔츠</a></h3>
                                    <p class="price">
                                        <del></del>23,000원
                                    </p>
                                    <p class="buttons"><a href="detail.php?menu_no=10&product_no=150" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
                                </div>
                                <!-- /.text-->
                                <div class="ribbon new">
                                    <div class="theribbon">NEW</div>
                                    <div class="ribbon-background"></div>
                                </div>
                                <!-- /.ribbon-->
                            </div>
                            <!-- /.product            -->
                        </div>
                        <?}?>
                        <!-- /.products-->
                    </div>
                    <!--<div class="pages">-->
                        <!--<p class="loadMore"><a href="#" class="btn btn-primary btn-lg"><i class="fa fa-chevron-down"></i> Load more</a></p>-->
                        <!--<nav aria-label="Page navigation example" class="d-flex justify-content-center">
                            <ul class="pagination">
                                <li class="page-item"><a href="#" aria-label="Previous" class="page-link"><span aria-hidden="true">«</span><span class="sr-only">Previous</span></a></li>
                                <li class="page-item active"><a href="#" class="page-link">1</a></li>
                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                <li class="page-item"><a href="#" class="page-link">3</a></li>
                                <li class="page-item"><a href="#" class="page-link">4</a></li>
                                <li class="page-item"><a href="#" class="page-link">5</a></li>
                                <li class="page-item"><a href="#" aria-label="Next" class="page-link"><span aria-hidden="true">»</span><span class="sr-only">Next</span></a></li>
                            </ul>
                        </nav>
                    </div>-->
                </div>
                <!-- /.col-lg-9-->
            </div>
        </div>
    </div>
</div>

<!--
*** FOOTER ***
_________________________________________________________
-->
<?php
include 'footer.php'
?>
<!-- *** FOOTER END ***-->


<!--
*** COPYRIGHT ***
_________________________________________________________
-->
<?php
include 'copyright.php'
?>
<!-- *** COPYRIGHT END ***-->

<!-- JavaScript files-->
<?php
include 'jsfile.php'
?>
    <script>
        // menu_no에 따라 menu_title, cat_second, cat_third 변경하기
        menuNo = '<?echo $menu_no?>';

        // 선택된 카테고리 active
        // href$="val" : href의 속성값이 val로 끝나는 요소
        $('.category-menu li a[href$='+ menuNo +']').each(function (index, item){
            if(index == 0) {
                $(item).addClass("active");
            }
            console.log(item);
        });

        switch (menuNo){
            case "2":
                $('#menu_title').text("상의");
                $('#cat_second').text("상의");
                $('#cat_third').hide();
                break;
            case "3":
                $('#menu_title').text("아우터");
                $('#cat_second').text("아우터");
                $('#cat_third').hide();
                $('#banpal').addClass("active");
                break;
            case "4":
                $('#menu_title').text("바지");
                $('#cat_second').text("바지");
                $('#cat_third').hide();
                break;
            case "5":
                $('#menu_title').text("반팔");
                $('#cat_second').text("상의");
                $('#cat_third').text("반팔");
                break;
            case "6":
                $('#menu_title').text("긴팔");
                $('#cat_second').text("상의");
                $('#cat_third').text("긴팔");
                break;
            case "7":
                $('#menu_title').text("민소매");
                $('#cat_second').text("상의");
                $('#cat_third').text("민소매");
                break;
            case "8":
                $('#menu_title').text("셔츠");
                $('#cat_second').text("상의");
                $('#cat_third').text("셔츠");
                break;
            case "9":
                $('#menu_title').text("맨투맨");
                $('#cat_second').text("상의");
                $('#cat_third').text("맨투맨");
                break;
            case "10":
                $('#menu_title').text("카라티셔츠");
                $('#cat_second').text("상의");
                $('#cat_third').text("카라티셔츠");
                break;
            case "11":
                $('#menu_title').text("후드");
                $('#cat_second').text("상의");
                $('#cat_third').text("후드");
                break;
            case "12":
                $('#menu_title').text("니트");
                $('#cat_second').text("상의");
                $('#cat_third').text("니트");
                break;
            case "13":
                $('#menu_title').text("기타 상의");
                $('#cat_second').text("상의");
                $('#cat_third').text("기타 상의");
                break;
            case "14":
                $('#menu_title').text("후드 집업");
                $('#cat_second').text("아우터");
                $('#cat_third').text("후드 집업");
                break;
            case "15":
                $('#menu_title').text("라이더 자켓");
                $('#cat_second').text("아우터");
                $('#cat_third').text("라이더 자켓");
                break;
            case "16":
                $('#menu_title').text("블루종/MA-1");
                $('#cat_second').text("아우터");
                $('#cat_third').text("블루종/MA-1");
                break;
            case "17":
                $('#menu_title').text("코트");
                $('#cat_second').text("아우터");
                $('#cat_third').text("코트");
                break;
            case "18":
                $('#menu_title').text("패딩");
                $('#cat_second').text("아우터");
                $('#cat_third').text("패딩");
                break;
            case "19":
                $('#menu_title').text("트레이닝 상의");
                $('#cat_second').text("아우터");
                $('#cat_third').text("트레이닝 상의");
                break;
            case "20":
                $('#menu_title').text("기타 아우터");
                $('#cat_second').text("아우터");
                $('#cat_third').text("기타 아우터");
                break;
            case "21":
                $('#menu_title').text("데님 팬츠");
                $('#cat_second').text("바지");
                $('#cat_third').text("데님 팬츠");
                break;
            case "22":
                $('#menu_title').text("숏 팬츠");
                $('#cat_second').text("바지");
                $('#cat_third').text("숏 팬츠");
                break;
            case "23":
                $('#menu_title').text("슬랙스");
                $('#cat_second').text("바지");
                $('#cat_third').text("슬랙스");
                break;
            case "24":
                $('#menu_title').text("트레이닝 바지");
                $('#cat_second').text("바지");
                $('#cat_third').text("트레이닝 바지");
                break;
            case "25":
                $('#menu_title').text("기타 바지");
                $('#cat_second').text("바지");
                $('#cat_third').text("기타 바지");
                break;
        }
    </script>
</body>
</html>