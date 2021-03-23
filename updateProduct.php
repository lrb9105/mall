<?php
session_start();

$login_id = $_SESSION['LOGIN_ID'];

// 로그인 되어있지 않다면 메인화면으로 이동
if($login_id == null || $login_id == ''){
    echo "<script> document.location.href='index.php'</script>";
}

$seq = $_GET['SEQ'];

// mysql커넥션 연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$product_no = $_GET['product_no'];

/* 데이터 가져오기 */
/* -------------------- 상품정보 ------------------*/
$sqlProductInfo = "SELECT P.PRODUCT_SEQ,
                P.FIRST_CATEGORY, 
                (SELECT MENU_NAME FROM MENU WHERE MENU_ID = P.FIRST_CATEGORY) FIRST_CATEGORY_NAME,
                P.SECOND_CATEGORY,
                (SELECT MENU_NAME FROM MENU WHERE MENU_ID = P.SECOND_CATEGORY) SECOND_CATEGORY_NAME,
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

// 상품 수량 정보
$sqlProductNumInfo = "SELECT  PO.SIZE
                            , PO.COLOR
                            , PO.QUANTITY
        FROM PRODUCT_OPTION PO
        WHERE PO.PRODUCT_SEQ = $product_no
        ";

$resultProductNumInfo = mysqli_query($conn, $sqlProductNumInfo);;
$countProductNumInfo = mysqli_num_rows($resultProductNumInfo);

// 상품 이미지 정보
$sqlFileInfo = "SELECT F.SEQ,
                       F.REF_SEQ,
                       F.SAVE_PATH,
                       F.FILE_NAME_ORIGIN
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
$countSizeInfo = mysqli_num_rows($resultSizeInfo);

// 모델정보
$sqlModelInfo = "SELECT PRODUCT_SEQ 
                , MODEL_HEIGHT 
                , MODEL_WEIGHT 
                , MODEL_SIZE
        FROM PRODUCT_MODEL_SIZE
        WHERE PRODUCT_SEQ = $product_no
        ";
$resultModelInfo = mysqli_query($conn, $sqlModelInfo);
$countModelInfo = mysqli_num_rows($resultModelInfo);


/* -------------------- 상품정보 ------------------*/



// 제조자가져오기
$sqlManufacture = "SELECT DISTINCT MANUFACTURER
                   FROM PRODUCT 
                   WHERE MANUFACTURER != ''
            ";
$resultManufacture = mysqli_query($conn, $sqlManufacture);
$countManufacture = mysqli_num_rows($resultManufacture);

// 제조국 가져오기
$sqlCountryOfManufacture = "SELECT DISTINCT COUNTRY_OF_MANUFACTURER
                            FROM PRODUCT 
                            WHERE COUNTRY_OF_MANUFACTURER != ''
            ";
$resultCountryOfManufacture = mysqli_query($conn, $sqlCountryOfManufacture);
$countCountryOfManufacture = mysqli_num_rows($resultCountryOfManufacture);

// 색상 가져오기
$sqlColor = "SELECT DISTINCT COLOR 
                            FROM PRODUCT_OPTION 
                            WHERE COLOR != ''
            ";
$resultColor = mysqli_query($conn, $sqlColor);
$countColor = mysqli_num_rows($resultColor);

// 사이즈 가져오기
$sqlSize = "SELECT DISTINCT SIZE  
                            FROM PRODUCT_OPTION 
                            WHERE SIZE  != ''
            ";
$resultSize = mysqli_query($conn, $sqlSize);
$countSize = mysqli_num_rows($resultSize);
?>

