<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];
$product_no = $_GET['product_no'];

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
        AND (F.TYPE = 0 OR F.TYPE = 1)
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

// Q&A

?>

<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<body>
<!-- navbar-->
<header class="header mb-5">
<div id="all">
    <div id="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
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
                            <form id="form_purchase" method="post" action="checkout4.php" onsubmit="return verifyBeforePurchase();">
                                <div class="box">
                                    <h1 class="text-center"> <?echo $rowProductInfo['PRODUCT_NAME']?></h1><br>
                                    <div class="product-info">
                                        <p>정상 가격: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 15px; color: #4e555b"><del><?echo number_format($rowProductInfo['PRODUCT_PRICE'])?>원</del></span></p>
                                        <p>판매 가격: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size: 22px; font-weight: bold;"><?echo number_format($rowProductInfo['PRODUCT_PRICE_SALE'])?>원</span>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="">▼ <?echo ceil(($rowProductInfo['PRODUCT_PRICE'] - $rowProductInfo['PRODUCT_PRICE_SALE'])/$rowProductInfo['PRODUCT_PRICE']*100)?>%할인<em class="color-lightgrey">(-<?echo number_format($rowProductInfo['PRODUCT_PRICE'] - $rowProductInfo['PRODUCT_PRICE_SALE'])?>원)</em></span>
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
                                    </div>
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
                                <div id="review" role="tabpanel" aria-labelledby="review-tab" class="tab-pane fade">
                                    <div id="board" class="col-lg-12">
                                        <div id="contact" class="box">
                                            <div id="header_photo_review" class="d-flex justify-content-between">
                                                <h3>포토후기</h3>
                                                <?if($rowOrderInfo[0] != null) {?>
                                                <button id="btn_review" class="btn btn-info" data-toggle="modal" data-target="#photo-review-modal">후기 작성</button>
                                                <?}?>
                                            </div>
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
                                                                                        <img id="img_photo_review" width="300px;">
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
                                            <hr>
                                            <div id="accordion">
                                                <?/*
                                                $i = 0;
                                                while($rowPhotoReviewInfo = mysqli_fetch_array($resultPhotoReviewInfo)){*/?><!--
                                                    <div id="photo_review_<?/*=$i*/?>"" class="card border-primary mb-3">
                                                        <div id="heading<?/*=$i*/?>" class="card-header p-0 border-0" style="color: white">
                                                            <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse<?/*=$i*/?>" aria-expanded="false" aria-controls="collapse<?/*=$i*/?>" class="btn btn-light d-block text-left rounded-0">
                                                                    <span class="raty" id="raty_<?/*=$i*/?>" style="margin-right: 50px;"><input id="star_score_<?/*=$i*/?>"hidden value="<?/*=$rowPhotoReviewInfo['STAR_SCORE']*/?>"></span>
                                                                    <span><?/*=$rowPhotoReviewInfo['TITLE']*/?></span>
                                                                    <span style="float: right; color: darkgrey" ><?/*=$rowPhotoReviewInfo['WRITER']*/?></span>
                                                                </a>
                                                            </h4>
                                                        </div>
                                                        <div id="collapse<?/*=$i*/?>" aria-labelledby="heading<?/*=$i*/?> data-parent=#accordion" class="collapse show" >
                                                            <div class="card-body">
                                                                <p><?/*=$rowPhotoReviewInfo['CONTENTS']*/?></p>
                                                                <img style="text-align: center; align:center;" width="300px;" src="<?/*=$rowPhotoReviewInfo['SAVE_PATH']*/?>">
                                                                <p style="color: darkgrey"><?/*=$rowPhotoReviewInfo['PRODUCT_COLOR']*/?>색상/<?/*=$rowPhotoReviewInfo['PRODUCT_SIZE']*/?>사이즈 구매</p>
                                                                <div>
                                                                    <table class="table">
                                                                        <tr>
                                                                            <td>사이즈: <span><?/*=$rowPhotoReviewInfo['EVAL_SIZE']*/?></span></td>
                                                                            <td>밝기: <span><?/*=$rowPhotoReviewInfo['EVAL_LIGHTNESS']*/?></span></td>
                                                                            <td>색감: <span><?/*=$rowPhotoReviewInfo['EVAL_COLOR']*/?></span></td>
                                                                            <?/*if($rowProductInfo['FIRST_CATEGORY'] != 26 && $rowProductInfo['FIRST_CATEGORY'] != 27){*/?>
                                                                            <td>무게감: <span><?/*=$rowPhotoReviewInfo['EVAL_THICKNESS']*/?></span></td>
                                                                            <?/*}*/?>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <div class="navbar-buttons" align="right" style="display: flex;">
                                                                <div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>
                                                                <div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a onclick="return confirm('정말로 삭제하시겠습니까?');" href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                --><?/*
                                                    $i++;
                                                }*/?>
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
                                                <h3>일반후기</h3>
                                            </div>
                                            <hr>
                                            <div id="accordion_review">
                                                <!--<div class="card border-primary mb-3">
                                                    <div id="heading1" class="card-header p-0 border-0" style="color: white">
                                                        <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1" class="btn btn-light d-block text-left rounded-0">
                                                                <span id="raty_1" style="margin-right: 50px;"></span>
                                                                <span>111111</span>
                                                                <span style="float: right; color: darkgrey" >test</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse1" aria-labelledby="heading0 data-parent=#accordion_review" class="collapse" >
                                                        <div class="card-body">
                                                            <p>매우 정말 만족합니다. <br> ㄴㅇㄹㄴㅇㄹ<br> ㄴㅇㄹㄴㅇㄹ<br> ㄴㅇㄹㄴㅇㄹ</p>
                                                            <p style="color: darkgrey">화이트색상/M사이즈 구매</p>
                                                            <div>
                                                                <table class="table">
                                                                    <tr>
                                                                        <td>사이즈: <span>좋아요</span></td>
                                                                        <td>밝기: <span>좋아요</span></td>
                                                                        <td>색감: <span>좋아요</span></td>
                                                                        <td>무게감: <span>좋아요</span></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="navbar-buttons" align="right" style="display: flex;">
                                                            <div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>
                                                            <div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a onclick="return confirm('정말로 삭제하시겠습니까?');" href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card border-primary mb-3">
                                                    <div id="heading2" class="card-header p-0 border-0" style="color: white">
                                                        <h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse2" aria-expanded="false" aria-controls="collapse2" class="btn btn-light d-block text-left rounded-0">
                                                                <span id="raty_2" style="margin-right: 50px;"></span>
                                                                <span>111111</span>
                                                                <span style="float: right; color: darkgrey" >test</span>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse2" aria-labelledby="heading0 data-parent=#accordion_review" class="collapse" >
                                                        <div class="card-body">
                                                            <p>매우 정말 만족합니다. <br> ㄴㅇㄹㄴㅇㄹ<br> ㄴㅇㄹㄴㅇㄹ<br> ㄴㅇㄹㄴㅇㄹ</p>
                                                            <p style="color: darkgrey">화이트색상/M사이즈 구매</p>
                                                            <div>
                                                                <table class="table">
                                                                    <tr>
                                                                        <td>사이즈: <span>좋아요</span></td>
                                                                        <td>밝기: <span>좋아요</span></td>
                                                                        <td>색감: <span>좋아요</span></td>
                                                                        <td>무게감: <span>좋아요</span></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="navbar-buttons" align="right" style="display: flex;">
                                                            <div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=2" class="btn btn-primary navbar-btn">수정</a></div>
                                                            <div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a onclick="return confirm('정말로 삭제하시겠습니까?');" href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>
                                                        </div>
                                                    </div>
                                                </div>-->
                                            </div>
                                            <!-- /.accordion-->
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination pagination_review" style="justify-content: center;">
                                                    <!--<li class="page-item"><a href="#" class="page-link"><</a></li>
                                                    <li class="page-item active"><a href="#" class="page-link">1</a></li>
                                                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                                                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                                                    <li class="page-item"><a href="#" class="page-link">4</a></li>
                                                    <li class="page-item"><a href="#" class="page-link">5</a></li>
                                                    <li class="page-item"><a href="#" class="page-link">></a></li>-->
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div id="qanda" role="tabpanel" aria-labelledby="qanda-tab" class="tab-pane fade">
                                    <div id="board" class="col-lg-12">
                                        <div class="container">
                                            <div id="contact" class="box">
                                                <div id="header_review" class="d-flex justify-content-between">
                                                    <h3>Q&A</h3>
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
<!-- JavaScript files-->
<?php
include 'jsfile.php'
?>

    <script>
        // menu_no에 따라 menu_title, cat_second, cat_third 변경하기
        productNo = '<?echo $product_no?>';

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

        // 상품이미지
        $('#file_photo_review').on("change", function(e){
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
                                +'<span>'+$("#photo_review_title").val()+'</span>'
                                +'<span style="float: right; color: darkgrey" ><?=$login_id?></span>'
                                +'</a>'
                                +'</h4>'
                                +'</div>'
                                +'<div id="collapse_photo_review'+cntPhotoReview+'" aria-labelledby="heading_photo_review'+cntPhotoReview+' data-parent=#accordion" class="collapse show" >'
                                +'<div class="card-body">'
                                +'<p>'+$("#photo_review_contents").val()+'</p>'
                                +'<img style="text-align: center; align:center;" width="300px;" src="'+json.save_path+'">'
                                +'<p style="color: darkgrey">'+json.color+'색상/'+json.size+'사이즈 구매</p>'
                                +'<div>'
                                +'<table class="table">'
                                +'<tr>'
                                +'<td>사이즈: <span>'+$("#photo_review_evaluation_size").val()+'</span></td>'
                                +'<td>밝기: <span>'+$("#photo_review_evaluation_lightness").val()+'</span></td>'
                                +'<td>색감: <span>'+$("#photo_review_evaluation_color").val()+'</span></td>';
                            if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                photoReview += '<td>무게감: <span>'+$("#photo_review_evaluation_thickness").val()+'</span></td>';
                            }
                            photoReview += '</tr>'
                                +'</table>'
                                +'</div>'
                                +'</div>'
                                +'<div class="navbar-buttons" align="right" style="display: flex;">'
                                +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>'
                                +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>'
                                +'</div>'
                                +'</div>'
                                +'</div>';

                            $('#photo_review_0').before(photoReview);

                            $('#raty_'+cntPhotoReview).raty({half : true, readOnly: true, score: $("#photo_review_raty").children('input').val()});

                            cntPhotoReview++;
                        } else{ //리뷰
                            let review =
                                '<div id="review_'+cntReview+'"class="card border-primary mb-3 photo-review">'
                                +'<div id="heading_review'+cntReview+'" class="card-header p-0 border-0" style="color: white">'
                                +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_review'+cntReview+'" aria-expanded="false" aria-controls="collapse_review'+cntReview+'" class="btn btn-light d-block text-left rounded-0">'
                                +'<span class="raty" id="raty_review_'+cntReview+'" style="margin-right: 50px;"><input id="star_score_'+cntReview+'"hidden value="'+$("#photo_review_raty").children('input').val()+'"></span>'
                                +'<span>'+$("#photo_review_title").val()+'</span>'
                                +'<span style="float: right; color: darkgrey" ><?=$login_id?></span>'
                                +'</a>'
                                +'</h4>'
                                +'</div>'
                                +'<div id="collapse_review'+cntReview+'" aria-labelledby="heading_review'+cntReview+' data-parent=#accordion" class="collapse show" >'
                                +'<div class="card-body">'
                                +'<p>'+$("#photo_review_contents").val()+'</p>'
                                +'<p style="color: darkgrey">'+json.color+'색상/'+json.size+'사이즈 구매</p>'
                                +'<div>'
                                +'<table class="table">'
                                +'<tr>'
                                +'<td>사이즈: <span>'+$("#photo_review_evaluation_size").val()+'</span></td>'
                                +'<td>밝기: <span>'+$("#photo_review_evaluation_lightness").val()+'</span></td>'
                                +'<td>색감: <span>'+$("#photo_review_evaluation_color").val()+'</span></td>';
                            if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                review += '<td>무게감: <span>'+$("#photo_review_evaluation_thickness").val()+'</span></td>';
                            }
                            review += '</tr>'
                                +'</table>'
                                +'</div>'
                                +'</div>'
                                +'<div class="navbar-buttons" align="right" style="display: flex;">'
                                +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>'
                                +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>'
                                +'</div>'
                                +'</div>'
                                +'</div>';

                            $('#review_0').before(review);

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

                            cntPhotoReview++;
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

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let photoReview =
                                '<div id="photo_review_'+i+'" class="card border-primary mb-3 photo-review">'
                                    +'<div id="heading_photo_review'+i+'" class="card-header p-0 border-0" style="color: white">'
                                        +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_photo_review'+i+'" aria-expanded="false" aria-controls="collapse_photo_review'+i+'" class="btn btn-light d-block text-left rounded-0">'
                                            +'<span class="raty" id="raty_'+i+'" style="margin-right: 50px;"><input id="star_score_'+i+'"hidden value="'+json.starScore[i]+'"></span>'
                                            +'<span>'+json.title[i]+'</span>'
                                            +'<span style="float: right; color: darkgrey" >'+json.writer[i]+'</span>'
                                        +'</a>'
                                        +'</h4>'
                                    +'</div>'
                                    +'<div id="collapse_photo_review'+i+'" aria-labelledby="heading_photo_review'+i+' data-parent=#accordion" class="collapse show" >'
                                        +'<div class="card-body">'
                                            +'<p>'+json.contents[i]+'</p>'
                                            +'<img style="text-align: center; align:center;" width="300px;" src="'+json.savePath[i]+'">'
                                            +'<p style="color: darkgrey">'+json.productColor[i]+'색상/'+json.productSize[i]+'사이즈 구매</p>'
                                            +'<div>'
                                                +'<table class="table">'
                                                    +'<tr>'
                                                        +'<td>사이즈: <span>'+json.evalSize[i]+'</span></td>'
                                                        +'<td>밝기: <span>'+json.evalLightness[i]+'</span></td>'
                                                        +'<td>색감: <span>'+json.evalColor[i]+'</span></td>';
                                                        if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                                            photoReview += '<td>무게감: <span>'+json.evalThickness[i]+'</span></td>';
                                                        }
                                            photoReview += '</tr>'
                                                +'</table>'
                                            +'</div>'
                                        +'</div>'
                                        +'<div class="navbar-buttons" align="right" style="display: flex;">'
                                            +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>'
                                            +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>'
                                        +'</div>'
                                    +'</div>'
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

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let review =
                                '<div id="review_'+i+'" class="card border-primary mb-3 review">'
                                +'<div id="heading_review'+i+'" class="card-header p-0 border-0" style="color: white">'
                                +'<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse_review'+i+'" aria-expanded="false" aria-controls="collapse_review'+i+'" class="btn btn-light d-block text-left rounded-0">'
                                +'<span class="raty" id="raty_review_'+i+'" style="margin-right: 50px;"><input id="star_score_'+i+'"hidden value="'+json.starScore[i]+'"></span>'
                                +'<span>'+json.title[i]+'</span>'
                                +'<span style="float: right; color: darkgrey" >'+json.writer[i]+'</span>'
                                +'</a>'
                                +'</h4>'
                                +'</div>'
                                +'<div id="collapse_review'+i+'" aria-labelledby="heading_review'+i+' data-parent=#accordion_review" class="collapse" >'
                                +'<div class="card-body">'
                                +'<p>'+json.contents[i]+'</p>'
                                +'<p style="color: darkgrey">'+json.productColor[i]+'색상/'+json.productSize[i]+'사이즈 구매</p>'
                                +'<div>'
                                +'<table class="table">'
                                +'<tr>'
                                +'<td>사이즈: <span>'+json.evalSize[i]+'</span></td>'
                                +'<td>밝기: <span>'+json.evalLightness[i]+'</span></td>'
                                +'<td>색감: <span>'+json.evalColor[i]+'</span></td>';
                            if(<?=$rowProductInfo['FIRST_CATEGORY']?> != 26 && <?=$rowProductInfo['FIRST_CATEGORY']?> != 27 ){
                                review += '<td>무게감: <span>'+json.evalThickness[i]+'</span></td>';
                            }
                            review += '</tr>'
                                +'</table>'
                                +'</div>'
                                +'</div>'
                                +'<div class="navbar-buttons" align="right" style="display: flex;">'
                                +'<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>'
                                +'<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>'
                                +'</div>'
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

                        // 페이징 적용
                        for(let i = 0; i < json.seq.length; i++){
                            let qanda =
                                '<div id="qanda'+i+'" class="card border-primary mb-3 qanda">'
                                    + '<div id="heading'+i+'" class="card-header p-0 border-0">'
                                    + '<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse'+i+'" aria-expanded="false" aria-controls="collapse'+i+'" class="btn btn-toolbar d-block text-left rounded-0"'
                                        + '<span>'+json.answerState[i]+'</span>'
                                        + '<span style="margin-left: 100px;">'+json.title[i]+'</span>'
                                        + '<span style="float: right;">'+json.writer[i]+'</span></a></h4>'
                                    + '</div>'
                                    + '<div id="collapse'+i+'" aria-labelledby="heading'+i+' data-parent=#accordion_qanda" class="collapse" >'
                                        + '<div class="card-body contents" style=" margin-left: 150px;" >'
                                        + json.contents[i]
                                        + '</div>';
                                    // 답변 상태에 따라 나올지 안나올지 결정
                                    if(json.answerState[i] == '답변완료'){
                                        qanda += '<div class="card-body reply" style="background-color: lightgrey;">'
                                                + '<div style="margin-left: 150px;">'
                                                + json.answer[i]+'<br><br><br><br><br><br>'
                                                + '</div>'
                                             + '</div>';
                                    }
                                        qanda += '<div class="navbar-buttons" align="right" style="display: flex;">'
                                            + '<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>'
                                            + '<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>'
                                        + '</div>'
                                    + '</div>'
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
                                + '<div id="heading'+cntQandA+'" class="card-header p-0 border-0">'
                                + '<h4 class="mb-0"><a href="#" data-toggle="collapse" data-target="#collapse'+cntQandA+'" aria-expanded="false" aria-controls="collapse'+cntQandA+'" class="btn btn-toolbar d-block text-left rounded-0"'
                                + '<span>답변중</span>'
                                + '<span style="margin-left: 100px;">'+$('#qanda_title').val()+'</span>'
                                + '<span style="float: right;"><?=$login_id?></span></a></h4>'
                                + '</div>'
                                + '<div id="collapse'+cntQandA+'" aria-labelledby="heading'+cntQandA+' data-parent=#accordion_qanda" class="collapse" >'
                                + '<div class="card-body contents" style=" margin-left: 150px;" >'
                                + $('#qanda_contents').val()
                                + '</div>'
                                + '<div class="navbar-buttons" align="right" style="display: flex;">'
                                + '<div style="flex: 11; margin: 3px;" id="btn_modify" class="navbar-collapse collapse d-none d-lg-block"><a href="/mall/updateNoticeOrFaq.php?SEQ=1" class="btn btn-primary navbar-btn">수정</a></div>'
                                + '<div style="flex: 1; margin: 3px;" id="btn_delete" class="navbar-collapse collapse d-none d-lg-block"><a  href="/mall/php/deleteNoticeOrFaqCompl.php?SEQ=1&TYPE=2" class="btn btn-primary navbar-btn">삭제</a></div>'
                                + '</div>'
                                + '</div>'
                                + '</div>';

                            console.log(qanda);

                            $('#qanda0').before(qanda);

                            // 모달에 작성한 데이터 제거
                            $('#qanda_title').val('');
                            $('#qanda_contents').val('');
                            $('#secret_yn').prop("checked", false);

                            cntQandA++;

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
    </script>
</body>
</html>