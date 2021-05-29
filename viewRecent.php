<?php
// 메뉴에 따라 카테고리, 메뉴명, active(클릭된 상태) 변경해주기
$referer = $_SERVER['HTTP_REFERER'];

//쿠키에 값이 있다면 조회
$viewRecentList = $_COOKIE['VIEW_RECENT_LIST'];
if($viewRecentList != null && $viewRecentList != ''){
    $viewRecentList = substr($viewRecentList,0, strlen($viewRecentList) -1);
}

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 상품갯수 가져오기
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
                P.SOLD_OUT_YN,
                F.SAVE_PATH
        FROM PRODUCT P
        INNER JOIN FILE F ON P.PRODUCT_SEQ = REF_SEQ
        WHERE P.PRODUCT_SEQ IN ($viewRecentList)
        AND F.TYPE = 0
        ";
// 쿼리를 통해 가져온 결과
$result = mysqli_query($conn, $sql);

// 현재 페이지번호
if(!isset($_GET['page_no'])){
    $page_no = 1;
} else{
    $page_no = $_GET['page_no'];
}

// 총 게시물 개수
$total_count_of_post = mysqli_num_rows($result);
// 한 페이지당 보여줄 게시물 개수
$count_of_post_per_page = 8;
// 총 페이지 개수(나머지가 있다면 1추가)
//$total_count_of_page = $total_count_of_post / $count_of_post_per_page + ($total_count_of_post % $count_of_post_per_page > 0 ? 1 : 0);
$total_count_of_page = ceil($total_count_of_post / $count_of_post_per_page);
// 한 페이지에서 보여줄 블록 개수
$count_of_block_per_page = 2;
// 총 블록그룹 개수(총 페이지 / 페이지 당 블록 수) + 1(나머지 있다면, 없다면 0)
//$total_count_of_block = $total_count_of_page / $count_of_block_per_page + ($total_count_of_page % $count_of_block_per_page > 0 ? 1 : 0);
$total_count_of_block = ceil($total_count_of_page / $count_of_block_per_page);
// 현재 블록그룹 번호
if($page_no != 1){
    $current_num_of_block = ceil($page_no/$count_of_block_per_page);
} else{
    $current_num_of_block = 1;
}
// 블록의 시작페이지 번호
$start_page_num_of_block = $current_num_of_block * $count_of_block_per_page - ($count_of_block_per_page - 1);
// 블록의 종료페이지 번호
$end_page_num_of_block = $current_num_of_block * $count_of_block_per_page;
if($end_page_num_of_block > $total_count_of_page){
    $end_page_num_of_block = $total_count_of_page;
}

// 조회 해야할 데이터 시작번호
$s_point = ($page_no-1) * $count_of_post_per_page;