<!DOCTYPE html>
<html>
<?php
include 'head.php'
?>
<style>
    .item_title {
        padding: 0px;
        margin: 0;
        width: 10%;
        text-align: center;
        font-weight: bold;
        background-color: #4FBFA8;
        color: #FFFFFF;
    }

    .item_title_inner{
        padding: 0px;
        margin: 0;
        width: 10%;
        text-align: center;
        font-weight: bold;
        background-color: #61b977;
        color: #FFFFFF;
        border: 1px solid white;
    }
    .upper{
        border-top: 1px solid black;
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
                                <li class="breadcrumb-item"><a href="#">관리자</a></li>
                                <li class="breadcrumb-item"><a href="#">상품</a></li>
                                <li aria-current="page" class="breadcrumb-item active">상품 수정</li>
                            </ol>
                        </nav>
                    </div>
                    <div id="board" class="col-lg-12">
                        <div class="box">
                            <!-- Contact Section Begin -->
                            <section class="contact spad">
                                <form method="POST" action="/mall/php/product/updateProductCompl.php" enctype="multipart/form-data" onsubmit="return confirm('상품을 수정하시겠습니까');)">
                                    <input hidden name="product_no" value="<?=$product_no?>">
                                <div class="container">
                                    <table class="table">
                                        <tr>
                                            <td height=20 align=center bgcolor=#ccc style="size: 20px;">상품 수정</td>
                                        </tr>
                                        <tr>
                                            <td bgcolor=white>
                                                <table class="table">
                                                    <tr >
                                                        <td class="item_title">상품명</td>
                                                        <td colspan="5">
                                                            <input class="form-control " type="text" name="product_name" id="product_name" value="<?=$rowProductInfo['PRODUCT_NAME']?>">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title">상품 1차분류</td>
                                                        <td>
                                                            <div class="form-control">
                                                                <?=$rowProductInfo['FIRST_CATEGORY_NAME']?>
                                                                <input name="first_category" id="first_category" hidden value="<?=$rowProductInfo['FIRST_CATEGORY']?>">
                                                            </div>
                                                        </td>
                                                        <td class="item_title">상품 2차분류</td>
                                                        <td>
                                                            <div class="form-control">
                                                                <?=$rowProductInfo['SECOND_CATEGORY_NAME']?>
                                                                <input name="second_category" id="second_category" hidden value="<?=$rowProductInfo['SECOND_CATEGORY']?>">
                                                            </div>
                                                        </td>
                                                        <td class="item_title">제조자</td>
                                                        <td>
                                                            <select class="form-control"  name="product_manufacture" id="product_manufacture">
                                                                <option value="">[선택]</option>
                                                                <?for($j = 0; $j < $countManufacture; $j++){
                                                                    $rowManufacture = mysqli_fetch_array($resultManufacture);
                                                                    ?>
                                                                    <option value="<?echo $rowManufacture['MANUFACTURER']?>" <?if($rowProductInfo['MANUFACTURER'] == $rowManufacture['MANUFACTURER']){?>selected<?}?>><?echo $rowManufacture['MANUFACTURER']?></option>
                                                                <?}?>
                                                                <option id="product_manufacture_etc" value="etc">직접입력</option>
                                                            </select>
                                                            <input class="form-control" id="product_manufacture_input" name="product_manufacture_input" placeholder="제조자를 입력하세요.">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title">기존가격</td>
                                                        <td>
                                                            <input  class="form-control" type="text" name="product_price" id="product_price" value="<?echo number_format($rowProductInfo['PRODUCT_PRICE'])?>">
                                                        </td>
                                                        <td class="item_title">할인가격</td>
                                                        <td>
                                                            <input style="float: left" class="form-control" type="text" name="product_price_sale" id="product_price_sale" value="<?echo number_format($rowProductInfo['PRODUCT_PRICE_SALE'])?>">
                                                        </td>
                                                        <td class="item_title">제조국</td>
                                                        <td>
                                                            <select class="form-control"  name="country_of_manufacture" id="country_of_manufacture">
                                                                <option value="">[선택]</option>
                                                                <?for($j = 0; $j < $countCountryOfManufacture; $j++){
                                                                    $rowCountryOfManufacture = mysqli_fetch_array($resultCountryOfManufacture);
                                                                    ?>
                                                                    <option value="<?echo $rowCountryOfManufacture['COUNTRY_OF_MANUFACTURER']?>" <?if($rowProductInfo['COUNTRY_OF_MANUFACTURER'] == $rowCountryOfManufacture['COUNTRY_OF_MANUFACTURER']){?>selected<?}?>><?echo $rowCountryOfManufacture['COUNTRY_OF_MANUFACTURER']?></option>
                                                                <?}?>
                                                                <option id="country_of_manufacture_etc" value="etc">직접입력</option>
                                                            </select>
                                                            <input class="form-control" id="country_of_manufacture_input" name="country_of_manufacture_input" placeholder="제조국를 입력하세요.">
                                                        </td>
                                                    </tr>
                                                    <tr >
                                                        <td class="item_title">상품소재</td>
                                                        <td colspan="5">
                                                            <input class="form-control " type="text" name="product_material" id="product_material" value="<?echo $rowProductInfo['MATERIAL']?>">
                                                        </td>
                                                    </tr>
                                                    <tr id="tr_add">
                                                        <td class="item_title">세탁방법 및 취급 시 주의사항</td>
                                                        <td colspan="5">
                                                            <textarea class="form-control " name="cleaning_method" id="cleaning_method" rows="5"><?echo $rowProductInfo['CLEANING_METHOD']?></textarea>
                                                        </td>
                                                    </tr>
                                                    <!-- 카테고리에 따라 넣어야 할 값 달라지고 그 부분은 여기에 입력하기-->
                                                    <tr>
                                                        <td class="item_title" colspan="6">상품 수량정보(색상, 사이즈, 수량)<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="product_info_add" class="btn btn-dark">상품정보 추가</button>&nbsp;<button type="button" id="product_info_delete" class="btn btn-warning">상품정보 삭제</button></span></td>
                                                    </tr>
                                                    <? $i=0;
                                                    while($rowProductNumInfo = mysqli_fetch_array($resultProductNumInfo)){?>
                                                        <tr id="tr_product_info_<?=$i?>">
                                                            <td class="item_title">색상</td>
                                                            <td>
                                                                <select class="form-control"  name="product_color[]" id="product_color">
                                                                    <option value="">[선택]</option>
                                                                    <option value="화이트" <?if($rowProductNumInfo['COLOR'] == '화이트'){?>selected<?}?>>화이트</option>
                                                                    <option value="레드" <?if($rowProductNumInfo['COLOR'] == '레드'){?>selected<?}?>>레드</option>
                                                                    <option value="블랙" <?if($rowProductNumInfo['COLOR'] == '블랙'){?>selected<?}?>>블랙</option>
                                                                    <option value="오렌지" <?if($rowProductNumInfo['COLOR'] == '오렌지'){?>selected<?}?>>오렌지</option>
                                                                    <option value="블루" <?if($rowProductNumInfo['COLOR'] == '블루'){?>selected<?}?>>블루</option>
                                                                    <option value="옐로우" <?if($rowProductNumInfo['COLOR'] == '옐로우'){?>selected<?}?>>옐로우</option>
                                                                    <option value="그린" <?if($rowProductNumInfo['COLOR'] == '그린'){?>selected<?}?>>그린</option>
                                                                    <option value="네이비" <?if($rowProductNumInfo['COLOR'] == '네이비'){?>selected<?}?>>네이비</option>
                                                                    <option value="그레이" <?if($rowProductNumInfo['COLOR'] == '그레이'){?>selected<?}?>>그레이</option>
                                                                    <option value="베이지" <?if($rowProductNumInfo['COLOR'] == '베이지'){?>selected<?}?>>베이지</option>
                                                                    <option value="카키" <?if($rowProductNumInfo['COLOR'] == '카키'){?>selected<?}?>>카키</option>
                                                                    <option value="브라운" <?if($rowProductNumInfo['COLOR'] == '브라운'){?>selected<?}?>>브라운</option>
                                                                </select>
                                                            </td>
                                                            <td class="item_title">사이즈</td>
                                                            <td>
                                                                <select class="form-control"  name="product_size[]" id="product_size">
                                                                    <? //신발 제외
                                                                    if($rowProductInfo['FIRST_CATEGORY'] != 26) {?>
                                                                        <option value="">[선택]</option>
                                                                        <option value="S" <?if($rowProductNumInfo['SIZE'] == 'S'){?>selected<?}?>>S</option>
                                                                        <option value="M" <?if($rowProductNumInfo['SIZE'] == 'M'){?>selected<?}?>>M</option>
                                                                        <option value="L" <?if($rowProductNumInfo['SIZE'] == 'L'){?>selected<?}?>>L</option>
                                                                        <option value="XL" <?if($rowProductNumInfo['SIZE'] == 'XL'){?>selected<?}?>>XL</option>
                                                                        <option value="2XL" <?if($rowProductNumInfo['SIZE'] == '2XL'){?>selected<?}?>>2XL</option>
                                                                        <option value="3XL" <?if($rowProductNumInfo['SIZE'] == '3XL'){?>selected<?}?>>3XL</option>
                                                                        <option value="4XL" <?if($rowProductNumInfo['SIZE'] == '4XL'){?>selected<?}?>>4XL</option>
                                                                        <option value="FREE" <?if($rowProductNumInfo['SIZE'] == 'FREE'){?>selected<?}?>>FREE</option>
                                                                    <?} else{ ?>
                                                                        <option value=''>[선택]</option>
                                                                        <option value='220' <?if($rowProductNumInfo['SIZE'] == '220'){?>selected<?}?>>220</option>
                                                                        <option value='225' <?if($rowProductNumInfo['SIZE'] == '225'){?>selected<?}?>>225</option>
                                                                        <option value='230' <?if($rowProductNumInfo['SIZE'] == '230'){?>selected<?}?>>230</option>
                                                                        <option value='235' <?if($rowProductNumInfo['SIZE'] == '235'){?>selected<?}?>>235</option>
                                                                        <option value='240' <?if($rowProductNumInfo['SIZE'] == '240'){?>selected<?}?>>240</option>
                                                                        <option value='245' <?if($rowProductNumInfo['SIZE'] == '245'){?>selected<?}?>>245</option>
                                                                        <option value='250' <?if($rowProductNumInfo['SIZE'] == '250'){?>selected<?}?>>250</option>
                                                                        <option value='255' <?if($rowProductNumInfo['SIZE'] == '255'){?>selected<?}?>>255</option>
                                                                        <option value='260' <?if($rowProductNumInfo['SIZE'] == '260'){?>selected<?}?>>260</option>
                                                                        <option value='265' <?if($rowProductNumInfo['SIZE'] == '265'){?>selected<?}?>>265</option>
                                                                        <option value='270' <?if($rowProductNumInfo['SIZE'] == '270'){?>selected<?}?>>270</option>
                                                                        <option value='275' <?if($rowProductNumInfo['SIZE'] == '275'){?>selected<?}?>>275</option>
                                                                        <option value='280' <?if($rowProductNumInfo['SIZE'] == '280'){?>selected<?}?>>280</option>
                                                                        <option value='285' <?if($rowProductNumInfo['SIZE'] == '285'){?>selected<?}?>>285</option>
                                                                        <option value='290' <?if($rowProductNumInfo['SIZE'] == '290'){?>selected<?}?>>290</option>
                                                                        <option value='295' <?if($rowProductNumInfo['SIZE'] == '295'){?>selected<?}?>>295</option>
                                                                        <option value='300' <?if($rowProductNumInfo['SIZE'] == '300'){?>selected<?}?>>300</option>
                                                                    <?}?>
                                                                </select>
                                                            </td>
                                                            <td class="item_title">수량</td>
                                                            <td>
                                                                <input class="form-control " type="number" name="product_number[]" id="product_number" value="<?=$rowProductNumInfo['QUANTITY']?>">
                                                            </td>
                                                        </tr>
                                                    <? $i++;
                                                    }?>
                                                    <tr>
                                                        <td class="item_title" colspan="6">제품이미지<!--<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="img_add" class="btn btn-dark">상세이미지 추가</button></span>--></td>
                                                    </tr>
                                                    <tr id="tr_img">
                                                        <td class="item_title">대표 이미지</td>
                                                        <td colspan="5">
                                                            <input type="file" name="file_represent" id="file_represent" accept="image/*" style="display: none;">
                                                            <label for="file_represent" class="btn btn-info fileBtn">파일선택</label>
                                                            <span style="width: 50%; display: inline-block;" id="repFileName" class="form-control"><?=$rowFileInfo['FILE_NAME_ORIGIN']?></span>
                                                            <div style="margin-top: 10px;">
                                                                <img id="img_represent" width="200px;" src="<?=$repImgSrc?>">
                                                            </div>
                                                        </td><script>
                                                            document.getElementById('file_represent').addEventListener('change', function(){
                                                                let filename = document.getElementById('repFileName');
                                                                if(this.files[0] == undefined){
                                                                    filename.innerText = '선택된 파일없음';
                                                                    return;
                                                                }
                                                                filename.innerText = this.files[0].name;
                                                            });
                                                        </script>
                                                    </tr>
                                                    <tr>
                                                        <td class="item_title">상세 이미지</td>
                                                        <td colspan="5">
                                                            <input type="file" name="file_detail[]" id="file_detail" accept="image/*" style="display: none;" multiple>
                                                            <label for="file_detail" class="btn btn-info fileBtn">파일선택</label>
                                                            <span style="width: 50%; display: inline-block;" id="detailFileName" class="form-control">
                                                                <? $cntFileDetail = mysqli_num_rows($resultFileInfo) - 1; //대표이미지는 무조건 있으므로! - 1 해줌
                                                                if($cntFileDetail == 0) {?>
                                                                    선택된 파일 없음
                                                                <?} else {?>
                                                                    파일 <?=$cntFileDetail?>개
                                                                <?}?>
                                                            </span>
                                                            <div id="img_detail" style="margin-top: 10px;">
                                                                <?while($rowFileInfo = mysqli_fetch_array($resultFileInfo)) { ?>
                                                                    <img class="img_detail" src="<?=$rowFileInfo['SAVE_PATH']?>" width="200px;"/>
                                                                <?}?>
                                                            </div>
                                                        </td><script>
                                                            document.getElementById('file_detail').addEventListener('change', function(){
                                                                let filename = document.getElementById('detailFileName');
                                                                if(this.files[0] == undefined){
                                                                    filename.innerText = '선택된 파일없음';
                                                                    return;
                                                                }
                                                                filename.innerText = '파일' + this.files.length + '개' ;
                                                            });
                                                        </script>
                                                    </tr>
                                                    <?if($rowProductInfo['FIRST_CATEGORY'] != 27) {?>
                                                        <tr id="model_info">
                                                            <td class="tr_model_info item_title" colspan="6">모델 정보<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="model_info_add" class="btn btn-dark">모델정보 추가</button>&nbsp;<button type="button" id="model_info_delete" class="btn btn-warning">모델정보 삭제</button></span></td>
                                                        </tr>
                                                    <?}?>
                                                    <? $i=0;
                                                    while($rowModelInfo = mysqli_fetch_array($resultModelInfo)){?>
                                                        <tr class="tr_model_info" id="tr_model_info_<?=$i?>">
                                                            <td class="item_title">키(cm)</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="model_height[]" value="<?=$rowModelInfo['MODEL_HEIGHT']?>">
                                                            </td>
                                                            <td class="item_title">몸무게(kg)</td>
                                                            <td>
                                                                <input type="text" class="form-control" name="model_weight[]" value="<?=$rowModelInfo['MODEL_WEIGHT']?>">
                                                            </td>
                                                            <td class="item_title">착용사이즈</td>
                                                            <td>
                                                                <select class="form-control"  name="model_size[]" id="model_size">'
                                                                    <? //신발 제외
                                                                    if($rowProductInfo['FIRST_CATEGORY'] != 26) {?>
                                                                        <option value="">[선택]</option>
                                                                        <option value="S" <?if($rowModelInfo['MODEL_SIZE'] == 'S'){?>selected<?}?>>S</option>
                                                                        <option value="M" <?if($rowModelInfo['MODEL_SIZE'] == 'M'){?>selected<?}?>>M</option>
                                                                        <option value="L" <?if($rowModelInfo['MODEL_SIZE'] == 'L'){?>selected<?}?>>L</option>
                                                                        <option value="XL" <?if($rowModelInfo['MODEL_SIZE'] == 'XL'){?>selected<?}?>>XL</option>
                                                                        <option value="2XL" <?if($rowModelInfo['MODEL_SIZE'] == '2XL'){?>selected<?}?>>2XL</option>
                                                                        <option value="3XL" <?if($rowModelInfo['MODEL_SIZE'] == '3XL'){?>selected<?}?>>3XL</option>
                                                                        <option value="4XL" <?if($rowModelInfo['MODEL_SIZE'] == '4XL'){?>selected<?}?>>4XL</option>
                                                                        <option value="FREE" <?if($rowModelInfo['MODEL_SIZE'] == 'FREE'){?>selected<?}?>>FREE</option>
                                                                    <?} else{ ?>
                                                                        <option value=''>[선택]</option>
                                                                        <option value='220' <?if($rowModelInfo['MODEL_SIZE'] == '220'){?>selected<?}?>>220</option>
                                                                        <option value='225' <?if($rowModelInfo['MODEL_SIZE'] == '225'){?>selected<?}?>>225</option>
                                                                        <option value='230' <?if($rowModelInfo['MODEL_SIZE'] == '230'){?>selected<?}?>>230</option>
                                                                        <option value='235' <?if($rowModelInfo['MODEL_SIZE'] == '235'){?>selected<?}?>>235</option>
                                                                        <option value='240' <?if($rowModelInfo['MODEL_SIZE'] == '240'){?>selected<?}?>>240</option>
                                                                        <option value='245' <?if($rowModelInfo['MODEL_SIZE'] == '245'){?>selected<?}?>>245</option>
                                                                        <option value='250' <?if($rowModelInfo['MODEL_SIZE'] == '250'){?>selected<?}?>>250</option>
                                                                        <option value='255' <?if($rowModelInfo['MODEL_SIZE'] == '255'){?>selected<?}?>>255</option>
                                                                        <option value='260' <?if($rowModelInfo['MODEL_SIZE'] == '260'){?>selected<?}?>>260</option>
                                                                        <option value='265' <?if($rowModelInfo['MODEL_SIZE'] == '265'){?>selected<?}?>>265</option>
                                                                        <option value='270' <?if($rowModelInfo['MODEL_SIZE'] == '270'){?>selected<?}?>>270</option>
                                                                        <option value='275' <?if($rowModelInfo['MODEL_SIZE'] == '275'){?>selected<?}?>>275</option>
                                                                        <option value='280' <?if($rowModelInfo['MODEL_SIZE'] == '280'){?>selected<?}?>>280</option>
                                                                        <option value='285' <?if($rowModelInfo['MODEL_SIZE'] == '285'){?>selected<?}?>>285</option>
                                                                        <option value='290' <?if($rowModelInfo['MODEL_SIZE'] == '290'){?>selected<?}?>>290</option>
                                                                        <option value='295' <?if($rowModelInfo['MODEL_SIZE'] == '295'){?>selected<?}?>>295</option>
                                                                        <option value='300' <?if($rowModelInfo['MODEL_SIZE'] == '300'){?>selected<?}?>>300</option>
                                                                    <?}?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <? $i++;
                                                    }?>

                                                    <? //상의
                                                    if($rowProductInfo['FIRST_CATEGORY'] == 2) {?>
                                                        <tr>
                                                            <td class="item_title added_info">치수정보</td>
                                                            <td colspan="5" class="added_info">
                                                                <table class="table" style="border: 1px solid black">
                                                                    <tr >
                                                                        <td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보 추가<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-dark">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-warning">치수정보 삭제</button></span></td>
                                                                    </tr>
                                                                    <tr >
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">어깨길이</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">가슴둘레</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">암홀</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">팔길이</td>
                                                                        <td class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">총길이</td>
                                                                    </tr>
                                                                    <?$i = 0;
                                                                    while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                                        <tr id="tr_size_info_<?=$i?>">
                                                                            <td>
                                                                                <select class="form-control"  name="size[]" id="size">
                                                                                    <option value="">[선택]</option>
                                                                                    <option value="S" <?if($rowSizeInfo['SIZE'] == 'S'){?>selected<?}?>>S</option>
                                                                                    <option value="M" <?if($rowSizeInfo['SIZE'] == 'M'){?>selected<?}?>>M</option>
                                                                                    <option value="L" <?if($rowSizeInfo['SIZE'] == 'L'){?>selected<?}?>>L</option>
                                                                                    <option value="XL" <?if($rowSizeInfo['SIZE'] == 'XL'){?>selected<?}?>>XL</option>
                                                                                    <option value="2XL" <?if($rowSizeInfo['SIZE'] == '2XL'){?>selected<?}?>>2XL</option>
                                                                                    <option value="3XL" <?if($rowSizeInfo['SIZE'] == '3XL'){?>selected<?}?>>3XL</option>
                                                                                    <option value="4XL" <?if($rowSizeInfo['SIZE'] == '4XL'){?>selected<?}?>>4XL</option>
                                                                                    <option value="FREE" <?if($rowSizeInfo['SIZE'] == 'FREE'){?>selected<?}?>>FREE</option>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control " type="text" name="TOP_SHOULDER_SIZE[]" id="TOP_SHOULDER_SIZE" value="<?=$rowSizeInfo['TOP_SHOULDER_SIZE']?>">
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control " type="text" name="TOP_CHEST_SIZE[]" id="TOP_CHEST_SIZE" value="<?=$rowSizeInfo['TOP_CHEST_SIZE']?>">
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control " type="text" name="TOP_ARMHOLE_SIZE[]" id="TOP_ARMHOLE_SIZE" value="<?=$rowSizeInfo['TOP_ARMHOLE_SIZE']?>">
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control " type="text" name="TOP_ARM_SIZE[]" id="TOP_ARM_SIZE" value="<?=$rowSizeInfo['TOP_ARM_SIZE']?>">
                                                                            </td>
                                                                            <td>
                                                                                <input class="form-control " type="text" name="TOP_TOTAL_LENGTH[]" id="TOP_TOTAL_LENGTH" value="<?=$rowSizeInfo['TOP_TOTAL_LENGTH']?>">
                                                                            </td>
                                                                        </tr>
                                                                    <? $i++;
                                                                    }?>
                                                                    <tr >
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">핏</td>
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">두깨감</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">신축성</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">비침</td>
                                                                        <td colspan="2" class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">계절</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select class="form-control"  name="fit" id="fit">
                                                                                <option value="">[선택]</option>
                                                                                <option value="스탠다드" <?if($rowProductInfo['FIT'] == '스탠다드'){?>selected<?}?>>스탠다드</option>
                                                                                <option value="세미오버" <?if($rowProductInfo['FIT'] == '세미오버'){?>selected<?}?>>세미오버</option>
                                                                                <option value="오버" <?if($rowProductInfo['FIT'] == '오버'){?>selected<?}?>>오버</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="thickness" id="thickness">
                                                                                <option value="">[선택]</option>
                                                                                <option value="두꺼움" <?if($rowProductInfo['THICKNESS'] == '두꺼움'){?>selected<?}?>>두꺼움</option>
                                                                                <option value="보통" <?if($rowProductInfo['THICKNESS'] == '보통'){?>selected<?}?>>보통</option>
                                                                                <option value="얇음" <?if($rowProductInfo['THICKNESS'] == '얇음'){?>selected<?}?>>얇음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="elasticity" id="elasticity">
                                                                                <option value="">[선택]</option>
                                                                                <option value="좋음" <?if($rowProductInfo['ELASTICITY'] == '좋음'){?>selected<?}?>>좋음</option>
                                                                                <option value="약간" <?if($rowProductInfo['ELASTICITY'] == '약간'){?>selected<?}?>>약간</option>
                                                                                <option value="없음" <?if($rowProductInfo['ELASTICITY'] == '없음'){?>selected<?}?>>없음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="reflection" id="reflection">
                                                                                <option value="">[선택]</option>
                                                                                <option value="비침" <?if($rowProductInfo['REFLECTION'] == '비침'){?>selected<?}?>>비침</option>
                                                                                <option value="약간" <?if($rowProductInfo['REFLECTION'] == '약간'){?>selected<?}?>>약간</option>
                                                                                <option value="없음" <?if($rowProductInfo['REFLECTION'] == '없음'){?>selected<?}?>>없음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <select class="form-control"  name="season" id="season">
                                                                                <option value="">[선택]</option>
                                                                                <option value="봄/가을" <?if($rowProductInfo['SEASON'] == '봄/가을'){?>selected<?}?>>봄/가을</option>
                                                                                <option value="여름" <?if($rowProductInfo['SEASON'] == '여름'){?>selected<?}?>>여름</option>
                                                                                <option value="겨울" <?if($rowProductInfo['SEASON'] == '겨울'){?>selected<?}?>>겨울</option>
                                                                                <option value="사계절" <?if($rowProductInfo['SEASON'] == '사계절'){?>selected<?}?>>사계절</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?} elseif($rowProductInfo['FIRST_CATEGORY'] == 3){ //아우터?>
                                                        <tr>
                                                            <td class="item_title added_info">치수정보</td>
                                                            <td colspan="5" class="added_info">
                                                                <table class="table" style="border: 1px solid black">
                                                                    <tr >
                                                                        <td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보 추가<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-dark">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-warning">치수정보 삭제</button></span></td>
                                                                    </tr>
                                                                    <tr >
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">총장</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">어깨너비</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">가슴단면</td>
                                                                        <td colspan="2" class="item_title_inner upper" style="border-top: 1px solid black;">소매길이</td>
                                                                    </tr>
                                                                    <?$i = 0;
                                                                    while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                                    <tr id="tr_size_info_<?=$i?>">
                                                                        <td>
                                                                            <select class="form-control"  name="size[]" id="size">
                                                                                <option value="">[선택]</option>
                                                                                <option value="S" <?if($rowSizeInfo['SIZE'] == 'S'){?>selected<?}?>>S</option>
                                                                                <option value="M" <?if($rowSizeInfo['SIZE'] == 'M'){?>selected<?}?>>M</option>
                                                                                <option value="L" <?if($rowSizeInfo['SIZE'] == 'L'){?>selected<?}?>>L</option>
                                                                                <option value="XL" <?if($rowSizeInfo['SIZE'] == 'XL'){?>selected<?}?>>XL</option>
                                                                                <option value="2XL" <?if($rowSizeInfo['SIZE'] == '2XL'){?>selected<?}?>>2XL</option>
                                                                                <option value="3XL" <?if($rowSizeInfo['SIZE'] == '3XL'){?>selected<?}?>>3XL</option>
                                                                                <option value="4XL" <?if($rowSizeInfo['SIZE'] == '4XL'){?>selected<?}?>>4XL</option>
                                                                                <option value="FREE" <?if($rowSizeInfo['SIZE'] == 'FREE'){?>selected<?}?>>FREE</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="OUTER_TOTAL_LENGTH[]" id="OUTER_TOTAL_LENGTH" value="<?=$rowSizeInfo['OUTER_TOTAL_LENGTH']?>">
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="OUTER_SHOULDER_SIZE[]" id="OUTER_SHOULDER_SIZE" value="<?=$rowSizeInfo['OUTER_SHOULDER_SIZE']?>">
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="OUTER__CHEST_SIZE[]" id="OUTER__CHEST_SIZE" value="<?=$rowSizeInfo['OUTER__CHEST_SIZE']?>">
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <input class="form-control " type="text" name="OUTER_SLEEVE_LENGTH[]" id="OUTER_SLEEVE_LENGTH" value="<?=$rowSizeInfo['OUTER_SLEEVE_LENGTH']?>">
                                                                        </td>
                                                                    </tr>
                                                                    <? $i++;
                                                                    }?>
                                                                    <tr >
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">핏</td>
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">두깨감</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">신축성</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">비침</td>
                                                                        <td colspan="2" class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">계절</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select class="form-control"  name="fit" id="fit">
                                                                                <option value="">[선택]</option>
                                                                                <option value="스탠다드" <?if($rowProductInfo['FIT'] == '스탠다드'){?>selected<?}?>>스탠다드</option>
                                                                                <option value="세미오버" <?if($rowProductInfo['FIT'] == '세미오버'){?>selected<?}?>>세미오버</option>
                                                                                <option value="오버" <?if($rowProductInfo['FIT'] == '오버'){?>selected<?}?>>오버</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="thickness" id="thickness">
                                                                                <option value="">[선택]</option>
                                                                                <option value="두꺼움" <?if($rowProductInfo['THICKNESS'] == '두꺼움'){?>selected<?}?>>두꺼움</option>
                                                                                <option value="보통" <?if($rowProductInfo['THICKNESS'] == '보통'){?>selected<?}?>>보통</option>
                                                                                <option value="얇음" <?if($rowProductInfo['THICKNESS'] == '얇음'){?>selected<?}?>>얇음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="elasticity" id="elasticity">
                                                                                <option value="">[선택]</option>
                                                                                <option value="좋음" <?if($rowProductInfo['ELASTICITY'] == '좋음'){?>selected<?}?>>좋음</option>
                                                                                <option value="약간" <?if($rowProductInfo['ELASTICITY'] == '약간'){?>selected<?}?>>약간</option>
                                                                                <option value="없음" <?if($rowProductInfo['ELASTICITY'] == '없음'){?>selected<?}?>>없음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="reflection" id="reflection">
                                                                                <option value="">[선택]</option>
                                                                                <option value="비침" <?if($rowProductInfo['REFLECTION'] == '비침'){?>selected<?}?>>비침</option>
                                                                                <option value="약간" <?if($rowProductInfo['REFLECTION'] == '약간'){?>selected<?}?>>약간</option>
                                                                                <option value="없음" <?if($rowProductInfo['REFLECTION'] == '없음'){?>selected<?}?>>없음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td colspan="2">
                                                                            <select class="form-control"  name="season" id="season">
                                                                                <option value="">[선택]</option>
                                                                                <option value="봄/가을" <?if($rowProductInfo['SEASON'] == '봄/가을'){?>selected<?}?>>봄/가을</option>
                                                                                <option value="여름" <?if($rowProductInfo['SEASON'] == '여름'){?>selected<?}?>>여름</option>
                                                                                <option value="겨울" <?if($rowProductInfo['SEASON'] == '겨울'){?>selected<?}?>>겨울</option>
                                                                                <option value="사계절" <?if($rowProductInfo['SEASON'] == '사계절'){?>selected<?}?>>사계절</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?} elseif($rowProductInfo['FIRST_CATEGORY'] == 4){ // 하의?>
                                                        <tr>
                                                            <td class="item_title added_info">치수정보</td>
                                                            <td colspan="5" class="added_info">
                                                                <table class="table" style="border: 1px solid black">
                                                                    <tr >
                                                                        <td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보 추가<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-dark">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-warning">치수정보 삭제</button></span></td>
                                                                    </tr>
                                                                    <tr >
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">허리단면</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">총기장</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">허벅지단면</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">밑단단면</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">밑위</td>
                                                                    </tr>
                                                                    <?$i = 0;
                                                                    while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                                    <tr id="tr_size_info_<?=$i?>">
                                                                        <td>
                                                                            <select class="form-control"  name="size[]" id="size">
                                                                                <option value="">[선택]</option>
                                                                                <option value="S" <?if($rowSizeInfo['SIZE'] == 'S'){?>selected<?}?>>S</option>
                                                                                <option value="M" <?if($rowSizeInfo['SIZE'] == 'M'){?>selected<?}?>>M</option>
                                                                                <option value="L" <?if($rowSizeInfo['SIZE'] == 'L'){?>selected<?}?>>L</option>
                                                                                <option value="XL" <?if($rowSizeInfo['SIZE'] == 'XL'){?>selected<?}?>>XL</option>
                                                                                <option value="2XL" <?if($rowSizeInfo['SIZE'] == '2XL'){?>selected<?}?>>2XL</option>
                                                                                <option value="3XL" <?if($rowSizeInfo['SIZE'] == '3XL'){?>selected<?}?>>3XL</option>
                                                                                <option value="4XL" <?if($rowSizeInfo['SIZE'] == '4XL'){?>selected<?}?>>4XL</option>
                                                                                <option value="FREE" <?if($rowSizeInfo['SIZE'] == 'FREE'){?>selected<?}?>>FREE</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="BOTTOM_WAIST_SIZE[]" id="BOTTOM_WAIST_SIZE" value="<?=$rowSizeInfo['BOTTOM_WAIST_SIZE']?>">
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="BOTTOM_TOTAL_LENGTH[]" id="BOTTOM_TOTAL_LENGTH" value="<?=$rowSizeInfo['BOTTOM_TOTAL_LENGTH']?>">
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="BOTTOM_THIGH_SIZE[]" id="BOTTOM_THIGH_SIZE" value="<?=$rowSizeInfo['BOTTOM_THIGH_SIZE']?>">
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="BOTTOM_HEM_SIZE[]" id="BOTTOM_HEM_SIZE" value="<?=$rowSizeInfo['BOTTOM_HEM_SIZE']?>">
                                                                        </td>
                                                                        <td>
                                                                            <input class="form-control " type="text" name="BOTTOM_RISE[]" id="BOTTOM_RISE" value="<?=$rowSizeInfo['BOTTOM_RISE']?>">
                                                                        </td>
                                                                    </tr>
                                                                    <? $i++;
                                                                    }?>
                                                                    <tr >
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">핏</td>
                                                                        <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">두깨감</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">신축성</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">비침</td>
                                                                        <td class="item_title_inner upper" style="border-top: 1px solid black;">촉감</td>
                                                                        <td class="item_title_inner upper" style="border-right: 1px solid black; border-top: 1px solid black;">계절</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <select class="form-control"  name="fit" id="fit">
                                                                                <option value="">[선택]</option>
                                                                                <option value="스탠다드" <?if($rowProductInfo['FIT'] == '스탠다드'){?>selected<?}?>>스탠다드</option>
                                                                                <option value="세미오버" <?if($rowProductInfo['FIT'] == '세미오버'){?>selected<?}?>>세미오버</option>
                                                                                <option value="오버" <?if($rowProductInfo['FIT'] == '오버'){?>selected<?}?>>오버</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="thickness" id="thickness">
                                                                                <option value="">[선택]</option>
                                                                                <option value="두꺼움" <?if($rowProductInfo['THICKNESS'] == '두꺼움'){?>selected<?}?>>두꺼움</option>
                                                                                <option value="보통" <?if($rowProductInfo['THICKNESS'] == '보통'){?>selected<?}?>>보통</option>
                                                                                <option value="얇음" <?if($rowProductInfo['THICKNESS'] == '얇음'){?>selected<?}?>>얇음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="elasticity" id="elasticity">
                                                                                <option value="">[선택]</option>
                                                                                <option value="좋음" <?if($rowProductInfo['ELASTICITY'] == '좋음'){?>selected<?}?>>좋음</option>
                                                                                <option value="약간" <?if($rowProductInfo['ELASTICITY'] == '약간'){?>selected<?}?>>약간</option>
                                                                                <option value="없음" <?if($rowProductInfo['ELASTICITY'] == '없음'){?>selected<?}?>>없음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="reflection" id="reflection">
                                                                                <option value="">[선택]</option>
                                                                                <option value="비침" <?if($rowProductInfo['REFLECTION'] == '비침'){?>selected<?}?>>비침</option>
                                                                                <option value="약간" <?if($rowProductInfo['REFLECTION'] == '약간'){?>selected<?}?>>약간</option>
                                                                                <option value="없음" <?if($rowProductInfo['REFLECTION'] == '없음'){?>selected<?}?>>없음</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="touch" id="touch">
                                                                                <option value="">[선택]</option>
                                                                                <option value="부드러움" <?if($rowProductInfo['TOUCH'] == '부드러움'){?>selected<?}?>>부드러움</option>
                                                                                <option value="보통" <?if($rowProductInfo['TOUCH'] == '보통'){?>selected<?}?>>보통</option>
                                                                                <option value="뻣뻣함" <?if($rowProductInfo['TOUCH'] == '뻣뻣함'){?>selected<?}?>>뻣뻣함</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control"  name="season" id="season">
                                                                                <option value="">[선택]</option>
                                                                                <option value="봄/가을" <?if($rowProductInfo['SEASON'] == '봄/가을'){?>selected<?}?>>봄/가을</option>
                                                                                <option value="여름" <?if($rowProductInfo['SEASON'] == '여름'){?>selected<?}?>>여름</option>
                                                                                <option value="겨울" <?if($rowProductInfo['SEASON'] == '겨울'){?>selected<?}?>>겨울</option>
                                                                                <option value="사계절" <?if($rowProductInfo['SEASON'] == '사계절'){?>selected<?}?>>사계절</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    <?} elseif($rowProductInfo['FIRST_CATEGORY'] == 27){ // 모자?>
                                                    <tr>
                                                        <td class="item_title added_info">치수정보</td>
                                                        <td colspan="5" class="added_info">
                                                            <table class="table" style="border: 1px solid black">
                                                                <tr >
                                                                    <td colspan="6" class="item_title_inner upper" style="border-left: 1px solid black;border-right: 1px solid black; border-top: 1px solid black;">치수정보 추가<span>&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" id="size_info_add" class="btn btn-dark">치수정보 추가</button>&nbsp;<button type="button" id="size_info_delete" class="btn btn-warning">치수정보 삭제</button></span></td>
                                                                </tr>
                                                                <tr >
                                                                    <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">사이즈</td>
                                                                    <td class="item_title_inner upper" style="border-left: 1px solid black; border-top: 1px solid black;">둘레</td>
                                                                    <td class="item_title_inner upper" style="border-top: 1px solid black;">챙길이</td>
                                                                    <td class="item_title_inner upper" style="border-top: 1px solid black;">높이</td>
                                                                </tr>
                                                                <?$i = 0;
                                                                while($rowSizeInfo = mysqli_fetch_array($resultSizeInfo)){?>
                                                                <tr id="tr_size_info_<?=$i?>">
                                                                    <td>
                                                                        <select class="form-control"  name="size[]" id="size">
                                                                            <option value="">[선택]</option>
                                                                            <option value="S" <?if($rowSizeInfo['SIZE'] == 'S'){?>selected<?}?>>S</option>
                                                                            <option value="M" <?if($rowSizeInfo['SIZE'] == 'M'){?>selected<?}?>>M</option>
                                                                            <option value="L" <?if($rowSizeInfo['SIZE'] == 'L'){?>selected<?}?>>L</option>
                                                                            <option value="XL" <?if($rowSizeInfo['SIZE'] == 'XL'){?>selected<?}?>>XL</option>
                                                                            <option value="2XL" <?if($rowSizeInfo['SIZE'] == '2XL'){?>selected<?}?>>2XL</option>
                                                                            <option value="3XL" <?if($rowSizeInfo['SIZE'] == '3XL'){?>selected<?}?>>3XL</option>
                                                                            <option value="4XL" <?if($rowSizeInfo['SIZE'] == '4XL'){?>selected<?}?>>4XL</option>
                                                                            <option value="FREE" <?if($rowSizeInfo['SIZE'] == 'FREE'){?>selected<?}?>>FREE</option>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control " type="text" name="HAT_ROUND[]" id="HAT_ROUND" value="<?=$rowSizeInfo['HAT_ROUND']?>">
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control " type="text" name="HAT_LENGTH[]" id="HAT_LENGTH" value="<?=$rowSizeInfo['HAT_LENGTH']?>">
                                                                    </td>
                                                                    <td>
                                                                        <input class="form-control " type="text" name="HAT_HEIGHT[]" id="HAT_HEIGHT" value="<?=$rowSizeInfo['HAT_HEIGHT']?>">
                                                                    </td>
                                                                </tr>
                                                                <? $i++;
                                                                }?>
                                                            </table>
                                                    <?}?>
                                                    <tr id="detail_info">
                                                        <td class="item_title">상세정보</td>
                                                        <td colspan="5">
                                                            <textarea name="contents" id="contents" class="nse_content" style="width: 100%; height: 400px;"><?echo $rowProductInfo['DETAIL_INFO']?></textarea>
                                                            <script type="text/javascript">
                                                                var oEditors = [];
                                                                nhn.husky.EZCreator.createInIFrame({
                                                                    oAppRef: oEditors,
                                                                    elPlaceHolder: "contents",
                                                                    sSkinURI: "smart_editor2/SmartEditor2Skin.html",
                                                                    fCreator: "createSEditor2"
                                                                });
                                                                function submitContents(elClickedObj) {
                                                                    // 에디터의 내용이 textarea에 적용됩니다.
                                                                    oEditors.getById["contents"].exec("UPDATE_CONTENTS_FIELD", []);
                                                                    // 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("contents").value를 이용해서 처리하면 됩니다.
                                                                    try {
                                                                        elClickedObj.form.submit();
                                                                    } catch(e) {}
                                                                }
                                                            </script>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <div align="right">
                                        <!--<button id="btn_write" class="btn btn-primary navbar-btn">작성하기</button>-->
                                        <button id="" type="submit" onclick="submitContents(this)" class="btn btn-primary navbar-btn">상품수정</button>
                                    </div>
                                </form>
                            </section>
                            <!-- Contact Section End -->
                        <!-- /.box-->
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
            $(function (){
                $('#product_manufacture_input').hide();
                $('#country_of_manufacture_input').hide();
            });

            //제조자 기타 선택 시 직접입력
            $('#product_manufacture').change(function (){
                if($('#product_manufacture').val() == "etc"){
                    $('#product_manufacture_input').show();
                } else{
                    $('#product_manufacture_input').hide();
                    $('#product_manufacture_input').val('');
                }
            });

            //제조국 기타 선택 시 직접입력
            $('#country_of_manufacture').change(function (){
                if($('#country_of_manufacture').val() == "etc"){
                    $('#country_of_manufacture_input').show();
                } else{
                    $('#country_of_manufacture_input').hide();
                    $('#country_of_manufacture_input').val('');
                }
            });

            let productInfoCnt = <?=$countProductNumInfo?>;

            // 상품정보(색상, 사이즈, 수량 추가) 추가 버튼 클릭
            $('#product_info_add').on("click", function(){
                // 동적생성 - 색상, 사이즈 셀렉터 사용
                let trTop = '<tr id="tr_product_info_'+productInfoCnt+'">'
                        + '<td class="item_title">색상</td>'
                        + '<td>'
                            + '<select class="form-control"  name="product_color[]" id="product_color">'
                                + '<option value="">[선택]</option>'
                                + '<option value="화이트">화이트</option>'
                                + '<option value="레드">레드</option>'
                                + '<option value="블랙">블랙</option>'
                                + '<option value="오렌지">오렌지</option>'
                                + '<option value="블루">블루</option>'
                                + '<option value="옐로우">옐로우</option>'
                                + '<option value="그린">그린</option>'
                                + '<option value="네이비">네이비</option>'
                                + '<option value="그레이">그레이</option>'
                                + '<option value="베이지">베이지</option>'
                                + '<option value="베이지">카키</option>'
                                + '<option value="브라운">브라운</option>'
                            + '</select>'
                        + '</td>';
                let trBody= null;

                if($('#first_category').val() != '26'){
                    trBody = '<td class="item_title">사이즈</td>'
                    + '<td>'
                    + '<select class="form-control"  name="product_size[]" id="product_size">'
                    + '<option value="">[선택]</option>'
                    + '<option value="S">S</option>'
                    + '<option value="M">M</option>'
                    + '<option value="L">L</option>'
                    + '<option value="XL">XL</option>'
                    + '<option value="2XL">2XL</option>'
                    + '<option value="3XL">3XL</option>'
                    + '<option value="4XL">4XL</option>'
                    + '<option value="FREE">FREE</option>'
                    + '</select>'
                    + '</td>';
                } else{
                    trBody = '<td class="item_title">사이즈</td>'
                    + '<td>'
                    + '<select class="form-control"  name="product_size[]" id="product_size">'
                    +"<option value=''>[선택]</option>"
                    + "<option value='220'>220</option>"
                    + "<option value='225'>225</option>"
                    + "<option value='230'>230</option>"
                    + "<option value='235'>235</option>"
                    + "<option value='240'>240</option>"
                    + "<option value='245'>245</option>"
                    + "<option value='250'>250</option>"
                    + "<option value='255'>255</option>"
                    + "<option value='260'>260</option>"
                    + "<option value='265'>265</option>"
                    + "<option value='270'>270</option>"
                    + "<option value='275'>275</option>"
                    + "<option value='280'>280</option>"
                    + "<option value='285'>285</option>"
                    + "<option value='290'>290</option>"
                    + "<option value='295'>295</option>"
                    + "<option value='300'>300</option>"
                    + '</select>'
                    + '</td>';
                }

                let trBottom =
                  '<td class="item_title">수량</td>'
                + '<td>'
                    + '<input class="form-control " type="number" name="product_number[]" id="product_number">'
                + '</td>'
                + '</tr>';

                let tr = trTop + trBody + trBottom;


               $('#tr_product_info_'+(productInfoCnt-1)).after(tr);
                productInfoCnt++;
            });

            // 모델정보 추가
            let modelCnt = <?=$countModelInfo?>;

            $('#model_info_add').on("click", function(){
                let trTop = '<tr class="tr_model_info" id="tr_model_info_'+modelCnt+'">'
                    + '<td class="item_title">키(cm)</td>'
                    + '<td>'
                        + '<input type="text" class="form-control" name="model_height[]">'
                    + '</td>'
                    + '<td class="item_title">몸무게(kg)</td>'
                    + '<td>'
                        + '<input type="text" class="form-control" name="model_weight[]">'
                    + '</td>'
                let trBody= null;

                if($('#first_category').val() != '26'){
                    trBody = '<td class="item_title">사이즈</td>'
                        + '<td>'
                        + '<select class="form-control"  name="model_size[]" id="model_size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>';
                } else{
                    trBody = '<td class="item_title">사이즈</td>'
                        + '<td>'
                        + '<select class="form-control"  name="model_size[]" id="model_size">'
                        +"<option value=''>[선택]</option>"
                        + "<option value='220'>220</option>"
                        + "<option value='225'>225</option>"
                        + "<option value='230'>230</option>"
                        + "<option value='235'>235</option>"
                        + "<option value='240'>240</option>"
                        + "<option value='245'>245</option>"
                        + "<option value='250'>250</option>"
                        + "<option value='255'>255</option>"
                        + "<option value='260'>260</option>"
                        + "<option value='265'>265</option>"
                        + "<option value='270'>270</option>"
                        + "<option value='275'>275</option>"
                        + "<option value='280'>280</option>"
                        + "<option value='285'>285</option>"
                        + "<option value='290'>290</option>"
                        + "<option value='295'>295</option>"
                        + "<option value='300'>300</option>"
                        + '</select>'
                        + '</td>';
                }

                let tr = trTop + trBody;
                $('#tr_model_info_'+(modelCnt-1)).after(tr);
                modelCnt++;
            });

            //치수정보 추가
            let sizeCnt = <?=$countSizeInfo?>;

            $(document).on("click", "#size_info_add", function(){
                let tr = null;
                if($('#first_category').val() == '2'){
                    //상의
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_SHOULDER_SIZE[]" id="TOP_SHOULDER_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_CHEST_SIZE[]" id="TOP_CHEST_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_ARMHOLE_SIZE[]" id="TOP_ARMHOLE_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_ARM_SIZE[]" id="TOP_ARM_SIZE">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="TOP_TOTAL_LENGTH[]" id="TOP_TOTAL_LENGTH">'
                        + '</td>'
                        + '</tr>';
                } else if($('#first_category').val() == '3'){
                    // 아우터
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="OUTER_TOTAL_LENGTH[]" id="OUTER_TOTAL_LENGTH ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="OUTER_SHOULDER_SIZE[]" id="OUTER_SHOULDER_SIZE ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="OUTER__CHEST_SIZE[]" id="OUTER__CHEST_SIZE ">'
                        + '</td>'
                        + '<td colspan="2">'
                        + '<input class="form-control " type="text" name="OUTER_SLEEVE_LENGTH[]" id="OUTER_SLEEVE_LENGTH ">'
                        + '</td>'
                        + '</tr>';
                } else if($('#first_category').val() == '4'){
// 하의
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_WAIST_SIZE[]" id="BOTTOM_WAIST_SIZE  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_TOTAL_LENGTH[]" id="BOTTOM_TOTAL_LENGTH  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_THIGH_SIZE[]" id="BOTTOM_THIGH_SIZE  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_HEM_SIZE[]" id="BOTTOM_HEM_SIZE  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="BOTTOM_RISE[]" id="BOTTOM_RISE  ">'
                        + '</td>'
                        + '</tr>';
                } else if($('#first_category').val() == '27'){
                    // 모자
                    tr = '<tr id="tr_size_info_'+sizeCnt+'">'
                        + '<td>'
                        + '<select class="form-control"  name="size[]" id="size">'
                        + '<option value="">[선택]</option>'
                        + '<option value="S">S</option>'
                        + '<option value="M">M</option>'
                        + '<option value="L">L</option>'
                        + '<option value="XL">XL</option>'
                        + '<option value="2XL">2XL</option>'
                        + '<option value="3XL">3XL</option>'
                        + '<option value="4XL">4XL</option>'
                        + '<option value="FREE">FREE</option>'
                        + '</select>'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="HAT_ROUND[]" id="HAT_ROUND  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="HAT_LENGTH[]" id="HAT_LENGTH  ">'
                        + '</td>'
                        + '<td>'
                        + '<input class="form-control " type="text" name="HAT_HEIGHT[]" id="HAT_HEIGHT  ">'
                        + '</td>'
                        + '</tr>'
                }

                $('#tr_size_info_'+(sizeCnt-1)).after(tr);
                sizeCnt++;
            });

            // 상품정보 삭제(마지막 행 삭제)
            $(document).on("click", "#product_info_delete", function(){
                let currentCnt = productInfoCnt-1;

                if(currentCnt != 0){
                    $('#tr_product_info_'+(productInfoCnt-1)).remove();
                    productInfoCnt--;
                }
            });

            // 모델정보 삭제(마지막 행 삭제)
            $(document).on("click", "#model_info_delete", function(){
                let currentCnt = modelCnt-1;
                if(currentCnt != 0){
                    $('#tr_model_info_'+(modelCnt-1)).remove();
                    modelCnt--;
                }
            });

            // 치수정보 삭제(마지막 행 삭제)
            $(document).on("click", "#size_info_delete", function(){
                let currentCnt = sizeCnt-1;
                if(currentCnt != 0){
                    $('#tr_size_info_'+(sizeCnt-1)).remove();
                    sizeCnt--;
                }
            });

            // 대표이미지 미리보기
            $('#file_represent').on("change", function(e){
                let files = e.target.files;
                let fileArr = Array.prototype.slice.call(files);

                fileArr.forEach(function(file){
                   if(!file.type.match("image.*")) {
                       alert("확장자는 이미지 확장자만 가능합니다.");
                       return;
                   }

                   let reader = new FileReader();

                   reader.onload = function(e) {
                       $('#img_represent').attr("src", e.target.result);
                   }
                   reader.readAsDataURL(file);

                });
            });

            // 싱세이미지 미리보기
            $('#file_detail').on("change", function(e){
                let files = e.target.files;
                let fileArr = Array.prototype.slice.call(files);
                let sel_files = [];

                $('.img_detail').remove();

                fileArr.forEach(function(file){
                    if(!file.type.match("image.*")) {
                        alert("확장자는 이미지 확장자만 가능합니다.");
                        return;
                    }

                    let reader = new FileReader();

                    reader.onload = function(e) {
                        $('#img_detail').append('<img class="img_detail" src=\"' + e.target.result + '\" width="200px;" style="float: left;"/>');
                    }

                    reader.readAsDataURL(file);

                });
            });
        </script>
</body>
</html>