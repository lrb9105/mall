<?php
// 메뉴에 따라 카테고리, 메뉴명, active(클릭된 상태) 변경해주기
$menu_no = $_GET['menu_no'];
$product_no = $_GET['product_no'];

$referer = $_SERVER['HTTP_REFERER'];

// product_no에 해당하는 상품 정보 가져오기
// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

/* 데이터 가져오기 */
// 상품정보
$sqlProductInfo = "SELECT P.PRODUCT_SEQ,
                P.FIRST_CATEGORY, 
                P.SECOND_CATEGORY,
                P.PRODUCT_NAME,
                P.PRODUCT_PRICE,
                P.PRODUCT_PRICE_SALE,
                P.MATERIAL,
                P.MANUFACTURER,
                P.COUNTRY_OF_MANUFACTURER,
                P.CLEANING_METHOD,
                P.DETAIL_INFO,
                P.CRE_DATETIME
        FROM PRODUCT P
        WHERE P.PRODUCT_SEQ = $product_no
        ";
$resultProductInfo = mysqli_query($conn, $sqlProductInfo);
$rowProductInfo = mysqli_fetch_array($resultProductInfo);

// 상품 수량 정보
$sqlProductNumInfo = "SELECT PO.SEQ,
                          PO.PRODUCT_SEQ,
                          PO.COLOR,
                          PO.SIZE,
                          PO.QUANTITY,
                          PO.CRE_DATETIME
        FROM PRODUCT_OPTION PO
        WHERE PO.PRODUCT_SEQ = $product_no
        ";
$option1 = mysqli_query($conn, $sqlProductNumInfo);
$option2 = mysqli_query($conn, $sqlProductNumInfo);;

// 상품 이미지 정보
$sqlFileInfo = "SELECT F.SEQ,
                       F.REF_SEQ,
                       F.SAVE_PATH
        FROM FILE F
        WHERE F.REF_SEQ = $product_no
        ";
$resultFileInfo = mysqli_query($conn, $sqlFileInfo);
$rowFileInfo = mysqli_fetch_array($resultFileInfo);

?>
<script>
    if('<?echo $referer?>' == ''){
        alert('잘못된 접근입니다.');
        location.href = 'index.php';
    }
