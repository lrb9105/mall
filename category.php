<?php
// 메뉴에 따라 카테고리, 메뉴명, active(클릭된 상태) 변경해주기
$menu_no = $_GET['menu_no'];
$referer = $_SERVER['HTTP_REFERER'];

// menu_no에 해당하는 모든 상품 가져오기
// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 데이터 가져오기 - 자유게시판
$sql = "SELECT P.PRODUCT_SEQ,
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
                P.CRE_DATETIME,
                F.SAVE_PATH
        FROM PRODUCT P
        INNER JOIN FILE F ON P.PRODUCT_SEQ = REF_SEQ
        WHERE P.SECOND_CATEGORY = $menu_no
        AND F.TYPE = 0
        ";
// 쿼리를 통해 가져온 결과
$result = mysqli_query($conn, $sql);
$count = mysqli_num_rows($result);

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

    <div id="all" >
    <div id="content" >
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
                <?php
                include 'sidebar.php'
                ?>

                <div class="col-lg-10">
                    <div class="box">
                        <h1 id="menu_title"></h1>
                    </div>
                    <div class="box info-bar">
                        <div class="row">
                            <div class="col-md-12 col-lg-3 products-showing">전체 <strong>1개</strong> 상품</div>
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
                        <div class="col-lg-3 col-md-6">
                            <?for($i=0; $i < $count; $i++){
                                $row = mysqli_fetch_array($result)
                                ?>
                                <div class="product">
                                    <div class="flip-container">
                                        <div class="flipper">
                                            <div class="front"><a href="detail.php?menu_no=<?echo $row['SECOND_CATEGORY']?>&product_no=<?echo $row['PRODUCT_SEQ']?>"><img src="<?echo $row['SAVE_PATH']?>" alt="" class="img-fluid"></a></div>
                                        </div>
                                    </div><a href="detail.html?menu_no=<?echo $row['SECOND_CATEGORY']?>&product_no=<?echo $row['PRODUCT_SEQ']?>" class="invisible"><img src="<?echo $row['SAVE_PATH']?>" alt="" class="img-fluid"></a>
                                    <div class="text">
                                        <h3><a href="detail.php?menu_no=<?echo $row['SECOND_CATEGORY']?>&product_no=<?echo $row['PRODUCT_SEQ']?>"><?echo $row['PRODUCT_NAME']?></a></h3>
                                        <p class="price">
                                            <del></del><?echo $row['PRODUCT_PRICE']?>원
                                        </p>
                                        <p class="buttons"><a href="#" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
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



                            <?} ?>
                            <!-- /.product            -->
                        </div>
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
    </script>
</body>
</html>