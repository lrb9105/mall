<?php
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

// 상품 사이즈 정보
$sqlProductSizeInfo = "SELECT DISTINCT PO.SIZE
        FROM PRODUCT_OPTION PO
        WHERE PO.PRODUCT_SEQ = $product_no
        AND QUANTITY > 0
        ";

// 상품 색상 정보
$sqlProductColorInfo = "SELECT DISTINCT PO.COLOR
        FROM PRODUCT_OPTION PO
        WHERE PO.PRODUCT_SEQ = $product_no
        AND QUANTITY > 0
        ";
$option1 = mysqli_query($conn, $sqlProductColorInfo);
$option2 = mysqli_query($conn, $sqlProductSizeInfo);;

// 상품 이미지 정보
$sqlFileInfo = "SELECT F.SEQ,
                       F.REF_SEQ,
                       F.SAVE_PATH
        FROM FILE F
        WHERE F.REF_SEQ = $product_no
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
                            <div class="item"> <img id="img_rep" src="<? echo $rowFileInfo['SAVE_PATH']?>" alt="" class="img-fluid"></div>
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
                            <form method="post" action="checkout4.php">
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
                                            <select class="form-control"  name="option2" id="option2" style="width: 85%; margin: 5px;">
                                                <option value="">[선택]</option>
                                                <?while($rowProductNumInfo = mysqli_fetch_array($option2)){?>
                                                    <option value="<?echo $rowProductNumInfo['SIZE']?>"><?echo $rowProductNumInfo['SIZE']?></option>
                                                <?}?>
                                            </select>
                                        </div>
                                        <div class="number form-inline">
                                            <span>수량: </span>
                                            <input id="product_number" name="product_number" type="number" class="form-control" style="margin-left: 20px;" value="1" min="1">
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
                                            <span>배송유형: &nbsp;&nbsp;&nbsp; 무료배송</span>
                                        </div>
                                        <br><br><br>
                                        <p style="text-align: right;">총 금액: &nbsp;&nbsp;&nbsp;<span id="total_price" style="font-size: 22px; font-weight: bold; color: red;"><?echo $rowProductInfo['PRODUCT_PRICE_SALE']?></span><span>원</span></p>
                                        <input name="product_no" type="number" value="<?echo $product_no?>" hidden>
                                        <input name="menu_no" type="number" value="<?echo $menu_no?>" hidden>
                                    </div>
                                    <p class="text-center buttons"><input type="submit" class="btn btn-info" value="바로구매"><a href="javascript:addCart(<?echo $product_no?>);" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> 장바구니 담기</a><a href="basket.php" class="btn btn-outline-primary"><i class="fa fa-heart"></i> 찜하기</a></p>
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
                            <div data-slider-id="1" class="owl-thumbs">
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
                                <div id="review" role="tabpanel" aria-labelledby="review-tab" class="tab-pane fade">Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.<br>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</div>
                                <div id="qanda" role="tabpanel" aria-labelledby="qanda-tab" class="tab-pane fade">Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</div>
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


        let origin_price = <?echo $rowProductInfo['PRODUCT_PRICE_SALE']?>

        // 수량이 변경 될 때 마다 총 금액 변경해줌
        $('#product_number').on("change", function(){
            $('#total_price').text($('#product_number').val() * parseInt(origin_price));
        });


        // 장바구니 추가
        function addCart(product_no){
            /*console.log(product_no);
            console.log($('#option1').val());
            console.log($('#option2').val());
            console.log($('#product_number').val());*/

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
    </script>
</body>
</html>