</script>

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
                <?php
                include 'sidebar.php'
                ?>
                <div class="col-lg-10 order-1 order-lg-2">
                    <div id="productMain" class="row">
                        <div class="col-md-6">
                            <div class="item"> <img src="<? echo $rowFileInfo['SAVE_PATH']?>" alt="" class="img-fluid"></div>
                            <!--<div data-slider-id="1" class="owl-carousel shop-detail-carousel">
                                <div class="item"> <img src="<?/* echo $imgPath*/?>" alt="" class="img-fluid"></div>
                                <div class="item"> <img src="<?/* echo $imgPath*/?>" alt="" class="img-fluid"></div>
                                <div class="item"> <img src="<?/* echo $imgPath*/?>" alt="" class="img-fluid"></div>
                            </div>-->
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
                                <h1 class="text-center">상품명: <?echo $rowProductInfo['PRODUCT_NAME']?></h1><br>
                                <div class="product-info">
                                    <p>정상 가격: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 15px; color: #4e555b"><del><?echo $rowProductInfo['PRODUCT_PRICE']?>원</del></span></p>
                                    <p>판매 가격: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 22px; font-weight: bold;"><?echo $rowProductInfo['PRODUCT_PRICE_SALE']?>원</span>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="">▼ <?echo ($rowProductInfo['PRODUCT_PRICE'] - $rowProductInfo['PRODUCT_PRICE_SALE'])/$rowProductInfo['PRODUCT_PRICE']*100?>%할인<em class="color-lightgrey">(-<?echo $rowProductInfo['PRODUCT_PRICE'] - $rowProductInfo['PRODUCT_PRICE_SALE']?>원)</em></span>
                                    </p>
                                    <hr>
                                    <div class="option1 form-inline">
                                        <span>색상: </span>
                                        <select class="form-control"  name="option1" id="option1" style="width: 85%; margin-left: 20px;">
                                            <option value="">[선택]</option>
                                            <?while($rowProductNumInfo = mysqli_fetch_array($option1)){?>
                                                <option value="<?echo $rowProductNumInfo['COLOR']?>"><?echo $rowProductNumInfo['COLOR']?></option>
                                            <?}?>
                                        </select>
                                    </div>
                                    <div class="option2 form-inline">
                                        <span>사이즈: </span>
                                        <select class="form-control"  name="option1" id="option1" style="width: 85%; margin: 5px;">
                                            <option value="">[선택]</option>
                                            <?while($rowProductNumInfo = mysqli_fetch_array($option2)){?>
                                                <option value="<?echo $rowProductNumInfo['SIZE']?>"><?echo $rowProductNumInfo['SIZE']?></option>
                                            <?}?>
                                        </select>
                                    </div>
                                    <div class="number form-inline">
                                        <span>수량: </span>
                                        <input id="product_number" type="number" class="form-control" style="margin-left: 20px;" value="1" min="1">
                                    </div>
                                    <br><br><br>
                                    <p style="text-align: right;">총 금액: &nbsp;&nbsp;&nbsp;<span id="total_price" style="font-size: 22px; font-weight: bold; color: red;"><?echo $rowProductInfo['PRODUCT_PRICE_SALE']?></span><span>원</span></p>
                                </div>
                                <p class="text-center buttons"><a href="basket.php" class="btn btn-info"><i class="fa fa-first-order"></i> 바로구매</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> 장바구니 담기</a><a href="basket.php" class="btn btn-outline-primary"><i class="fa fa-heart"></i> 찜하기</a></p>
                            </div>
                        </div>
                        <!-- 작은이미지 - 클릭 할 때 해당 이미지로 메인 이미지 변경-->
                        <div class="col-md-6">
                            <!--<div class="box">
                                <h1 class="text-center" id="product_name">White  Armani</h1>
                                <p class="goToDescription"><a href="#details" class="scroll-to">Scroll to product details, material &amp; care and sizing</a></p>
                                <p class="price" id="price">$124.00</p>
                                <p class="text-center buttons"><a href="basket.php" class="btn btn-info"><i class="fa fa-first-order"></i> 바로구매</a><a href="basket.php" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> 장바구니 담기</a><a href="basket.php" class="btn btn-outline-primary"><i class="fa fa-heart"></i> 찜하기</a></p>
                            </div> -->
                            <br>
                            <div data-slider-id="1" class="owl-thumbs">
                                <?while($rowFileInfo = mysqli_fetch_array($resultFileInfo)){?>
                                    <button class="owl-thumb-item"><img src="<?echo $rowFileInfo['SAVE_PATH']?>" alt="" class="img-fluid"></button>
                                <?}?>
                            </div>
                        </div>
                    </div>
                    <div id="details" class="box">
                        <div class="col-lg-12">
                            <ul id="pills-tab" role="tablist" class="nav nav-pills nav-justified">
                                <li class="nav-item" ><a id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="false" class="nav-link active">상세정보</a></li>
                                <li class="nav-item" ><a id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false" class="nav-link">후기</a></li>
                                <li class="nav-item" ><a id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false" class="nav-link">Q&A</a></li>
                            </ul>
                            <div id="pills-tabContent" class="tab-content">
                                <div id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" class="tab-pane fade active show">
                                    <?echo $rowProductInfo['DETAIL_INFO']?>
                                </div>
                                <div id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" class="tab-pane fade">Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.<br>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</div>
                                <div id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" class="tab-pane fade">Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</div>
                                <div id="pills-marketing" role="tabpanel" aria-labelledby="pills-marketing-tab" class="tab-pane fade ">Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin. Cred vinyl keffiyeh DIY salvia PBR, banh mi before they sold out farm-to-table VHS viral locavore cosby sweater. Lomo wolf viral, mustache readymade thundercats keffiyeh craft beer marfa ethical. Wolf salvia freegan, sartorial keffiyeh echo park vegan.<br>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</div>
                            </div>
                        </div>
                    </div>


                    <!--<div class="row same-height-row">
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
                        </div>
                    </div>-->
                    <!--<div class="row same-height-row">
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
                        </div>
                    </div>
                </div>-->
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
            case "28":
                $('#menu_title').text("스니커즈");
                $('#cat_second').text("신발");
                $('#cat_third').text("스니커즈");
                break;
            case "29":
                $('#menu_title').text("로퍼&구두");
                $('#cat_second').text("신발");
                $('#cat_third').text("로퍼&구두");
                break;
            case "30":
                $('#menu_title').text("슬리퍼&쪼리&샌들");
                $('#cat_second').text("신발");
                $('#cat_third').text("슬리퍼&쪼리&샌들");
                break;
            case "31":
                $('#menu_title').text("야구모자");
                $('#cat_second').text("모자");
                $('#cat_third').text("야구 모자");
                break;
            case "32":
                $('#menu_title').text("스냅백");
                $('#cat_second').text("모자");
                $('#cat_third').text("스냅백");
                break;
            case "33":
                $('#menu_title').text("비니");
                $('#cat_second').text("모자");
                $('#cat_third').text("비니");
                break;
            case "34":
                $('#menu_title').text("사파리/벙거지");
                $('#cat_second').text("모자");
                $('#cat_third').text("사파리/벙거지");
                break;
            case "35":
                $('#menu_title').text("페도라/증절모");
                $('#cat_second').text("모자");
                $('#cat_third').text("페도라/증절모");
                break;
        }

        // 메뉴타이틀에 따라 해당하는 메뉴의 클래스에 show를 추가
        switch ($('#cat_second').text()){
            case "상의":
                $('#collapse0').addClass("show");
                break;
            case "아우터":
                $('#collapse1').addClass("show");
                break;
            case "하의":
                $('#collapse2').addClass("show");
                break;
            case "신발":
                $('#collapse3').addClass("show");
                break;
            case "모자":
                $('#collapse4').addClass("show");
                break;
        }


        let origin_price = <?echo $rowProductInfo['PRODUCT_PRICE_SALE']?>

        // 수량이 변경 될 때 마다 총 금액 변경해줌
        $('#product_number').on("change", function(){
            $('#total_price').text($('#product_number').val() * parseInt(origin_price));
        });
    </script>
</body>
</html>