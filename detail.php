<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];
$user_name = $_SESSION['NAME'];

// 메뉴에 따라 카테고리, 메뉴명, active(클릭된 상태) 변경해주기
$menu_no = $_GET['menu_no'];
$product_no = $_GET['product_no'];

$viewRecentList = $_COOKIE['VIEW_RECENT_LIST'];

// 쿠키에 값이 있다면
if($viewRecentList != null && $viewRecentList != ''){
    // 해당 상품이 쿠키에 없다면
    if(strpos($viewRecentList, $product_no.',') === false){
        $viewRecentList = $viewRecentList.$product_no.',';
        setcookie("VIEW_RECENT_LIST", $viewRecentList, time() + 3600*24, '/');
    }
} else{
    // 쿠키에 추가
    setcookie("VIEW_RECENT_LIST", $product_no.',', time() + 3600*24, '/');
}

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
                P.CRE_DATETIME,
                P.THICKNESS,
                P.REFLECTION,
                P.ELASTICITY,
                P.SEASON,
                P.FIT,
                P.TOUCH
        FROM PRODUCT P
        WHERE P.PRODUCT_SEQ = $product_no
        ";
$resultProductInfo = mysqli_query($conn, $sqlProductInfo);
$rowProductInfo = mysqli_fetch_array($resultProductInfo);

// 상품 색상 정보
$sqlProductColorInfo = "SELECT DISTINCT PO.COLOR
        FROM PRODUCT_OPTION PO
        WHERE PO.PRODUCT_SEQ = $product_no
        AND QUANTITY > 0
        ";
$option1 = mysqli_query($conn, $sqlProductColorInfo);

// 상품 사이즈 정보
$sqlProductSizeInfo = " SELECT PO.COLOR, PO.SIZE
                        FROM PRODUCT_OPTION PO
                        WHERE PO.PRODUCT_SEQ = $product_no
                        AND COLOR IN ( SELECT COLOR FROM PRODUCT_OPTION WHERE PRODUCT_SEQ = $product_no AND QUANTITY > 0);
        ";
$option2 = mysqli_query($conn, $sqlProductSizeInfo);

$option2Arr = array();
$colorArr = array();
$sizeArr = array();

while($rowOption2 = mysqli_fetch_array($option2)){

    array_push($colorArr, $rowOption2['COLOR']);
    array_push($sizeArr, $rowOption2['SIZE']);
}
array_push($option2Arr, $colorArr, $sizeArr);

// 상품 이미지 정보
$sqlFileInfo = "SELECT F.SEQ,
                       F.REF_SEQ,
                       F.SAVE_PATH
        FROM FILE F
        WHERE F.REF_SEQ = $product_no
        AND (F.TYPE = 0 OR F.TYPE = 1)
        ORDER BY TYPE
        ";
$resultFileInfo = mysqli_query($conn, $sqlFileInfo);
$rowFileInfo = mysqli_fetch_array($resultFileInfo);
$repImgSrc = $rowFileInfo['SAVE_PATH'];

// 상품 치수정보
$sqlSizeInfo = "SELECT PRODUCT_SEQ 
                , TOP_SHOULDER_SIZE 
                , TOP_CHEST_SIZE 
                , TOP_ARMHOLE_SIZE 
                , TOP_ARM_SIZE 
                , TOP_TOTAL_LENGTH 
                , OUTER_SHOULDER_SIZE 
                , OUTER__CHEST_SIZE 
                , OUTER_SLEEVE_LENGTH 
                , OUTER_TOTAL_LENGTH 
                , BOTTOM_WAIST_SIZE 
                , BOTTOM_RISE 
                , BOTTOM_THIGH_SIZE 
                , BOTTOM_HEM_SIZE 
                , BOTTOM_TOTAL_LENGTH 
                , HAT_ROUND 
                , HAT_LENGTH 
                , HAT_HEIGHT 
                , SIZE
        FROM PRODUCT_SIZE
        WHERE PRODUCT_SEQ = $product_no
        ";
$resultSizeInfo = mysqli_query($conn, $sqlSizeInfo);

// 모델정보
$sqlModelInfo = "SELECT PRODUCT_SEQ 
                , MODEL_HEIGHT 
                , MODEL_WEIGHT 
                , MODEL_SIZE
        FROM PRODUCT_MODEL_SIZE
        WHERE PRODUCT_SEQ = $product_no
        ";
$resultModelInfo = mysqli_query($conn, $sqlModelInfo);

// 로그인한 사용자가 해당 물품의 구매자인지 확인(상품번호가 동일한 배송완료 상태의 상품을 가지고 있는지 확인)
$sqlOrderInfo = "SELECT 1
                 FROM ORDER_LIST OL
                 INNER JOIN ORDER_PRODUCT_LIST OPL ON OL.ORDER_NO = OPL.ORDER_NO
                 WHERE OPL.PRODUCT_SEQ = $product_no
                 AND OL.ORDER_PERSON_ID = '$login_id'
                 AND OPL.REVIEW_YN = '0'
                 AND ORDER_STATE = '배송완료';
        ";
$resultOrderInfo = mysqli_query($conn, $sqlOrderInfo);
$rowOrderInfo = mysqli_fetch_array($resultOrderInfo);

// 로그인한 사용자의 해당 상품 구매내역(순번, 컬러, 사이즈)
$sqlOrderListInfo = " SELECT SEQ
                        , PRODUCT_COLOR
                        , PRODUCT_SIZE
                        FROM ORDER_PRODUCT_LIST OPL
                        INNER JOIN ORDER_LIST OL ON OPL.ORDER_NO = OL.ORDER_NO
                        WHERE OPL.PRODUCT_SEQ = $product_no
                        AND OL.ORDER_PERSON_ID = '$login_id'
                        AND OPL.REVIEW_YN = '0'
                        AND ORDER_STATE = '배송완료'
        ";
$resultOrderListInfo = mysqli_query($conn, $sqlOrderListInfo);

// 별점정보
$sqlStarScoreInfo = "
                 SELECT SUM(STAR_SCORE) SUM
                      , COUNT(*) COUNT_OF_REVIEWER
                 FROM REVIEW 
                 WHERE PRODUCT_SEQ = $product_no
                 AND USE_YN = 'Y'
        ";
$resultStarScoreInfo = mysqli_query($conn, $sqlStarScoreInfo);
$rowStarScoreInfo = mysqli_fetch_array($resultStarScoreInfo);

$sum = $rowStarScoreInfo[0];
if($sum == null || $sum == ''){
    $sum = 0;
}
$cntOfReviewer = $rowStarScoreInfo[1];
if($cntOfReviewer == null || $cntOfReviewer == ''){
    $cntOfReviewer = 0;
}

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
<style>
    .img-fluid{
        object-fit: cover;
    }