// 실제 데이터 조회
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
                P.SOLD_OUT_YN,
                F.SAVE_PATH
        FROM PRODUCT P
        INNER JOIN FILE F ON P.PRODUCT_SEQ = REF_SEQ
        WHERE P.PRODUCT_SEQ IN ($viewRecentList)
        AND F.TYPE = 0
        ORDER BY FIELD(PRODUCT_SEQ,$viewRecentList)
        LIMIT $s_point,$count_of_post_per_page
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
                            <li class="breadcrumb-item"><a href="#">홈</a></li>
                            <!--카테고리(cat_no)-->
                            <li class="breadcrumb-item" id="cat_second">최근본상품</li>
                        </ol>
                    </nav>
                </div>

                <div class="col-lg-12">
                    <div class="box">
                        <h1 id="menu_title">최근본상품</h1>
                    </div>
                    <div class="box info-bar">
                        <div class="row">
                            <div class="col-md-12 col-lg-3 products-showing">전체 <strong><?=$total_count_of_post?>개</strong> 상품</div>
                        </div>
                    </div>
                    <div class="row products">
                        <?for($i=0; $i < $count; $i++){
                        $row = mysqli_fetch_array($result)
                        ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="product" style="border: 3px solid grey;">
                                <div class="flip-container">
                                    <a href="detail.php?menu_no=<?echo $row['SECOND_CATEGORY']?>&product_no=<?echo $row['PRODUCT_SEQ']?>">
                                        <?if($row['SOLD_OUT_YN'] != 'Y') {?>
                                            <img id='front' src="<?echo $row['SAVE_PATH']?>" alt="" class="img-fluid">
                                        <?} else{?>
                                            <img style="opacity: 0.2;" id='front' src="<?echo $row['SAVE_PATH']?>" alt="" class="img-fluid">
                                        <?}?>
                                    </a>
                                </div>
                                <div class="text">
                                    <h3 style="text-align: left;"><a href="detail.php?menu_no=<?echo $row['SECOND_CATEGORY']?>&product_no=<?echo $row['PRODUCT_SEQ']?>"><?echo $row['PRODUCT_NAME']?></a></h3>
                                    <p class="price" style="text-align: left;">
                                        <?if($row['SOLD_OUT_YN'] != 'Y' && $row['PRODUCT_PRICE'] != $row['PRODUCT_PRICE_SALE']){?>
                                            <del style="font-size: 15px;"><?echo number_format($row['PRODUCT_PRICE'])?>원</del><br>
                                        <?} else{ ?>
                                            <del></del><br>
                                        <?}?>
                                        <span>
                                                    <?if($row['SOLD_OUT_YN'] != 'Y') {?>
                                                        <?echo number_format($row['PRODUCT_PRICE_SALE'])?>원
                                                    <?} else{?>
                                                        <span style="color: red">품절</span>
                                                    <?}?>
                                                </span>
                                        <?if($row['SOLD_OUT_YN'] != 'Y' && $row['PRODUCT_PRICE'] != $row['PRODUCT_PRICE_SALE']){?>
                                            <span style="color: red; float: right;"><?echo ceil(($row['PRODUCT_PRICE'] - $row['PRODUCT_PRICE_SALE'])/$row['PRODUCT_PRICE']*100)?>%</span>
                                        <?}?>
                                    </p>
<!--                                    <p class="buttons"><a href="#" class="btn btn-primary"><i class="fa fa-shopping-cart"></i>장바구니추가</a></p>
-->                                </div>
                                <?if($row['PRODUCT_PRICE'] != $row['PRODUCT_PRICE_SALE']){?>
                                <!-- /.text-->
                                    <?if($row['SOLD_OUT_YN'] != 'Y' &&$row['PRODUCT_PRICE'] != $row['PRODUCT_PRICE_SALE']){?>
                                        <div class="ribbon sale">
                                            <div class="theribbon">SALE</div>
                                            <div class="ribbon-background"></div>
                                        </div>
                                    <?}?>
                                <!-- /.ribbon-->
                                <?}?>
                            </div>
                            <!-- /.product            -->
                        </div>
                        <?} ?>
                        <!-- /.products-->
                    </div>
                    <div class="pages">
                        <nav aria-label="Page navigation example" class="d-flex justify-content-center">
                            <ul class="pagination">
                                <?if($current_num_of_block != 1){ ?>
                                    <li class="page-item"><a href="viewRecent.php?&page_no=<?= 1?>" aria-label="Previous" class="page-link"><span aria-hidden="true"><<</span><span class="sr-only">Previous</span></a></li>
                                <?}?>
                                <?if($current_num_of_block != 1){ ?>
                                    <li class="page-item"><a href="viewRecent.php?menu_no=&page_no=<?= $start_page_num_of_block - 1?>" aria-label="Previous" class="page-link"><span aria-hidden="true"><</span><span class="sr-only">Previous</span></a></li>
                                <?}?>
                                <?for($i = $start_page_num_of_block; $i <= $end_page_num_of_block; $i++) {
                                    if($page_no != $i){ ?>
                                        <li class="page-item"><a href="viewRecent.php?&page_no=<?= $i?>" class="page-link"><?= $i?></a></li>
                                    <?} else{ ?>
                                        <li class="page-item active"><a href="viewRecent.php?&page_no=<?= $i?>" class="page-link"><?= $i?></a></li><?}?>
                                <?}?>
                                <?if($current_num_of_block != $total_count_of_block){?>
                                    <li class="page-item"><a href="viewRecent.php?&page_no=<?= $end_page_num_of_block + 1?>" aria-label="Next" class="page-link"><span aria-hidden="true">></span><span class="sr-only">Next</span></a></li>
                                <?}?>
                                <?if($current_num_of_block != $total_count_of_block){?>
                                    <li class="page-item"><a href="viewRecent.php?&page_no=<?= $total_count_of_page?>" aria-label="Next" class="page-link"><span aria-hidden="true">>></span><span class="sr-only">Next</span></a></li>
                                <?}?>
                            </ul>
                        </nav>
                    </div>
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
        // 선택된 카테고리 active
        // href$="val" : href의 속성값이 val로 끝나는 요소

    </script>
</body>
</html>