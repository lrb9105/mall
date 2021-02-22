<?php
// 메뉴에 따라 카테고리, 메뉴명, active(클릭된 상태) 변경해주기
$menu_no = $_GET['menu_no'];
$product_no = $_GET['product_no'];
$imgPath = null;

switch ($product_no){
    // 반팔
    case "160":
        $imgPath = "img/clothes/top/short/short_sleeves.jpg";
        break;
    // 긴팔
    case "100":
        $imgPath = "img/clothes/top/long/long_sleeves.jpg";
        break;
    // 민소매
    case "110":
        $imgPath = "img/clothes/top/sleeveless/sleeveless.jpg";
        break;
    // 셔츠
    case "120":
        $imgPath = "img/clothes/top/shirts/shirts.jpg";
        break;
    // 맨투맨
    case "140":
        $imgPath = "img/clothes/top/manToman/manToman.jpg";
        break;
    // 카라티셔츠
    case "150":
        $imgPath = "img/clothes/top/karaTshirts/karaTshirts.jpg";
        break;
}
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
                            <li class="breadcrumb-item" id="cat_second"><a href="#"></a></li>
                            <li class="breadcrumb-item" id="cat_third"><a href="#"></a></li>
                            <li aria-current="page" class="breadcrumb-item" id="cat_four"></li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3 order-2 order-lg-1">
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
                                <li><a href="category.php?menu_no=2" class="nav-link top">상의 <span class="badge badge-secondary">42</span></a>
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
                                <li><a href="category.php?menu_no=3" class="nav-link outer">아우터 <span class="badge badge-light">123</span></a>
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
                                <li><a href="category.php?menu_no=4" class="nav-link bottom">바지  <span class="badge badge-secondary">11</span></a>
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
                </div>
                <div class="col-lg-9 order-1 order-lg-2">
                    <div id="productMain" class="row">
                        <div class="col-md-6">
                            <div data-slider-id="1" class="owl-carousel shop-detail-carousel">
                                <div class="item"> <img src="<? echo $imgPath?>" alt="" class="img-fluid"></div>
                                <div class="item"> <img src="<? echo $imgPath?>" alt="" class="img-fluid"></div>
                                <div class="item"> <img src="<? echo $imgPath?>" alt="" class="img-fluid"></div>
                            </div>
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
                        </div>
                        <div class="col-md-6">
                            <div class="box">
                                <h1 class="text-center" id="product_name">White  Armani</h1>
                                <p class="goToDescription"><a href="#details" class="scroll-to">Scroll to product details, material &amp; care and sizing</a></p>
                                <p class="price" id="price">$124.00</p>
                                <p class="text-center buttons"><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add to cart</a><a href="basket.php" class="btn btn-outline-primary"><i class="fa fa-heart"></i> Add to wishlist</a></p>
                            </div>
                            <div data-slider-id="1" class="owl-thumbs">
                                <button class="owl-thumb-item"><img src="<? echo $imgPath?>" alt="" class="img-fluid"></button>
                                <button class="owl-thumb-item"><img src="<? echo $imgPath?>" alt="" class="img-fluid"></button>
                                <button class="owl-thumb-item"><img src="<? echo $imgPath?>" alt="" class="img-fluid"></button>
                            </div>
                        </div>
                    </div>
                    <div id="details" class="box">
                        <p></p>
                        <h4>Product details</h4>
                        <p>White lace top, woven, has a round neck, short sleeves, has knitted lining attached</p>
                        <h4>Material &amp; care</h4>
                        <ul>
                            <li>Polyester</li>
                            <li>Machine wash</li>
                        </ul>
                        <h4>Size &amp; Fit</h4>
                        <ul>
                            <li>Regular fit</li>
                            <li>The model (height 5'8" and chest 33") is wearing a size S</li>
                        </ul>
                        <blockquote>
                            <p><em>Define style this season with Armani's new range of trendy tops, crafted with intricate details. Create a chic statement look by teaming this lace number with skinny jeans and pumps.</em></p>
                        </blockquote>
                        <hr>
                        <div class="social">
                            <h4>Show it to your friends</h4>
                            <p><a href="#" class="external facebook"><i class="fa fa-facebook"></i></a><a href="#" class="external gplus"><i class="fa fa-google-plus"></i></a><a href="#" class="external twitter"><i class="fa fa-twitter"></i></a><a href="#" class="email"><i class="fa fa-envelope"></i></a></p>
                        </div>
                    </div>
                    <div class="row same-height-row">
                        <div class="col-md-3 col-sm-6">
                            <div class="box same-height">
                                <h3>You may also like these products</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product2.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product2_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product2.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product1.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product1_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product1.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product3.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product3_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product3.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product-->
                        </div>
                    </div>
                    <div class="row same-height-row">
                        <div class="col-md-3 col-sm-6">
                            <div class="box same-height">
                                <h3>Products viewed recently</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product2.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product2_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product2.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product1.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product1_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product1.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product-->
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="product same-height">
                                <div class="flip-container">
                                    <div class="flipper">
                                        <div class="front"><a href="detail.php"><img src="img/product3.jpg" alt="" class="img-fluid"></a></div>
                                        <div class="back"><a href="detail.php"><img src="img/product3_2.jpg" alt="" class="img-fluid"></a></div>
                                    </div>
                                </div><a href="detail.php" class="invisible"><img src="img/product3.jpg" alt="" class="img-fluid"></a>
                                <div class="text">
                                    <h3>Fur coat</h3>
                                    <p class="price">$143</p>
                                </div>
                            </div>
                            <!-- /.product-->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-9-->
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
        productNo = '<?echo $product_no?>';

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

        productName = null;
        price = null;

        switch (productNo){
            // 반팔
            case "160":
                productName = '기본 반팔';
                price = '9,000원';
                break;
            // 긴팔
            case "100":
                productName = '기본 긴팔';
                price = '69,000원';
                break;
            // 민소매
            case "110":
                productName = '기본 민소매';
                price = '7,000원';
                break;
            // 셔츠
            case "120":
                productName = '기본 셔츠';
                price = '23,000원';
                break;
            // 맨투맨
            case "140":
                productName = '기본 맨투맨';
                price = '50,000원';
                break;
            // 카라티셔츠
            case "150":
                productName = '기본 카라티셔츠';
                price = '23,000원';
                break;
        }

        $('#cat_four').text(productName);
        $('#product_name').text(productName)
        $('#price').text(price);
    </script>
</body>
</html>