</style>
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
                            <li aria-current="page" class="breadcrumb-item" id="cat_four"><?=$rowProductInfo['PRODUCT_NAME']?></li>
                        </ol>
                    </nav>
                </div>
                <?php
                include 'sidebar.php'
                ?>
                <div class="col-lg-10 order-1 order-lg-2">
                    <div id="productMain" class="row">
                        <div class="col-md-6">
                            <div class="item"">
                                <img id="img_rep" src="<? echo $rowFileInfo['SAVE_PATH']?>" alt="" class="img-fluid">
                            </div>
                            <!--<div data-slider-id="1" class="owl-carousel shop-detail-carousel">
                                <div class="item"> <img src="<?/* echo $imgPath*/?>" alt="" class="img-fluid"></div>
                                <div class="item"> <img src="<?/* echo $imgPath*/?>" alt="" class="img-fluid"></div>
                                <div class="item"> <img src="<?/* echo $imgPath*/?>" alt="" class="img-fluid"></div>
                            </div>-->
                        </div>
                        <div class="col-md-6">
                            <form id="form_purchase" method="post" action="checkout4.php" onsubmit="return verifyBeforePurchase();">
                                <div class="box">
                                    <h1 class="text-center"> <?echo $rowProductInfo['PRODUCT_NAME']?></h1><br>
                                    <div class="product-info">
                                        <p>정상 가격: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 15px; color: #4e555b"><del><?echo number_format(str_replace(",",'',$rowProductInfo['PRODUCT_PRICE']))?>원</del></span></p>
                                        <p>판매 가격: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 22px; font-weight: bold;"><?echo number_format(str_replace(",",'',$rowProductInfo['PRODUCT_PRICE_SALE']))?>원</span>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="">▼ <?echo ceil(($rowProductInfo['PRODUCT_PRICE'] - $rowProductInfo['PRODUCT_PRICE_SALE'])/$rowProductInfo['PRODUCT_PRICE']*100)?>%할인<em class="color-lightgrey">(-<?echo number_format(str_replace(",",'',$rowProductInfo['PRODUCT_PRICE']) - str_replace(",",'',$rowProductInfo['PRODUCT_PRICE_SALE']))?>원)</em></span>
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
                                            <select class="form-control"  name="option2" id="option2" style="width: 85%; margin: 5px;">
                                                <option value="">[선택]</option>
                                                <?/*while($rowProductNumInfo = mysqli_fetch_array($option2)){*/?><!--
                                                    <option value="<?/*echo $rowProductNumInfo['SIZE']*/?>"><?/*echo $rowProductNumInfo['SIZE']*/?></option>
                                                --><?/*}*/?>
                                            </select>
                                        </div>
                                        <div class="number form-inline">
                                            <span>수량: </span>
                                            <input id="product_number" name="product_number" type="number" max="100" class="form-control" style="margin-left: 20px;" value="1" min="1">
                                        </div>
                                        <!--<div class="number form-inline">
                                            <span>배송비 <br>결제:</span>
                                            <select class="form-control"  name="delivery_payment" id="delivery_payment" style="width: 70%; margin: 5px;">
                                                <option value="">[선택]</option>
                                                <option value="0">결제시 2,500원 함께 선결제</option>
                                                <option value="1">착불</option>
                                            </select>
                                        </div>-->
                                        <hr>
                                        <div class="number form-inline">
                                            <span>상품평: &nbsp;&nbsp;&nbsp; <span id="raty_product"></span><span id="cnt_reviewer">&nbsp;&nbsp;<?=$cntOfReviewer?>건</span></span>
                                        </div>
                                        <hr>
                                        <div class="number form-inline">
                                            <span>배송유형: &nbsp;&nbsp;&nbsp; 무료배송</span>
                                        </div>
                                        <br><br><br>
                                        <p style="text-align: right;">총 금액: &nbsp;&nbsp;&nbsp;<span id="total_price" style="font-size: 22px; font-weight: bold; color: red;"><?echo number_format(str_replace(",",'',$rowProductInfo['PRODUCT_PRICE_SALE']))?></span><span>원</span></p>
                                        <input name="product_no" type="number" value="<?echo $product_no?>" hidden>
                                        <input name="menu_no" type="number" value="<?echo $menu_no?>" hidden>
                                    </div>
                                    <? //관리자
                                    if($_SESSION['USER_TYPE'] == '0'){?>
                                        <p class="text-center buttons"><button type="button" id="btn_product_modify" class="btn btn-info" onclick="location.href='updateProduct.php?product_no=<?=$product_no?>'">상품수정</button> &nbsp; <button type="button" id="btn_product_delete" class="btn btn-warning">상품삭제</button></p>
                                    <?} else{ //일반사용자?>
                                        <p class="text-center buttons"><input id="btn_purchase" type="submit" class="btn btn-info" value="바로구매"><a href="javascript:addCart(<?echo $product_no?>);" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> 장바구니 담기</a></p>
                                    <?}?>
                                    <!--<a href="basket.php" class="btn btn-outline-primary"><i class="fa fa-heart"></i> 찜하기</a>-->
                                </div>
                            </form>
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
                            <div data-slider-id="1" class="owl-thumbs" style="height: 100px;">
                                <button onclick="changeImg('<?echo $repImgSrc?>')" class="owl-thumb-item"><img src="<?echo $repImgSrc?>" alt="" class="img-fluid"></button>
                                <?while($rowFileInfo = mysqli_fetch_array($resultFileInfo)){?>
                                    <button onclick="changeImg('<?echo $rowFileInfo['SAVE_PATH']?>')" class="owl-thumb-item"><img src="<?echo $rowFileInfo['SAVE_PATH']?>" alt="" class="img-fluid"></button>
                                <?}?>
                            </div>
                        </div>
                    </div>
                    <div id="details" class="box">
                        <div class="col-lg-12">
                            <ul id="pills-tab" role="tablist" class="nav nav-pills nav-justified">
                                <li class="nav-item" ><a id="detail-info-tab" data-toggle="pill" href="#detail-info" role="tab" aria-controls="detail-info" aria-selected="false" class="nav-link active">상세정보</a></li>
                                <li class="nav-item" ><a id="review-tab" data-toggle="pill" href="#review" role="tab" aria-controls="review" aria-selected="false" class="nav-link">후기</a></li>
                                <li class="nav-item" ><a id="qanda-tab" data-toggle="pill" href="#qanda" role="tab" aria-controls="qanda" aria-selected="false" class="nav-link">Q&A</a></li>
                            </ul>
                            <div id="pills-tabContent" class="tab-content">
                                <!-- detail-info-->
                                <div id="detail-info" role="tabpanel" aria-labelledby="detail-info-tab" class="tab-pane fade active show">
                                    <div>
                                        <?echo $rowProductInfo['DETAIL_INFO']?>
                                    </div>
                                    <br><br><br><br><br>
                                    <!-- 사이즈 정보 -->
                                    <div class="col-lg-12">
                                        <?if($rowProductInfo['FIRST_CATEGORY'] != '26'){?>
                                            <div class="table-size" style="text-align: center;">
                                                <div style="font-size: 17px; padding-bottom: 10px; text-align: left; padding-top: 10px; padding-left: 10px; padding-right: 10px">
                                                    <strong>사이즈정보</strong>
                                                </div>
                                                <table height="120" cellspacing="0" cellpadding="0" width="100%" border="0">
                                                    <tbody>
                                                    <tr align="center">
                                                        <td width="3%" style="border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid">&nbsp;</td>
                                                        <td height="58" width="10%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">
                                                            <div style="text-align: left" id="info_title"></div>
                                                        </td>
                                                        <!--상의-->
                                                        <?if($rowProductInfo['FIRST_CATEGORY'] == '2'){?>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">어깨</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">가슴</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">소매</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">암홀</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">총길이</td>
                                                            <!--아우터-->
                                                        <?} elseif($rowProductInfo['FIRST_CATEGORY'] == '3'){?>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">어깨</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">가슴</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">소매</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">총길이</td>
                                                            <!--바지-->
                                                        <?} elseif($rowProductInfo['FIRST_CATEGORY'] == '4'){?>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">허리단면</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">밑위</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">허벅지단면</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">밑단단면</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">총길이</td>
                                                            <!--모자-->
                                                        <?} elseif($rowProductInfo['FIRST_CATEGORY'] == '27'){?>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">둘레</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">총길이</td>
                                                            <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">높이</td>
                                                        <?}?>
                                                    </tr>
                                                    <!--상의-->
                                                    <?if($rowProductInfo['FIRST_CATEGORY'] == '2'){?>
                                                        <?while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                            <tr align="center">
                                                                <td width="3%" style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">&nbsp;</td>
                                                                <td style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                                    <div style="text-align: left"><?echo $rowSizeInfo['SIZE'];?></div>
                                                                </td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['TOP_SHOULDER_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['TOP_CHEST_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['TOP_ARM_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['TOP_ARMHOLE_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['TOP_TOTAL_LENGTH'];?></td>
                                                            </tr>
                                                        <?}?>
                                                        <!--아우터-->
                                                    <?} elseif($rowProductInfo['FIRST_CATEGORY'] == '3'){?>
                                                        <?while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                            <tr align="center">
                                                                <td width="3%" style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">&nbsp;</td>
                                                                <td style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                                    <div style="text-align: left"><?echo $rowSizeInfo['SIZE'];?></div>
                                                                </td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['OUTER_SHOULDER_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['OUTER__CHEST_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['OUTER_SLEEVE_LENGTH'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['OUTER_TOTAL_LENGTH'];?></td>
                                                            </tr>
                                                        <?}?>
                                                        <!--바지-->
                                                    <?} elseif($rowProductInfo['FIRST_CATEGORY'] == '4'){?>
                                                        <?while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                            <tr align="center">
                                                                <td width="3%" style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">&nbsp;</td>
                                                                <td style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                                    <div style="text-align: left"><?echo $rowSizeInfo['SIZE'];?></div>
                                                                </td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['BOTTOM_WAIST_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['BOTTOM_RISE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['BOTTOM_THIGH_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['BOTTOM_HEM_SIZE'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['BOTTOM_TOTAL_LENGTH'];?></td>
                                                            </tr>
                                                        <?}?>
                                                        <!--모자-->
                                                    <?} elseif($rowProductInfo['FIRST_CATEGORY'] == '27'){?>
                                                        <?while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                            <tr align="center">
                                                                <td width="3%" style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">&nbsp;</td>
                                                                <td style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                                    <div style="text-align: left"><?echo $rowSizeInfo['SIZE'];?></div>
                                                                </td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['HAT_ROUND'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['HAT_LENGTH'];?></td>
                                                                <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowSizeInfo['HAT_HEIGHT'];?></td>
                                                            </tr>
                                                        <?}?>
                                                    <?}?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?}?>
                                        <p>&nbsp;</p>
                                        <p style="margin-bottom: 7px; margin-top: 7px">&nbsp;</p>

                                        <!-- 모델정보-->
                                        <?if($rowProductInfo['FIRST_CATEGORY'] != '27'){?>
                                        <div class="table-size" style="text-align: center;">
                                            <div style="font-size: 17px; padding-bottom: 10px; text-align: left; padding-top: 10px; padding-left: 10px; padding-right: 10px">
                                                <strong>모델 착용정보</strong>
                                            </div>
                                            <table height="120" cellspacing="0" cellpadding="0" width="100%" border="0">
                                                <tbody>
                                                <tr align="center">
                                                    <td width="3%" style="border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid">&nbsp;</td>

                                                    <!--상의-->
                                                    <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">키(cm)</td>
                                                    <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">몸무게(kg)</td>
                                                    <td width="18%" style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(102,102,102) 2px solid; font-weight: 700">착용사이즈</td>
                                                </tr>
                                                <?while($rowModelInfo = mysqli_fetch_array($resultModelInfo)){?>
                                                    <tr align="center">
                                                        <td width="3%" style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">&nbsp;</td>
                                                        <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowModelInfo['MODEL_HEIGHT'];?></td>
                                                        <td style="font-size: 14px; border-bottom: rgb(232,232,232) 1px solid"><?echo $rowModelInfo['MODEL_WEIGHT'];?></td>
                                                        <td style="font-size: 15px; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                            <div style="text-align: center"><?echo $rowModelInfo['MODEL_SIZE'];?></div>
                                                        </td>
                                                    </tr>
                                                <?}?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?}?>

                                        <p>&nbsp;</p>
                                        <p style="margin-bottom: 7px; margin-top: 7px">&nbsp;</p>

                                        <!--상품정보-->
                                        <div class="table-size">
                                            <div style="font-size: 17px; padding-bottom: 10px; text-align: left; padding-top: 10px; padding-left: 10px; padding-right: 10px">
                                                <strong>상품정보</strong></div>
                                            <table cellspacing="0" cellpadding="0" width="1000" border="0">
                                                <tbody>
                                                <tr align="center">
                                                    <td width="2%"
                                                        style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        &nbsp;
                                                    </td>
                                                    <td height="58" width="12%"
                                                        style="font-size: 15px; border-top: rgb(102,102,102) 2px solid; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        <div style="text-align: left">소재</div>
                                                    </td>
                                                    <td width="2%"
                                                        style="font-size: 14px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(232,232,232) 1px solid">
                                                        &nbsp;
                                                    </td>
                                                    <td width="84%" align="left"
                                                        style="font-size: 14px; border-top: rgb(102,102,102) 2px solid; border-bottom: rgb(232,232,232) 1px solid">
                                                        <?echo $rowProductInfo['MATERIAL']?>
                                                    </td>
                                                </tr>
                                                <?if($rowProductInfo['FIRST_CATEGORY'] != '26' && $rowProductInfo['FIRST_CATEGORY'] != '27') { ?>
                                                <tr align="center">
                                                    <td width="2%"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        &nbsp;
                                                    </td>
                                                    <td height="105"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        <div style="text-align: left">정보</div>
                                                    </td>
                                                    <td width="2%"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid">
                                                        &nbsp;
                                                    </td>
                                                    <td align="left"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; -ms-word-break: keep-all">
                                                        신축성 : <?echo $rowProductInfo['ELASTICITY']?> / 두께감 : <?echo $rowProductInfo['THICKNESS']?>
                                                        <div style="text-align: left; -ms-word-break: keep-all">사이즈 : <?echo $rowProductInfo['FIT']?>
                                                            / 비침여부 : <?echo $rowProductInfo['REFLECTION']?>
                                                        </div>
                                                        <?if($rowProductInfo['FIRST_CATEGORY'] == '4'){?>
                                                            <div style="text-align: left; -ms-word-break: keep-all">촉감 :
                                                                <?echo $rowProductInfo['TOUCH']?>
                                                            </div>
                                                        <?}?>
                                                    </td>
                                                </tr>
                                                <tr align="center">
                                                    <td width="2%"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        &nbsp;
                                                    </td>
                                                    <td style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        <div style="text-align: left">계절</div>
                                                    </td>
                                                    <td width="2%"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid">
                                                        &nbsp;
                                                    </td>
                                                    <td align="left"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; -ms-word-break: keep-all">
                                                        <?echo $rowProductInfo['SEASON']?>
                                                    </td>
                                                </tr>
                                                <?}?>
                                                <tr align="center">
                                                    <td width="2%"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        &nbsp;
                                                    </td>
                                                    <td height="65"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700; -ms-word-break: keep-all">
                                                        <div style="text-align: left">세탁방법 및</div>
                                                        <div style="text-align: left; -ms-word-break: keep-all">주의사항
                                                        </div>
                                                    </td>
                                                    <td width="2%"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid">
                                                        &nbsp;
                                                    </td>
                                                    <td align="left"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; -ms-word-break: keep-all">
                                                        <?echo $rowProductInfo['CLEANING_METHOD']?>
                                                    </td>
                                                </tr>
                                                <tr align="center"></tr>
                                                <tr align="center">
                                                    <td width="2%"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700">
                                                        &nbsp;
                                                    </td>
                                                    <td height="65"
                                                        style="font-size: 15px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid; font-weight: 700; -ms-word-break: keep-all">
                                                        <div style="text-align: left">제조국 및</div>
                                                        <div style="text-align: left; -ms-word-break: keep-all">제조자
                                                        </div>
                                                    </td>
                                                    <td width="2%"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid">
                                                        &nbsp;
                                                    </td>
                                                    <td align="left"
                                                        style="font-size: 14px; vertical-align: middle; border-bottom: rgb(232,232,232) 1px solid">
                                                        <?echo $rowProductInfo['COUNTRY_OF_MANUFACTURER']?>  / <?echo $rowProductInfo['MANUFACTURER']?>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p>&nbsp;</p>
                                        <p style="margin-bottom: 7px; margin-top: 7px">&nbsp;</p>
                                    </div>
                                </div>
                                <!-- /detail-info-->

                                <!-- review -->
                                <div id="review" role="tabpanel" aria-labelledby="review-tab" class="tab-pane fade">
                                    <div id="board" class="col-lg-12">
                                        <div id="contact" class="box">
                                            <div id="header_photo_review" class="d-flex justify-content-between">
                                                <h3>포토후기(<span id="cnt_photo_review"></span>건)</h3>
                                                <?if($rowOrderInfo[0] != null) {?>
                                                <button id="btn_review" class="btn btn-info" data-toggle="modal" data-target="#photo-review-modal">후기 작성</button>
                                                <?}?>
                                            </div>
                                            <hr>
                                            <div id="photo-review-modal" tabindex="-1" role="dialog" aria-labelledby="Photo-Reivew" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">후기 작성</h4>
                                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <tbody>
                                                                <tr>
                                                                    <td bgcolor="white">
                                                                        <table class="table">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>상품 선택</td>
                                                                                <td>
                                                                                    <select class="form-control"  name="photo_review_selected_product" id="photo_review_selected_product">
                                                                                        <?while($rowOrderListInfo = mysqli_fetch_array($resultOrderListInfo)){?>
                                                                                        <option value="<?=$rowOrderListInfo['SEQ']?>"><?=$rowOrderListInfo['PRODUCT_COLOR'].'/'.$rowOrderListInfo['PRODUCT_SIZE']?></option>
                                                                                        <?}?>
                                                                                    </select>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>제목</td>
                                                                                <td><input class="form-control py-4" type="text" name="photo_review_title" id="photo_review_title" maxlength="1000" value=""></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>내용</td>
                                                                                <td>
                                                                                    <textarea name="photo_review_contents" id="photo_review_contents" class="nse_content" style="width: 100%; height: 100px;"></textarea>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>평가</td>
                                                                                <td>
                                                                                    <table style="width: 100%;">
                                                                                        <tr>
                                                                                            <td>사이즈</td>
                                                                                            <td>밝기</td>
                                                                                            <td>색감</td>
                                                                                            <?if($rowProductInfo['FIRST_CATEGORY'] != 26 && $rowProductInfo['FIRST_CATEGORY'] != 27){?>
                                                                                                <td>무게감</td>
                                                                                            <?}?>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <select class="form-control"  name="photo_review_evaluation_size" id="photo_review_evaluation_size">
                                                                                                    <option value="작아요">작아요</option>
                                                                                                    <option value="보통이에요" selected>보통이에요</option>
                                                                                                    <option value="커요">커요</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td>
                                                                                                <select class="form-control"  name="photo_review_evaluation_lightness" id="photo_review_evaluation_lightness">
                                                                                                    <option value="어두워요">어두워요</option>
                                                                                                    <option value="보통이에요"selected>보통이에요</option>
                                                                                                    <option value="밝아요">밝아요</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <td>
                                                                                                <select class="form-control"  name="photo_review_evaluation_color" id="photo_review_evaluation_color">
                                                                                                    <option value="흐려요">흐려요</option>
                                                                                                    <option value="보통이에요" selected>보통이에요</option>
                                                                                                    <option value="선명해요">선명해요</option>
                                                                                                </select>
                                                                                            </td>
                                                                                            <!--상의, 아우터, 하의일 경우에만 나오기-->
                                                                                            <?if($rowProductInfo['FIRST_CATEGORY'] != 26 && $rowProductInfo['FIRST_CATEGORY'] != 27){?>
                                                                                                <td>
                                                                                                    <select class="form-control"  name="photo_review_evaluation_thickness" id="photo_review_evaluation_thickness">
                                                                                                        <option value="얇아요">얇아요</option>
                                                                                                        <option value="보통이에요" selected>보통이에요</option>
                                                                                                        <option value="두꺼워요">두꺼워요</option>
                                                                                                    </select>
                                                                                                </td>
                                                                                            <?}?>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>별점</td>
                                                                                <td>
                                                                                    <div id="photo_review_raty"></div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>상품이미지</td>
                                                                                <td>
                                                                                    <input type="file" class="form-control" name="photo_review_file_photo_review" id="photo_review_file_photo_review">
                                                                                    <div style="margin-top: 10px;">
                                                                                        <img id="img_photo_review" width="200px;">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <button id="btn_write_photo_review_compl" class="btn btn-info" style="float: right;">작성완료</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- 후기 수정 모달-->
                                            <div id="photo-review-modify-modal" tabindex="-1" role="dialog" aria-labelledby="Photo-Reivew" aria-hidden="true" class="modal fade">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">후기 수정</h4>
                                                            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <table class="table">
                                                                <tbody>
                                                                <tr>
                                                                    <td bgcolor="white">
                                                                        <table class="table">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>상품 선택<input hidden id="photo_review_modify_seq"><input hidden id="photo_review_modify_cnt"><input hidden id="photo_review_modify_type"></td>
                                                                                <td>
                                                                                    <div id="photo_review_modify_product"></div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>제목</td>
                                                                                <td><input class="form-control py-4" type="text" name="photo_review_title" id="photo_review_modify_title" maxlength="1000" value=""></td>
                                                                            </tr>

                                                                            <tr>
                                                                                <td>내용</td>
                                                                                <td>
                                                                                    <textarea name="photo_review_contents" id="photo_review_modify_contents" class="nse_content" style="width: 100%; height: 100px;"></textarea>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>평가</td>
                                                                                <td>
                                                                                    <table style="width: 100%;">
                                                                                        <tr>
                                                                                            <td>사이즈</td>
                                                                                            <td>밝기</td>
                                                                                            <td>색감</td>
                                                                                            <?if($rowProductInfo['FIRST_CATEGORY'] != 26 && $rowProductInfo['FIRST_CATEGORY'] != 27){?>
                                                                                                <td>무게감</td>
                                                                                            <?}?>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <select class="form-control"  name="photo_review_modify_evaluation_size" id="photo_review_modify_evaluation_size">
                                                                                                </select>
                                                                                            </td>
                                                                                            <td>
                                                                                                <select class="form-control"  name="photo_review_modify_evaluation_lightness" id="photo_review_evaluation_modify_lightness">
                                                                                                </select>
                                                                                            </td>
                                                                                            <td>
                                                                                                <select class="form-control"  name="photo_review_evaluation_modify_color" id="photo_review_evaluation_modify_color">
                                                                                                </select>
                                                                                            </td>
                                                                                            <!--상의, 아우터, 하의일 경우에만 나오기-->
                                                                                            <?if($rowProductInfo['FIRST_CATEGORY'] != 26 && $rowProductInfo['FIRST_CATEGORY'] != 27){?>
                                                                                                <td>
                                                                                                    <select class="form-control"  name="photo_review_modify_evaluation_thickness" id="photo_review_modify_evaluation_thickness">
                                                                                                    </select>
                                                                                                </td>
                                                                                            <?}?>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>별점</td>
                                                                                <td>
                                                                                    <div id="photo_review_modify_raty"></div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr id="photo_review_modify_product_img">
                                                                                <td>상품이미지</td>
                                                                                <td>
                                                                                    <input type="file" name="photo_review_modify_file_photo_review" id="photo_review_modify_file_photo_review" accept="image/*" style="display: none;">
                                                                                    <label for="photo_review_modify_file_photo_review" class="btn btn-info fileBtn">파일선택</label>
                                                                                    <span style="width: 50%; display: inline-block;" id="fileName" class="form-control">파일 변경하기</span>
                                                                                    <div style="margin-top: 10px;">
                                                                                        <img id="photo_review_modify_img_photo_review" width="200px;">
                                                                                    </div>
                                                                                </td>
                                                                                <script>
                                                                                    document.getElementById('photo_review_modify_file_photo_review').addEventListener('change', function(){
                                                                                        let filename = document.getElementById('fileName');
                                                                                        if(this.files[0] == undefined){
                                                                                            filename.innerText = '선택된 파일없음';
                                                                                            return;
                                                                                        }
                                                                                        filename.innerText = this.files[0].name;
                                                                                    });
                                                                                </script>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>상품이미지2</td>
                                                                                <td>
                                                                                    <input type="file" name="file" accept="image/*" id="bizFile" style="display: none;">
                                                                                    <label for="bizFile" class="btn btn-info fileBtn">파일선택</label>
                                                                                    <span id="fileName">선택된 파일없음</span>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                            <button id="btn_modify_photo_review_compl" class="btn btn-info" style="float: right;">수정완료</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="accordion">
                                            </div>
                                            <!-- /.accordion-->
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination_photo_review" style="justify-content: center;">
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>

                                    <div id="board" class="col-lg-12">
                                        <div id="contact" class="box">
                                            <div id="header_review" class="d-flex justify-content-between">
                                                <h3>일반후기(<span id="cnt_review"></span>건)</h3>
                                            </div>
                                            <hr>
                                            <div id="accordion_review">
                                            </div>
                                            <!-- /.accordion-->
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination_review" style="justify-content: center;">
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <!-- /review -->

                                <!-- q&a -->
                                <div id="qanda" role="tabpanel" aria-labelledby="qanda-tab" class="tab-pane fade">
                                    <div id="board" class="col-lg-12">
                                        <div class="container">
                                            <div id="contact" class="box">
                                                <div id="header_review" class="d-flex justify-content-between">
                                                    <h3>Q&A(<span id="cnt_qanda"></span>건)</h3>
                                                    <button id="btn_qanda" class="btn btn-info" data-toggle="modal" data-target="#qanda-modal">Q&A 작성</button>
                                                </div>
                                                <div id="qanda-modal" tabindex="-1" role="dialog" aria-labelledby="Photo-Reivew" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Q&A 작성</h4>
                                                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td bgcolor="white">
                                                                            <table class="table">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td><input id="secret_yn" type="checkbox">&nbsp; 비밀글 여부</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td style="width: 20%;">질문유형</td>
                                                                                    <td>
                                                                                        <select class="form-control"  name="question_type" id="question_type">
                                                                                            <option value="사이즈">사이즈</option>
                                                                                            <option value="재입고">재입고</option>
                                                                                            <option value="배송">배송</option>
                                                                                            <option value="기타문의">기타문의</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>제목</td>
                                                                                    <td><input class="form-control py-4" type="text" name="qanda_title" id="qanda_title" maxlength="200" placeholder="제목을 입력하세요."></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>내용</td>
                                                                                    <td>
                                                                                        <textarea name="qanda_contents" id="qanda_contents" class="nse_content" style="width: 100%; height: 100px;" placeholder="내용을 입력하세요."></textarea>
                                                                                    </td>
                                                                                </tr>

                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                                <button id="btn_qanda_compl" class="btn btn-info" style="float: right;">작성완료</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Q&A 수정-->
                                                <div id="qanda-modify-modal" tabindex="-1" role="dialog" aria-labelledby="Photo-Reivew" aria-hidden="true" class="modal fade">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Q&A 수정</h4>
                                                                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td bgcolor="white">
                                                                            <table class="table">
                                                                                <tbody>
                                                                                <!--<tr>
                                                                                    <td><input id="qanda_modify_secret_yn" type="checkbox" readonly>&nbsp; 비밀글 여부</td>
                                                                                </tr>-->
                                                                                <tr>
                                                                                    <td style="width: 20%;">질문유형<input hidden id="qanda_modify_seq"><input hidden id="qanda_modify_cnt"></td>
                                                                                    <td>
                                                                                        <select class="form-control"  name="qanda_modify_question_type" id="qanda_modify_question_type">
                                                                                            <option value="사이즈">사이즈</option>
                                                                                            <option value="재입고">재입고</option>
                                                                                            <option value="배송">배송</option>
                                                                                            <option value="기타문의">기타문의</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>제목</td>
                                                                                    <td><input class="form-control py-4" type="text" name="qanda_modify_title" id="qanda_modify_title" maxlength="200" placeholder="제목을 입력하세요."></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>내용</td>
                                                                                    <td>
                                                                                        <textarea name="qanda_modify_contents" id="qanda_modify_contents" class="nse_content" style="width: 100%; height: 100px;" placeholder="내용을 입력하세요."></textarea>
                                                                                    </td>
                                                                                </tr>

                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                                <button id="btn_qanda_modify_compl" class="btn btn-info" style="float: right;">수정완료</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- //Q&A 수정-->

                                                <hr>
                                                <div id="accordion_qanda"></div>
                                                <!-- /.accordion-->
                                                <nav aria-label="Page navigation">
                                                    <ul class="pagination pagination_qanda" style="justify-content: center;"></ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /q&a -->
                            </div>
                    </div>
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
        $(document).ready(function(){
            // 모든 자원 다운로드 시 포토후기 가져오기
            searchPhotoReview(1);
            searchReview(1);
            searchQandA(1);
        });

        // 별점정보
        let sum = <?=$sum?>;
        let cntOfReviewer = <?=$cntOfReviewer?>;

        let starScore = sum/cntOfReviewer


        // menu_no에 따라 menu_title, cat_second, cat_third 변경하기
        menuNo = '<?echo $menu_no?>';
        productNo = '<?echo $product_no?>';

        // 선택된 카테고리 active
        // href$="val" : href의 속성값이 val로 끝나는 요소
        $('.category-menu li a[href$='+ menuNo +']').each(function (index, item){
            if(index == 0) {
                console.log($(item));
                console.log("1111");
                $(item).addClass("active");
            }
        });

        function changeImg(src){
            $('#img_rep').attr("src",src);
        }

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
                $('#menu_title').text("티셔츠");
                $('#cat_second').text("상의");
                $('#cat_third').text("티셔츠");
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
                $('#menu_title').text("청바지");
                $('#cat_second').text("하의");
                $('#cat_third').text("청바지");
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
                $('#menu_title').text("야구모자/스냅백");
                $('#cat_second').text("모자");
                $('#cat_third').text("야구 모자/스냅백");
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
                $('#menu_title').text("기타모자");
                $('#cat_second').text("모자");
                $('#cat_third').text("기타모자");
                break;
        }

        // 메뉴타이틀에 따라 해당하는 메뉴의 클래스에 show를 추가
        switch ($('#cat_second').text()){
            case "상의":
                $('#collapse0').addClass("show");
                $('#info_title').text("상의");
                break;
            case "아우터":
                $('#collapse1').addClass("show");
                $('#info_title').text("아우터");
                break;
            case "바지":
                $('#collapse2').addClass("show");
                $('#info_title').text("바지");
                break;
            case "신발":
                $('#collapse3').addClass("show");
                $('#info_title').text("신발");
                break;
            case "모자":
                $('#collapse4').addClass("show");
                $('#info_title').text("모자");
                break;
        }


        let origin_price = <?echo str_replace(",",'',$rowProductInfo['PRODUCT_PRICE_SALE'])?>;

        console.log(origin_price);

        // 수량이 변경 될 때 마다 총 금액 변경해줌
        $('#product_number').on("change", function(){
            if($(this).val() > 100 ){
                $(this).val(100);
            }
            $('#total_price').text(String($('#product_number').val() * parseInt(origin_price)).replace(/\B(?=(\d{3})+(?!\d))/g, ","));
        });


        // 장바구니 추가
        function addCart(product_no){
            /*console.log(product_no);
            console.log($('#option1').val());
            console.log($('#option2').val());
            console.log($('#product_number').val());*/
            let loginId = '<?echo $login_id?>';

            // 색상 선택
            if(loginId == ''){
                alert("장바구니에 상품을 담으려면 로그인해주세요!");
                return false;
            }

            // 색상 선택
            if($('#option1').val() == ''){
                alert("색상을 선택해주세요.");
                return false;
            }

            // 사이즈 선택
            if($('#option2').val() == ''){
                alert("사이즈를 선택해주세요.");
                return false;
            }

            if(confirm("장바구니에 추가하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/cart/writeCartCompl.php',
                    data: {
                        product_no: product_no,
                        color: $('#option1').val(),
                        size: $('#option2').val(),
                        quantity: $('#product_number').val()
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            alert("장바구니에 추가했습니다.");
                        } else if(json.result == 'duplicate'){
                            alert("이미 장바구니에 추가되어있습니다.");
                        } else{
                            alert("추가 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        }

        // 선택하는 생상에 따라 사이즈 다르게 하기
        $('#option1').on("change", function(){
            /*$.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/cart/writeCartCompl.php',
                data: {
                    product_no: product_no,
                    color: $('#option1').val(),
                    size: $('#option2').val(),
                    quantity: $('#product_number').val()
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        alert("장바구니에 추가했습니다.");
                    } else if(json.result == 'duplicate'){
                        alert("이미 장바구니에 추가되어있습니다.");
                    } else{
                        alert("추가 실패했습니다.");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });*/
        });

        // 바로구매 버튼 클릭 시 유효성 검증
        function verifyBeforePurchase(){
            let loginId = '<?echo $login_id?>';

            // 색상 선택
            if(loginId == ''){
                alert("상품구매를 위해 로그인해주세요!");
                return false;
            }

            // 색상 선택
            if($('#option1').val() == ''){
                alert("색상을 선택해주세요.");
                return false;
            }

            // 사이즈 선택
            if($('#option2').val() == ''){
                alert("사이즈를 선택해주세요.");
                return false;
            }

            // 수량 선택
            if($('#product_number').val() < 1){
                alert("수량은 1개이상 선택해주셔야 합니다.");
                return false;
            }
            return confirm("해당 상품을 구매하시겠습니까?");
        }

        // 포토상품평 별점 매기기
        let cntPhotoReview;
        let cntReview;

        /*for(let i = 0; i < cntPhotoReview; i++){
            $('#raty_'+i).raty({half : true, readOnly: true, score: $('#star_score_'+i).val()});
        }*/

        $('#photo_review_raty').raty({half : true, readOnly: false});
        $('#raty_photo_review').raty({half : true, readOnly: false});
        $('#raty_review').raty({half : true, readOnly: false});
        $('#raty_product').raty({half : true, readOnly: true, score:(starScore > 0 ? starScore : 0)});

        // 상품이미지
        $('#photo_review_file_photo_review').on("change", function(e){
            let files = e.target.files;
            let fileArr = Array.prototype.slice.call(files);

            fileArr.forEach(function(file){
                if(!file.type.match("image.*")) {
                    alert("확장자는 이미지 확장자만 가능합니다.");
                    return;
                }

                let reader = new FileReader();

                reader.onload = function(e) {
                    $('#img_photo_review').attr("src", e.target.result);
                }
                reader.readAsDataURL(file);

            });
        });



        // 상품이미지 수정
        $('#photo_review_modify_file_photo_review').on("change", function(e) {
            let files = e.target.files;
            let fileArr = Array.prototype.slice.call(files);

            fileArr.forEach(function (file) {
                if (!file.type.match("image.*")) {
                    alert("확장자는 이미지 확장자만 가능합니다.");
                    return;
                }

                let reader = new FileReader();

                reader.onload = function (e) {
                    $('#photo_review_modify_img_photo_review').attr("src", e.target.result);
                }
                reader.readAsDataURL(file);

            });
        });



        // 리뷰 작성완료
        $('#btn_write_photo_review_compl').on("click", function(){

            /*console.log($('#photo_review_title').val());
            console.log($('#photo_review_contents').val());
            console.log($('#photo_review_evaluation_size').val());
            console.log($('#photo_review_evaluation_color').val());
            console.log($('#photo_review_evaluation_lightness').val());
            console.log($("#photo_review_raty").children('input').val());
            console.log($('#photo_review_file_photo_review')[0].files[0]);*/

            let formData = new FormData();
            if($('#photo_review_file_photo_review').val() != ''){
                formData.append("type", "0"); // 포토후기
            } else{
                formData.append("type", "1"); // 일반후기
            }
            formData.append("photo_review_selected_product", $('#photo_review_selected_product').val());
            formData.append("product_no", <?echo $product_no?>);
            formData.append("photo_review_title", $("#photo_review_title").val());
            formData.append("photo_review_contents", $("#photo_review_contents").val());
            formData.append("photo_review_evaluation_size", $("#photo_review_evaluation_size").val());
            formData.append("photo_review_evaluation_color", $("#photo_review_evaluation_color").val());
            formData.append("photo_review_evaluation_lightness", $("#photo_review_evaluation_lightness").val());
            formData.append("photo_review_evaluation_thickness", $("#photo_review_evaluation_thickness").val());
            formData.append("photo_review_raty", $("#photo_review_raty").children('input').val());
            formData.append("photo_review_file", $('#photo_review_file_photo_review')[0].files[0]);


            // 리뷰 작성완료
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/review/writeReviewCompl.php',
                processData: false, // 필수
                contentType: false, // 필수
                data: formData,
                success: function (json) {
                    if(json.result == 'ok'){
                        // 모달 창 닫기
                        $('#photo-review-modal').modal("hide");

                        // 포토리뷰
                        if($('#photo_review_file_photo_review').val() != ''){
                            let photoReview =
                                '<div id="photo_review_'+cntPhotoReview+'" class="card border-primary mb-3 photo-review">'
                                +'<div id="heading_photo_review'+cntPhotoReview+'" class="card-header p-0 border-0" style="color: white">'
                                +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_photo_review'+cntPhotoReview+'" aria-expanded="false" aria-controls="collapse_photo_review'+cntPhotoReview+'" class="btn btn-light d-block text-left rounded-0">'
                                +'<span class="raty" id="raty_'+cntPhotoReview+'" style="margin-right: 50px;"><input id="star_score_'+cntPhotoReview+'"hidden value="'+$("#photo_review_raty").children('input').val()+'"></span>'
                                +'<span id="photo_review_title_'+cntPhotoReview+'" >'+$("#photo_review_title").val()+'</span>'
                                +'<span id="photo_review_name_'+cntPhotoReview+'"  style="float: right; color: darkgrey" ><?=$user_name?></span>'
                                +'</a>'
                                +'</h4>'
                                +'</div>'
                                +'<div id="collapse_photo_review'+cntPhotoReview+'" aria-labelledby="heading_photo_review'+cntPhotoReview+' data-parent=#accordion" class="collapse show" >'
                                +'<div class="card-body">'
                                +'<p id="photo_review_contents_'+cntPhotoReview+'" >'+$("#photo_review_contents").val()+'</p>'
                                +'<img id="photo_review_img_'+cntPhotoReview+'" style="text-align: center; align:center;" width="300px;" src="'+json.save_path+'">'
                                +'<p style="color: darkgrey">'+json.color+'색상/'+json.size+'사이즈 구매</p>'
                                +'<div>'
                                +'<table class="table">'
                                +'<tr>'
                                +'<td>사이즈: <span id="photo_review_eval_size_'+cntPhotoReview+'" >'+$("#photo_review_evaluation_size").val()+'</span></td>'
                                +'<td>밝기: <span id="photo_review_eval_lightness_'+cntPhotoReview+'" >'+$("#photo_review_evaluation_lightness").val()+'</span></td>'
                                +'<td>색감: <span id="photo_review_eval_color_'+cntPhotoReview+'" >'+$("#photo_review_evaluation_color").val()+'</span></td>';
                            if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                photoReview += '<td>무게감: <span id="photo_review_eval_thickness_'+cntPhotoReview+'" >'+$("#photo_review_evaluation_thickness").val()+'</span></td>';
                            }
                            photoReview += '</tr>'
                                +'</table>'
                                +'</div>'
                                +'</div>'
                                +'<div class="navbar-buttons" align="right" style="display: flex;">'
                                +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="javascript:modifyReview('+json.seq +' , ' + cntPhotoReview +' ,'+ 0 +')" class="btn btn-primary navbar-btn">수정</a></div>'
                                +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="javascript:deleteReview('+json.seq +' , ' + cntPhotoReview +' ,'+ 0 +')" class="btn btn-primary navbar-btn">삭제</a></div>'
                                +'</div>'
                                +'</div>'
                                +'</div>';

                            // 첫구매라면
                            if(cntPhotoReview == 0){
                                $('#accordion').append(photoReview);
                            } else{
                                $('#photo_review_0').before(photoReview);
                            }

                            $('#raty_'+cntPhotoReview).raty({half : true, readOnly: true, score: $("#photo_review_raty").children('input').val()});

                            cntPhotoReview++;
                            totalCntPhotoReview++;
                            $('#cnt_photo_review').text(totalCntPhotoReview);

                            // 상품 전체 별점 변경
                            sum = sum + parseInt($("#photo_review_raty").children('input').val());
                            cntOfReviewer++;
                            starScore = sum/cntOfReviewer;

                            $('#raty_product').children('img').remove();
                            $('#raty_product').raty({half : true, readOnly: true, score: starScore});

                            $('#cnt_reviewer').text(' ' + cntOfReviewer + '건');

                        } else{ //리뷰
                            let review =
                                '<div id="review_'+cntReview+'"class="card border-primary mb-3 photo-review">'
                                +'<div id="heading_review'+cntReview+'" class="card-header p-0 border-0" style="color: white">'
                                +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_review'+cntReview+'" aria-expanded="false" aria-controls="collapse_review'+cntReview+'" class="btn btn-light d-block text-left rounded-0">'
                                +'<span class="raty" id="raty_review_'+cntReview+'" style="margin-right: 50px;"><input id="star_score_'+cntReview+'"hidden value="'+$("#photo_review_raty").children('input').val()+'"></span>'
                                +'<span id="review_title_'+cntReview+'" >'+$("#photo_review_title").val()+'</span>'
                                +'<span  id="review_name_'+cntReview+'" style="float: right; color: darkgrey" ><?=$user_name?></span>'
                                +'</a>'
                                +'</h4>'
                                +'</div>'
                                +'<div id="collapse_review'+cntReview+'" aria-labelledby="heading_review'+cntReview+' data-parent=#accordion" class="collapse show" >'
                                +'<div class="card-body">'
                                +'<p id="review_contents_'+cntReview+'" >'+$("#photo_review_contents").val()+'</p>'
                                +'<p style="color: darkgrey">'+json.color+'색상/'+json.size+'사이즈 구매</p>'
                                +'<div>'
                                +'<table class="table">'
                                +'<tr>'
                                +'<td>사이즈: <span id="review_eval_size_'+cntReview+'" >'+$("#photo_review_evaluation_size").val()+'</span></td>'
                                +'<td>밝기: <span id="review_eval_lightness_'+cntReview+'" >'+$("#photo_review_evaluation_lightness").val()+'</span></td>'
                                +'<td>색감: <span id="review_eval_color_'+cntReview+'" >'+$("#photo_review_evaluation_color").val()+'</span></td>';
                            if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                review += '<td>무게감: <span id="review_eval_thickness_'+cntReview+'" >'+$("#photo_review_evaluation_thickness").val()+'</span></td>';
                            }
                            review += '</tr>'
                                +'</table>'
                                +'</div>'
                                +'</div>'
                                +'<div class="navbar-buttons" align="right" style="display: flex;">'
                                +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="javascript:modifyReview('+json.seq +' , ' + cntReview +' ,'+ 1 +')" class="btn btn-primary navbar-btn">수정</a></div>'
                                +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="javascript:deleteReview('+json.seq +' , ' + cntReview +' ,'+ 1 +')" class="btn btn-primary navbar-btn">삭제</a></div>'
                                +'</div>'
                                +'</div>'
                                +'</div>';

                            // 첫구매라면
                            if(cntReview == 0){
                                $('#accordion_review').append(review);
                            } else{
                                $('#review_0').before(review);
                            }

                            console.log(cntReview);
                            console.log($("#photo_review_raty").children('input').val());

                            $('#raty_review_'+cntReview).raty({half : true, readOnly: true, score: $("#photo_review_raty").children('input').val()});

                            // 모달에 작성한 데이터 제거
                            $('#photo_review_selected_product').val('');
                            $('#photo_review_title').val('');
                            $('#photo_review_contents').val('');
                            $('#photo_review_evaluation_size').val('');
                            $('#photo_review_evaluation_color').val('');
                            $('#photo_review_evaluation_lightness').val('');
                            $('#photo_review_evaluation_thickness').val('');
                            $('#photo_review_file_photo_review').val('');

                            cntReview++;
                            totalCntReview++;
                            $('#cnt_review').text(totalCntReview);

                            // 상품 전체 별점 변경
                            sum = sum + parseInt($("#photo_review_raty").children('input').val());
                            cntOfReviewer++;

                            starScore = sum/cntOfReviewer;

                            console.log(sum);
                            console.log(cntOfReviewer);
                            console.log(starScore);

                            $('#raty_product').children('img').remove();
                            $('#raty_product').raty({half : true, readOnly: true, score: starScore});

                            $('#cnt_reviewer').text(' ' + cntOfReviewer + '건');
                        }
                    }
                    // 리뷰 작성 후 남은 리뷰가능상품의 갯수가 없다면 후기 작성 버튼을 숨긴다.
                    if(<?=$rowOrderInfo[0]?> - 1 == 0){
                        $('#btn_review').hide();
                    }
                },
                error: function () {
                }
            });
        });


        // 포토리뷰 가져오기
        function searchPhotoReview(pageNo){
            let page_no = pageNo;

            if(page_no == '' || page_no == null || page_no == undefined){
                page_no = 1;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/review/selectPhotoReviewCompl.php',
                data: {
                    page_no: page_no,
                    product_no: <?=$product_no?>
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        //alert("조회를 완료헀습니다.");
                        // 가져온 데이터가 있다면 모든 행 삭제.
                        if(json.seq.length != 0){
                            //모든 행 삭제
                            $('#accordion > .photo-review').remove();
                            // 페이징 삭제
                            $('.pagination_photo_review > li').remove();
                        }

                        /* 전체 데이터 뿌리기
                        for(let i = 0; i < json.seq.length; i++){
                            let space = '';

                            for(let j= 0; j < (json.depth[i] - 1) * 3; j++){
                                space += '&nbsp';
                            }
                            if(space != ''){
                                space += '┖';
                            }
                            $('#free_board_post_tb > tbody:last').append(
                                '<tr style="cursor: pointer;" onclick="location.href=\'detailFreeBoard.php?board_no=3&seq=' + json.seq[i] + '\'">'
                                +    '<td>'+json.seq[i]+'</td>'
                                +    '<td style="text-align: left;">'
                                +         '<a href="detailFreeBoard.php?board_no=3&seq='+json.seq[i]+'">'
                                +             space + '<u>' + json.title[i] + '</u>'
                                +         '</a>' + ' [' + json.commentCnt[i] + ']'
                                +     '</td>'
                                +     '<td>'+ json.name[i] +'</td>'
                                +     '<td>'+ json.creDatetime[i].substring(0,10) +'</td>'
                                +     '<td>'+ json.cnt[i] +'</td>'
                                + '</tr>');
                        }*/

                        // 포토리뷰 갯수
                        cntPhotoReview = json.seq.length;
                        totalCntPhotoReview = json.total_count_of_post;
                        $('#cnt_photo_review').text(totalCntPhotoReview);

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let photoReview =
                                '<div id="photo_review_'+i+'" class="card border-primary mb-3 photo-review">'
                                    +'<div id="heading_photo_review'+i+'" class="card-header p-0 border-0" style="color: white">'
                                        +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_photo_review'+i+'" aria-expanded="false" aria-controls="collapse_photo_review'+i+'" class="btn btn-light d-block text-left rounded-0">'
                                            +'<span class="raty" id="raty_'+i+'" style="margin-right: 50px;"><input id="star_score_'+i+'"hidden value="'+json.starScore[i]+'"></span>'
                                            +'<span id="photo_review_title_'+i+'">'+json.title[i]+'</span>'
                                            +'<span id="photo_review_name_'+i+'" style="float: right; color: darkgrey" >'+json.name[i]+'</span>'
                                        +'</a>'
                                        +'</h4>'
                                    +'</div>'
                                    +'<div id="collapse_photo_review'+i+'" aria-labelledby="heading_photo_review'+i+' data-parent=#accordion" class="collapse show" >'
                                        +'<div class="card-body">'
                                            +'<p id="photo_review_contents_'+i+'">'+json.contents[i]+'</p>'
                                            +'<img id="photo_review_img_'+i+'" style="text-align: center; align:center;" width="300px;" src="'+json.savePath[i]+'">'
                                            +'<p style="color: darkgrey">'+json.productColor[i]+'색상/'+json.productSize[i]+'사이즈 구매</p>'
                                            +'<div>'
                                                +'<table class="table">'
                                                    +'<tr>'
                                                        +'<td>사이즈: <span id="photo_review_eval_size_'+i+'">'+json.evalSize[i]+'</span></td>'
                                                        +'<td>밝기: <span id="photo_review_eval_lightness_'+i+'">'+json.evalLightness[i]+'</span></td>'
                                                        +'<td>색감: <span id="photo_review_eval_color_'+i+'">'+json.evalColor[i]+'</span></td>';
                                                        if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                                            photoReview += '<td>무게감: <span id="photo_review_eval_thickness_'+i+'">'+json.evalThickness[i]+'</span></td>';
                                                        }
                                            photoReview += '</tr>'
                                                +'</table>'
                                            +'</div>'
                                        +'</div>';
                                        if('<?=$login_id?>' == json.writer[i]){
                                            photoReview += '<div class="navbar-buttons" align="right" style="display: flex;">'
                                                                +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="javascript:modifyReview('+json.seq[i]+' , '+ i +' ,'+ 0 +')" class="btn btn-primary navbar-btn">수정</a></div>'
                                                                +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="javascript:deleteReview('+json.seq[i]+' , '+ i +' ,'+ 0 +')" class="btn btn-primary navbar-btn">삭제</a></div>'
                                                         +'</div>';
                                        }
                            photoReview += '</div>'
                                +'</div>';
                            $('#accordion').append(photoReview);
                            $('#raty_'+i).raty({half : true, readOnly: true, score: json.starScore[i]});
                        }

                        /* 페이징 시작 */
                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination_photo_review').append('<li class="page-item"><a href="javascript:searchPhotoReview(1)"class="page-link">' + '<<' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination_photo_review').append('<li class="page-item"><a href="javascript:searchPhotoReview('+ (parseInt(json.start_page_num_of_block) - 1) + ')" class="page-link">' + '<' + '</a></li>');
                        }

                        for(let i = parseInt(json.start_page_num_of_block); i <= parseInt(json.end_page_num_of_block); i++){
                            if(page_no != i){
                                $('.pagination_photo_review').append('<li class="page-item"><a href="javascript:searchPhotoReview('+i + ')" class="page-link">' + i + '</a></li>');
                            } else{
                                $('.pagination_photo_review').append('<li class="page-item active"><a href="javascript:searchPhotoReview('+i + ')" class="page-link">' + i + '</a></li>');
                            }
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination_photo_review').append('<li class="page-item"><a href="javascript:searchPhotoReview('+ (parseInt(json.end_page_num_of_block) + 1) + ')"class="page-link">' + '>' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination_photo_review').append('<li class="page-item"><a href="javascript:searchPhotoReview('+ parseInt(json.total_count_of_page) + ')" class="page-link">' + '>>' + '</a></li>');
                        }
                        /* 페이징 종료 */
                    } else {
                        alert("조회에 실패했습니다!");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        }

        // 일반 리뷰 가져오기
        function searchReview(pageNo){
            let page_no = pageNo;

            if(page_no == '' || page_no == null || page_no == undefined){
                page_no = 1;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/review/selectReviewCompl.php',
                data: {
                    page_no: page_no,
                    product_no: <?=$product_no?>
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        //alert("조회를 완료헀습니다.");
                        // 가져온 데이터가 있다면 모든 행 삭제.
                        if(json.seq.length != 0){
                            //모든 행 삭제
                            $('#accordion_review > .review').remove();
                            // 페이징 삭제
                            $('.pagination_review > li').remove();
                        }

                        // 리뷰 갯수
                        cntReview = json.seq.length;
                        totalCntReview = json.total_count_of_post;
                        $('#cnt_review').text(totalCntReview);

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let review =
                                '<div id="review_'+i+'" class="card border-primary mb-3 review">'
                                +'<div id="heading_review'+i+'" class="card-header p-0 border-0" style="color: white">'
                                +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_review'+i+'" aria-expanded="false" aria-controls="collapse_review'+i+'" class="btn btn-light d-block text-left rounded-0">'
                                +'<span class="raty" id="raty_review_'+i+'" style="margin-right: 50px;"><input id="star_score_'+i+'"hidden value="'+json.starScore[i]+'"></span>'
                                +'<span id="review_title_'+i+'">'+json.title[i]+'</span>'
                                +'<span id="review_name_'+i+'" style="float: right; color: darkgrey" >'+json.name[i]+'</span>'
                                +'</a>'
                                +'</h4>'
                                +'</div>'
                                +'<div id="collapse_review'+i+'" aria-labelledby="heading_review'+i+' data-parent=#accordion_review" class="collapse" >'
                                +'<div class="card-body">'
                                +'<p id="review_contents_'+i+'" >'+json.contents[i]+'</p>'
                                +'<p style="color: darkgrey">'+json.productColor[i]+'색상/'+json.productSize[i]+'사이즈 구매</p>'
                                +'<div>'
                                +'<table class="table">'
                                +'<tr>'
                                +'<td>사이즈: <span id="review_eval_size_'+i+'" >'+json.evalSize[i]+'</span></td>'
                                +'<td>밝기: <span id="review_eval_lightness_'+i+'" >'+json.evalLightness[i]+'</span></td>'
                                +'<td>색감: <span id="review_eval_color_'+i+'" >'+json.evalColor[i]+'</span></td>';
                            if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                review += '<td>무게감: <span id="review_eval_thickness_'+i+'" >'+json.evalThickness[i]+'</span></td>';
                            }
                            review += '</tr>'
                                +'</table>'
                                +'</div>'
                                +'</div>'
                            if('<?=$login_id?>' == json.writer[i]){
                                review += '<div class="navbar-buttons" align="right" style="display: flex;">'
                                    +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="javascript:modifyReview('+json.seq[i]+' , '+ i +' ,'+ 1 +')" class="btn btn-primary navbar-btn">수정</a></div>'
                                    +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="javascript:deleteReview('+json.seq[i]+' , '+ i +' ,'+ 1 +')" class="btn btn-primary navbar-btn">삭제</a></div>'

                            }
                           review += '</div>'
                                +'</div>'
                                +'</div>';
                            $('#accordion_review').append(review);
                            $('#raty_review_'+i).raty({half : true, readOnly: true, score: json.starScore[i]});
                        }

                        /* 페이징 시작 */
                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination_review').append('<li class="page-item"><a href="javascript:searchReview(1)"class="page-link">' + '<<' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination_review').append('<li class="page-item"><a href="javascript:searchReview('+ (parseInt(json.start_page_num_of_block) - 1) + ')" class="page-link">' + '<' + '</a></li>');
                        }

                        for(let i = parseInt(json.start_page_num_of_block); i <= parseInt(json.end_page_num_of_block); i++){
                            if(page_no != i){
                                $('.pagination_review').append('<li class="page-item"><a href="javascript:searchReview('+i + ')" class="page-link">' + i + '</a></li>');
                            } else{
                                $('.pagination_review').append('<li class="page-item active"><a href="javascript:searchReview('+i + ')" class="page-link">' + i + '</a></li>');
                            }
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination_review').append('<li class="page-item"><a href="javascript:searchReview('+ (parseInt(json.end_page_num_of_block) + 1) + ')"class="page-link">' + '>' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination_review').append('<li class="page-item"><a href="javascript:searchReview('+ parseInt(json.total_count_of_page) + ')" class="page-link">' + '>>' + '</a></li>');
                        }
                        /* 페이징 종료 */
                    } else {
                        alert("조회에 실패했습니다!");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        }

        // Q&A 가져오기
        function searchQandA(pageNo){
            let page_no = pageNo;

            if(page_no == '' || page_no == null || page_no == undefined){
                page_no = 1;
            }

            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/qanda/selectQandaCompl.php',
                data: {
                    page_no: page_no,
                    product_no: <?=$product_no?>
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        //alert("조회를 완료헀습니다.");
                        // 가져온 데이터가 있다면 모든 행 삭제.
                        if(json.seq.length != 0){
                            //모든 행 삭제
                            $('#accordion_qanda > .qanda').remove();
                            // 페이징 삭제
                            $('.pagination_qanda > li').remove();
                        }

                        // Q&A갯수

                        cntQandA = json.seq.length;
                        totlalCntQandA = json.total_count_of_post;
                        $('#cnt_qanda').text(totlalCntQandA);

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let qanda =
                                '<div id="qanda'+i+'" class="card border-primary mb-3 qanda">'
                                    + '<div id="heading_qanda'+i+'" class="card-header p-0 border-0">';
                                if(json.secretYn[i] == 1){
                                    if(json.writer[i] == '<?=$login_id?>'){ //작성자가 로그인한 사용자인 경우
                                        qanda +='<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_qanda'+i+'" aria-expanded="false" aria-controls="collapse_qanda'+i+'" class="btn btn-toolbar d-block text-left rounded-0">'
                                    } else{
                                        qanda +='<h4 class="mb-0" onclick="alert(\'비밀글입니다.\');"><a class="btn btn-toolbar d-block text-left rounded-0">'
                                    }
                                } else{
                                    qanda +='<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_qanda'+i+'" aria-expanded="false" aria-controls="collapse_qanda'+i+'" class="btn btn-toolbar d-block text-left rounded-0">'
                                }
                                qanda +=  '<span style="display: inline-block; width: 100px;">'+json.answerState[i]+'</span>';
                                        // 비밀글인 경우
                                        if(json.secretYn[i] == 1 && json.writer[i] != '<?=$login_id?>'){
                                            if(json.answerYn[i] == 0){ //답변중
                                                qanda += '<span id="qanda_type_'+i+'" style="display: inline-block; width: 80px;">'+json.type[i]+'</span>';
                                                qanda += '<span id="qanda_title_'+i+'">'+json.title[i]+'<i class="fa fa-lock"></i></span>'
                                            } else{ //답변완료
                                                qanda += '<span id="qanda_type_'+i+'" style="display: inline-block; width: 80px;">'+json.type[i]+'</span>';
                                                qanda += '<span id="qanda_title_'+i+'">'+json.title[i]+'<i class="fa fa-lock"></i></span>'
                                            }
                                        } else{ //일반글인 겨우
                                            if(json.answerYn[i] == 0){ //답변중
                                                qanda += '<span id="qanda_type_'+i+'" style="display: inline-block; width: 80px;">'+json.type[i]+'</span>';
                                                qanda += '<span id="qanda_title_'+i+'">'+json.title[i]+'</span>'
                                            } else{ //답변완료
                                                qanda += '<span id="qanda_type_'+i+'" style="display: inline-block; width: 80px;">'+json.type[i]+'</span>';
                                                qanda += '<span id="qanda_title_'+i+'">'+json.title[i]+'</span>'
                                            }
                                        }

                                        qanda += '<span style="float: right;">'+json.name[i]+'</span></a></h4>'
                                    + '</div>'
                                    + '<div id="collapse_qanda'+i+'" aria-labelledby="heading_qanda'+i+' data-parent=#accordion_qanda" class="collapse" >'
                                        + '<div id="qanda_contents_'+i+'" class="card-body contents" style=" margin-left: 173px;" >'
                                        + json.contents[i]
                                        + '</div>';
                                    // 답변 상태에 따라 나올지 안나올지 결정
                                    if(json.answerState[i] == '답변완료'){
                                        qanda += '<div class="card-body reply" style="background-color: lightgrey;">'
                                                + '<div style="margin-left: 173px;">'
                                                + '<span style="font-weight: bold;">답변</span><br><br>'
                                                + json.answer[i]+'<br><br><br><br><br><br>'
                                                + '</div>'
                                             + '</div>';
                                    }
                                    if('<?=$login_id?>' == json.writer[i]){
                                        qanda += '<div class="navbar-buttons" align="right" style="display: flex;">';
                                        if(json.answerState[i] == '답변중'){
                                            qanda += '<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="javascript:modifyQandA('+json.seq[i]+' , '+i+')" class="btn btn-primary navbar-btn">수정</a></div>';
                                        }
                                        qanda += '<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="javascript:deleteQandA('+json.seq[i]+' , '+i+')" class="btn btn-primary navbar-btn">삭제</a></div>'
                                            + '</div>';
                                    }
                            qanda += '</div>'
                                + '</div>';

                            $('#accordion_qanda').append(qanda);
                        }

                        /* 페이징 시작 */
                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination_qanda').append('<li class="page-item"><a href="javascript:searchQandA(1)"class="page-link">' + '<<' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != 1){
                            $('.pagination_qanda').append('<li class="page-item"><a href="javascript:searchQandA('+ (parseInt(json.start_page_num_of_block) - 1) + ')" class="page-link">' + '<' + '</a></li>');
                        }

                        for(let i = parseInt(json.start_page_num_of_block); i <= parseInt(json.end_page_num_of_block); i++){
                            if(page_no != i){
                                $('.pagination_qanda').append('<li class="page-item"><a href="javascript:searchQandA('+i + ')" class="page-link">' + i + '</a></li>');
                            } else{
                                $('.pagination_qanda').append('<li class="page-item active"><a href="javascript:searchQandA('+i + ')" class="page-link">' + i + '</a></li>');
                            }
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination_qanda').append('<li class="page-item"><a href="javascript:searchQandA('+ (parseInt(json.end_page_num_of_block) + 1) + ')"class="page-link">' + '>' + '</a></li>');
                        }

                        if(parseInt(json.current_num_of_block) != parseInt(json.total_count_of_block)){
                            $('.pagination_qanda').append('<li class="page-item"><a href="javascript:searchQandA('+ parseInt(json.total_count_of_page) + ')" class="page-link">' + '>>' + '</a></li>');
                        }
                        /* 페이징 종료 */
                    } else {
                        alert("조회에 실패했습니다!");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        }

        // Q&A저장 버튼 클릭 이벤트
        $('#btn_qanda_compl').on('click', function(){
            /*console.log($('#secret_yn').is(":checked"));
            console.log($('#question_type').val());
            console.log($('#qanda_title').val());
            console.log($('#qanda_contents').val());*/

            let secret_yn = 0;

            if($('#secret_yn').is(":checked")){
                secret_yn = 1;
            }

            if(confirm("Q&A를 등록하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/qanda/writeQandaCompl.php',
                    data: {
                        secret_yn: secret_yn,
                        question_type: $('#question_type').val(),
                        qanda_title: $('#qanda_title').val(),
                        qanda_contents: $('#qanda_contents').val(),
                        product_no: productNo
                    },

                    success: function (json) {
                        if (json.result == 'ok') {

                            // 모달창 닫기
                            $('#qanda-modal').modal("hide");

                            // 동적으로 추가
                            let qanda =
                                '<div id="qanda'+cntQandA+'" class="card border-primary mb-3">'
                                + '<div id="heading_qanda'+cntQandA+'" class="card-header p-0 border-0">'
                                + '<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_qanda'+cntQandA+'" aria-expanded="false" aria-controls="collapse_qanda'+cntQandA+'" class="btn btn-toolbar d-block text-left rounded-0">'
                                + '<span style="display: inline-block; width: 100px;">답변중</span>'
                                + '<span id="qanda_type_'+cntQandA+'" style="display: inline-block; width: 80px;">'+$('#question_type').val()+'</span>'
                                + '<span id="qanda_title_'+cntQandA+'">'+$('#qanda_title').val()+'</span>'
                                + '<span style="float: right;"><?=$user_name?></span></a></h4>'
                                + '</div>'
                                + '<div id="collapse_qanda'+cntQandA+'" aria-labelledby="heading_qanda'+cntQandA+' data-parent=#accordion_qanda" class="collapse" >'
                                + '<div id="qanda_contents_'+cntQandA+'" class="card-body contents" style=" margin-left: 173px;" >'
                                + $('#qanda_contents').val()
                                + '</div>'
                                + '<div class="navbar-buttons" align="right" style="display: flex;">'
                                + '<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="javascript:modifyQandA('+json.seq+' , '+cntQandA+')" class="btn btn-primary navbar-btn">수정</a></div>'
                                + '<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="javascript:deleteQandA('+json.seq+', '+cntQandA+')" class="btn btn-primary navbar-btn">삭제</a></div>'
                                + '</div>'
                                + '</div>'
                                + '</div>';

                            console.log(qanda);

                            // 첫 Q&A라면
                            if(cntQandA == 0 ){
                                $('#accordion_qanda').append(qanda);
                            } else{
                                $('#qanda0').before(qanda);
                            }


                            // 모달에 작성한 데이터 제거
                            $('#qanda_title').val('');
                            $('#qanda_contents').val('');
                            $('#secret_yn').prop("checked", false);

                            cntQandA++;
                            totlalCntQandA++;
                            $('#cnt_qanda').text(totlalCntQandA);

                        } else{
                            alert("Q&A등록에 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        });

        // 상품 삭제
        $('#btn_product_delete').on("click", function(){
            if(confirm("해당상품을 삭제하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/deleteProductCompl.php',
                    data: {
                        product_no: <?=$product_no?>
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            // 로그인 -> 로그아웃으로 변경
                            location.href = "category.php?menu_no="+<?=$menu_no?>;
                            // 모달창 종료
                        } else {
                            alert("삭제에 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        });

        //후기 삭제
        function deleteReview(seq, cnt, type){
            if(confirm("해당상품을 삭제하시겠습니까?")){
                let starScoreOfReview;

                if(type == 0){
                    starScoreOfReview = $("#raty_"+cnt).children('input').val();;
                } else{
                    starScoreOfReview = $("#raty_review_"+cnt).children('input').val();;
                }
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/review/deleteReviewCompl.php',
                    data: {
                        seq: seq
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            // 포토후기라면(type=0)
                            if(type == 0){
                                $('#photo_review_'+cnt).remove();
                                cntPhotoReview--;
                                totalCntPhotoReview--;
                                $('#cnt_photo_review').text(totalCntPhotoReview);
                            } else {// 일반후기라면(type=1)
                                $('#review_'+cnt).remove();
                                cntReview--;
                                totalCntReview--;
                                $('#cnt_review').text(totalCntReview);
                            }

                            // 상품 전체 별점 변경
                            sum = sum - starScoreOfReview;
                            cntOfReviewer--;
                            starScore = sum/cntOfReviewer;

                            $('#raty_product').children('img').remove();
                            $('#raty_product').raty({half : true, readOnly: true, score: starScore});

                            $('#cnt_reviewer').text(cntOfReviewer + '건');
                        } else {
                            alert("삭제에 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        }

        //Q&A 삭제
        function deleteQandA(seq, cnt){
            if(confirm("해당상품을 삭제하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/qanda/deleteQandaCompl.php',
                    data: {
                        seq: seq
                    },

                    success: function (json) {
                        if (json.result == 'ok') {
                            $('#qanda'+cnt).remove();
                            cntQandA--;
                            totlalCntQandA--;
                            $('#cnt_qanda').text(totlalCntQandA);
                        } else {
                            alert("삭제에 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        }

        // 후기 수정열기
        function modifyReview(seq, cnt, type){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/review/selectReviewOneCompl.php',
                data: {
                    seq: seq,
                    type: type
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        $('#photo_review_modify_product').text(json.productColor + '/' + json.productSize);
                        $('#photo_review_modify_title').val(json.title);
                        $('#photo_review_modify_contents').val(json.contents);
                        $('#photo_review_modify_seq').val(seq);
                        $('#photo_review_modify_cnt').val(cnt);
                        $('#photo_review_modify_type').val(type);

                        /* 평가항목 */
                        //사이즈
                        let sizeArr = ['작아요', '보통이에요', '커요'];
                        let optionSize = '';

                        $('#photo_review_modify_evaluation_size').children('option').remove();

                        for(let i = 0; i < sizeArr.length; i++){
                            if(json.evalSize == sizeArr[i]){
                                optionSize += '<option value="'+sizeArr[i]+'" selected>'+sizeArr[i]+'</option>';
                            } else{
                                optionSize += '<option value="'+sizeArr[i]+'">'+sizeArr[i]+'</option>';
                            }
                        }

                        $('#photo_review_modify_evaluation_size').append(optionSize);

                        // 밝기
                        let lightnessArr = ['어두워요', '보통이에요', '밝아요'];
                        let optionLightness = '';

                        $('#photo_review_evaluation_modify_lightness').children('option').remove();

                        for(let i = 0; i < lightnessArr.length; i++){
                            if(json.evalLightness == lightnessArr[i]){
                                optionLightness += '<option value="'+lightnessArr[i]+'" selected>'+lightnessArr[i]+'</option>';
                            } else{
                                optionLightness += '<option value="'+lightnessArr[i]+'">'+lightnessArr[i]+'</option>';
                            }
                        }

                        $('#photo_review_evaluation_modify_lightness').append(optionLightness);

                        // 색상
                        let colorArr = ['흐려요', '보통이에요', '선명해요'];
                        let optionColor = '';

                        $('#photo_review_evaluation_modify_color').children('option').remove();

                        for(let i = 0; i < colorArr.length; i++){
                            if(json.evalColor == colorArr[i]){
                                optionColor += '<option value="'+colorArr[i]+'" selected>'+colorArr[i]+'</option>';
                            } else{
                                optionColor += '<option value="'+lightnessArr[i]+'">'+colorArr[i]+'</option>';
                            }
                        }
                        $('#photo_review_evaluation_modify_color').append(optionColor);

                        // 무게감
                        let thicknessArr = ['얇아요', '보통이에요', '두꺼워요'];
                        let optionThickness = '';

                        $('#photo_review_modify_evaluation_thickness').children('option').remove();

                        for(let i = 0; i < thicknessArr.length; i++){
                            if(json.evalThickness == thicknessArr[i]){
                                optionThickness += '<option value="'+thicknessArr[i]+'" selected>'+thicknessArr[i]+'</option>';
                            } else{
                                optionThickness += '<option value="'+thicknessArr[i]+'">'+thicknessArr[i]+'</option>';
                            }
                        }
                        $('#photo_review_modify_evaluation_thickness').append(optionThickness);

                        // 별점
                        $('#photo_review_modify_raty').children('img').remove();
                        $('#photo_review_modify_raty').raty({half : true, readOnly: false, score: json.starScore});

                        // 포토후기인 경우 파일 보이기
                        if(type == 0){
                            $('#photo_review_modify_product_img').show();
                            $('#photo_review_modify_img_photo_review').attr("src",json.savePath);
                            $('#fileName').text(json.fileName)
                        } else{
                            $('#photo_review_modify_product_img').hide();
                        }
                    } else {
                        alert("수정에 실패했습니다.");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });

            // input hidden으로 cnt, type넣어주기
            // type에 따라 파일 숨기기
            $('#photo-review-modify-modal').modal('show');
        }

        // 후기 수정완료
        $('#btn_modify_photo_review_compl').on("click", function(){
            /*console.log(1 + ': '+ $('#photo_review_modify_seq').val());
            console.log(2 + ': '+ $('#photo_review_modify_cnt').val());
            console.log(2 + ': '+ $('#photo_review_modify_type').val());*/

            let formData = new FormData();

            formData.append("photo_review_seq", $('#photo_review_modify_seq').val());
            formData.append("photo_review_type", $('#photo_review_modify_type').val());
            formData.append("photo_review_title", $("#photo_review_modify_title").val());
            formData.append("photo_review_contents", $("#photo_review_modify_contents").val());
            formData.append("photo_review_evaluation_size", $("#photo_review_modify_evaluation_size").val());
            formData.append("photo_review_evaluation_color", $("#photo_review_evaluation_modify_color").val());
            formData.append("photo_review_evaluation_lightness", $("#photo_review_evaluation_modify_lightness").val());
            formData.append("photo_review_evaluation_thickness", $("#photo_review_modify_evaluation_thickness").val());
            formData.append("photo_review_raty", $("#photo_review_modify_raty").children('input').val());
            formData.append("photo_review_modify_file_photo_review", $('#photo_review_modify_file_photo_review')[0].files[0]);

            let cnt = $('#photo_review_modify_cnt').val();
            let type = $('#photo_review_modify_type').val();

            if(confirm("해당리뷰를 수정하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/review/modifyReviewCompl.php',
                    processData: false, // 필수
                    contentType: false, // 필수
                    data: formData,

                    success: function (json) {
                        if (json.result == 'ok') {
                            // 포토 후기 완료 시 데이터 수정
                            // 모달 창 닫기
                            $('#photo-review-modify-modal').modal("hide");

                            // 포토리뷰
                            if(type == 0){
                                $('#photo_review_title_'+cnt).text($("#photo_review_modify_title").val());
                                $('#photo_review_contents_'+cnt).text($("#photo_review_modify_contents").val());
                                $('#photo_review_eval_size_'+cnt).text($("#photo_review_modify_evaluation_size").val());
                                $('#photo_review_eval_color_'+cnt).text($("#photo_review_evaluation_modify_color").val());
                                $('#photo_review_eval_lightness_'+cnt).text($("#photo_review_evaluation_modify_lightness").val());
                                $('#photo_review_eval_thickness_'+cnt).text($("#photo_review_modify_evaluation_thickness").val());

                                $('#raty_'+cnt).children('img').remove();
                                $('#raty_'+cnt).raty({half : true, readOnly: false, score: $("#photo_review_modify_raty").children('input').val()});

                                // 이미지 파일이 있다면 넣어 줌
                                if(json.saved_path != null && json.saved_path != '' ){
                                    $('#photo_review_img_'+cnt).attr("src",json.saved_path);
                                }

                            } else { //일반 후기 완료 시 데이터 수정

                                $('#review_title_'+cnt).text($("#photo_review_modify_title").val());
                                $('#review_contents_'+cnt).text($("#photo_review_modify_contents").val());
                                $('#review_eval_size_'+cnt).text($("#photo_review_modify_evaluation_size").val());
                                $('#review_eval_color_'+cnt).text($("#photo_review_evaluation_modify_color").val());
                                $('#review_eval_lightness_'+cnt).text($("#photo_review_evaluation_modify_lightness").val());
                                $('#review_eval_thickness_'+cnt).text($("#photo_review_modify_evaluation_thickness").val());

                                $('#raty_review_'+cnt).children('img').remove();
                                $('#raty_review_'+cnt).raty({half : true, readOnly: false, score: $("#photo_review_modify_raty").children('input').val()});
                            }
                        } else {
                            alert("수정에 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        });

        //Q&A수정
        function modifyQandA(seq, cnt) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: '/mall/php/product/qanda/selectQandaOneCompl.php',
                data: {
                    seq: seq
                },

                success: function (json) {
                    if (json.result == 'ok') {
                        /*if (json.secretYn == 1) {
                            $('#qanda_modify_secret_yn').prop("checked", true);
                        } else{
                            $('#qanda_modify_secret_yn').prop("checked", false);
                        }*/
                        $('#qanda_modify_title').val(json.title);
                        $('#qanda_modify_contents').text(json.contents);

                        $('#qanda_modify_seq').val(seq);
                        $('#qanda_modify_cnt').val(cnt);

                        /* 평가항목 */
                        //사이즈
                        let typeArr = ['사이즈', '재입고', '배송', '기타문의'];
                        let optionSize = '';

                        $('#qanda_modify_question_type').children('option').remove();

                        for (let i = 0; i < typeArr.length; i++) {
                            if (json.type == typeArr[i]) {
                                optionSize += '<option value="' + typeArr[i] + '" selected>' + typeArr[i] + '</option>';
                            } else {
                                optionSize += '<option value="' + typeArr[i] + '">' + typeArr[i] + '</option>';
                            }
                        }

                        $('#qanda_modify_question_type').append(optionSize);

                        // 모달 열기
                        $('#qanda-modify-modal').modal('show');
                    } else {
                        alert("수정에 실패했습니다.");
                    }
                },
                error: function () {
                    alert("에러가 발생했습니다.");
                }
            });
        }

        // Q&A 수정완료
        $('#btn_qanda_modify_compl').on("click", function(){
            let cnt = $('#qanda_modify_cnt').val();
            let seq = $('#qanda_modify_seq').val();

            let formData = new FormData();

            //formData.append("secret_yn", $('#qanda_modify_secret_yn').is(":checked") ? 1 : 0);
            formData.append("question_type", $('#qanda_modify_question_type').val());
            formData.append("qanda_title", $("#qanda_modify_title").val());
            formData.append("qanda_contents", $("#qanda_modify_contents").val());
            formData.append("qanda_seq", seq);

            if(confirm("해당 Q&A를 수정하시겠습니까?")){
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: '/mall/php/product/qanda/modifyQandaCompl.php',
                    processData: false, // 필수
                    contentType: false, // 필수
                    data: formData,

                    success: function (json) {
                        if (json.result == 'ok') {
                            // 모달 창 닫기
                            $('#qanda-modify-modal').modal("hide");

                            $('#qanda_title_'+cnt).text($("#qanda_modify_title").val());
                            $('#qanda_contents_'+cnt).text($("#qanda_modify_contents").val());
                            $('#qanda_type_'+cnt).text($("#qanda_modify_question_type").val());
                            // 포토리뷰
                        } else {
                            alert("수정에 실패했습니다.");
                        }
                    },
                    error: function () {
                        alert("에러가 발생했습니다.");
                    }
                });
            }
        });

        // 색상 선택 시 해당하는 사이즈만 출력되게 하기
        let option2Arr = <?=json_encode($option2Arr)?>;

        $('#option1').on("change", function(){
            // 사이즈 카테고리 박스를 비우기
            $('#option2').empty();
            $('#option2').append('<option value="">[선택]</option>');


            for(let i = 0; i < option2Arr[0].length; i++){
                if($(this).val() == option2Arr[0][i]){
                    let option = "<option value=" + option2Arr[1][i] + ">"+ option2Arr[1][i] +"</option>";
                    $('#option2').append(option);
                }
            }
        });
    </script>
</body>
</html>