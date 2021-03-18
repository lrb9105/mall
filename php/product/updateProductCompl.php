<?php
/* 상품등록.
    1.
*/
//성공여부
$result = true;

/* 입력 값 받아오기 */
// 상품명
$product_name = $_POST['product_name'];
// 상품1차분류
$first_category = $_POST['first_category'];
// 상품2차분류
$second_category = $_POST['second_category'];
// 제조자
$product_manufacture = $_POST['product_manufacture'];
if($product_manufacture == 'etc'){
    $product_manufacture = $_POST['product_manufacture_input'];
}
// 기존가격
$product_price = $_POST['product_price'];
// 할인가격
$product_price_sale = $_POST['product_price_sale'];
// 제조국
$country_of_manufacture = $_POST['country_of_manufacture'];
if($country_of_manufacture == 'etc'){
    $country_of_manufacture = $_POST['country_of_manufacture_input'];
}
// 상품소재
$product_material = $_POST['product_material'];
// 세탁방법 및 취급 시 주의사항
$cleaning_method = $_POST['cleaning_method'];
// 색상
$product_colorArr = $_REQUEST['product_color'];
// 사이즈
$product_sizeArr = $_REQUEST['product_size'];
// 수량
$product_numberArr = $_REQUEST['product_number'];
// 상세정보
$contents = $_POST['contents'];
//모델키
$model_heightArr = $_REQUEST['model_height'];
//모델 몸무게
$model_weightArr = $_REQUEST['model_weight'];
//모델 착용사이즈
$model_sizeArr = $_REQUEST['model_size'];
//치수 넣을 삼품 사이즈
$sizeArr = $_REQUEST['size'];
//상의-어깨길이
$TOP_SHOULDER_SIZEArr = $_REQUEST['TOP_SHOULDER_SIZE'];
//상의-가슴너비
$TOP_CHEST_SIZEArr = $_REQUEST['TOP_CHEST_SIZE'];
//상의-암홀길이
$TOP_ARMHOLE_SIZEArr = $_REQUEST['TOP_ARMHOLE_SIZE'];
//상의-팔길이
$TOP_ARM_SIZEArr = $_REQUEST['TOP_ARM_SIZE'];
//상의-총길이
$TOP_TOTAL_LENGTHArr = $_REQUEST['TOP_TOTAL_LENGTH'];
//아우터-총기장
$OUTER_TOTAL_LENGTHArr = $_REQUEST['OUTER_TOTAL_LENGTH'];
//아우터-어깨길이
$OUTER_SHOULDER_SIZEArr = $_REQUEST['OUTER_SHOULDER_SIZE'];
//아우터-가슴너비
$OUTER__CHEST_SIZEArr = $_REQUEST['OUTER__CHEST_SIZE'];
//아우터-소매길이
$OUTER_SLEEVE_LENGTHArr = $_REQUEST['OUTER_SLEEVE_LENGTH'];
//하의-허리단면
$BOTTOM_WAIST_SIZEArr = $_REQUEST['BOTTOM_WAIST_SIZE'];
//하의-총기장
$BOTTOM_TOTAL_LENGTHArr = $_REQUEST['BOTTOM_TOTAL_LENGTH'];
//하의-허벅지단면
$BOTTOM_THIGH_SIZEArr = $_REQUEST['BOTTOM_THIGH_SIZE'];
//하의-밑단단면
$BOTTOM_HEM_SIZEArr = $_REQUEST['BOTTOM_HEM_SIZE'];
//하의-밑위
$BOTTOM_RISEArr = $_REQUEST['BOTTOM_RISE'];
//모자-둘레
$HAT_ROUNDArr = $_REQUEST['HAT_ROUND'];
//모자-챙길이
$HAT_LENGTHArr = $_REQUEST['HAT_LENGTH'];
//모자-높이
$HAT_HEIGHTArr = $_REQUEST['HAT_HEIGHT'];
//핏
$fit = $_POST['fit'];
//두께
$thickness= $_POST['thickness'];
//신축성
$elasticity = $_POST['elasticity'];
//비침
$reflection = $_POST['reflection'];
//촉감
$touch = $_POST['touch'];
//계절
$season = $_POST['season'];



/* 입력값 출력 */
// 상품명
echo $product_name ."<br>";
// 상품1차분류first_category
echo $first_category ."<br>";
// 상품2차분류
echo $second_category ."<br>";
// 제조자
echo $product_manufacture ."<br>";
// 기존가격
echo $product_price ."<br>";
// 할인가격
echo $product_price_sale ."<br>";
// 제조국
echo $country_of_manufacture ."<br>";
// 상품소재
echo $product_material ."<br>";
// 세탁방법 및 취급 시 주의사항
echo $cleaning_method ."<br>";

// 색상
for($i = 0; $i < count($product_colorArr); $i++){
    echo $product_colorArr[$i] ."<br>";
}
// 사이즈
for($i = 0; $i < count($product_sizeArr); $i++){
    echo $product_sizeArr[$i] ."<br>";
}
// 수량
for($i = 0; $i < count($product_numberArr); $i++){
    echo $product_numberArr[$i] ."<br>";
}

// 상세정보
echo $contents = $_POST['contents'];

//모델키
for($i = 0; $i < count($product_numberArr); $i++){
    echo $model_heightArr[$i] ."<br>";
}
//모델 몸무게
for($i = 0; $i < count($model_weightArr); $i++){
    echo $model_weightArr[$i] ."<br>";
}
//모델 착용사이즈
for($i = 0; $i < count($model_sizeArr); $i++){
    echo $model_sizeArr[$i] ."<br>";
}
//치수 넣을 삼품 사이즈
for($i = 0; $i < count($sizeArr); $i++){
    echo $sizeArr[$i] ."<br>";
}
//상의-어깨길이
for($i = 0; $i < count($TOP_SHOULDER_SIZEArr); $i++){
    echo $TOP_SHOULDER_SIZEArr[$i] ."<br>";
}
//상의-가슴너비
for($i = 0; $i < count($TOP_CHEST_SIZEArr); $i++){
    echo $TOP_CHEST_SIZEArr[$i] ."<br>";
}
//상의-암홀길이
for($i = 0; $i < count($TOP_ARMHOLE_SIZEArr); $i++){
    echo $TOP_ARMHOLE_SIZEArr[$i] ."<br>";
}
//상의-팔길이
for($i = 0; $i < count(TOP_ARM_SIZE); $i++){
    echo TOP_ARM_SIZE[$i] ."<br>";
}
//상의-총길이
for($i = 0; $i < count($TOP_TOTAL_LENGTHArr); $i++){
    echo $TOP_TOTAL_LENGTHArr[$i] ."<br>";
}
//아우터-총기장
for($i = 0; $i < count($OUTER_TOTAL_LENGTHArr); $i++){
    echo $OUTER_TOTAL_LENGTHArr[$i] ."<br>";
}
//아우터-어깨길이
for($i = 0; $i < count($OUTER_SHOULDER_SIZEArr); $i++){
    echo $OUTER_SHOULDER_SIZEArr[$i] ."<br>";
}
//아우터-가슴너비
for($i = 0; $i < count($OUTER__CHEST_SIZEArr); $i++){
    echo $OUTER__CHEST_SIZEArr[$i] ."<br>";
}
//아우터-소매길이
for($i = 0; $i < count($OUTER_SLEEVE_LENGTHArr); $i++){
    echo $OUTER_SLEEVE_LENGTHArr[$i] ."<br>";
}
//하의-허리단면
for($i = 0; $i < count($BOTTOM_WAIST_SIZEArr); $i++){
    echo $BOTTOM_WAIST_SIZEArr[$i] ."<br>";
}
//하의-총기장
for($i = 0; $i < count($BOTTOM_TOTAL_LENGTHArr); $i++){
    echo $BOTTOM_TOTAL_LENGTHArr[$i] ."<br>";
}
//하의-허벅지단면
for($i = 0; $i < count($BOTTOM_THIGH_SIZEArr); $i++){
    echo $BOTTOM_THIGH_SIZEArr[$i] ."<br>";
}
//하의-밑단단면
for($i = 0; $i < count($BOTTOM_HEM_SIZEArr); $i++){
    echo $BOTTOM_HEM_SIZEArr[$i] ."<br>";
}
//하의-밑위
for($i = 0; $i < count($BOTTOM_RISEArr); $i++){
    echo $BOTTOM_RISEArr[$i] ."<br>";
}
//모자-둘레
for($i = 0; $i < count($HAT_ROUNDArr); $i++){
    echo $HAT_ROUNDArr[$i] ."<br>";
}
//모자-챙길이
for($i = 0; $i < count($HAT_LENGTHArr); $i++){
    echo $HAT_LENGTHArr[$i] ."<br>";
}
//모자-높이
for($i = 0; $i < count($HAT_HEIGHTArr); $i++){
    echo $HAT_HEIGHTArr[$i] ."<br>";
}
//핏
echo $fit."<br>";
//두께
echo $thickness."<br>";
//신축성
echo $elasticity."<br>";
//비침
echo $reflection."<br>";
//촉감
echo $touch."<br>";
//계절
echo $season."<br>";


return;


/* 입력값 db 저장 */
//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// PRODUCT 테이블
$sqlInsertProduct = null;

if($first_category == '2' || $first_category == '3'){
    $sqlInsertProduct = "
    INSERT INTO PRODUCT (
                FIRST_CATEGORY, 
                SECOND_CATEGORY,
                PRODUCT_NAME,
                PRODUCT_PRICE,
                PRODUCT_PRICE_SALE,
                MATERIAL,
                MANUFACTURER,
                COUNTRY_OF_MANUFACTURER,
                CLEANING_METHOD,
                DETAIL_INFO,
                CRE_DATETIME,
                THICKNESS,
                REFLECTION,
                ELASTICITY,
                SEASON,
                FIT
    ) VALUES (
            '$first_category', 
            '$second_category', 
            '$product_name',
            '$product_price', 
            '$product_price_sale',
            '$product_material',
            '$product_manufacture', 
            '$country_of_manufacture', 
            '$cleaning_method', 
            '$contents',
            NOW(),
            '$thickness',
            '$reflection',
            '$elasticity',
            '$season',
            '$fit'
    )";
} elseif ($first_category == '4'){
    $sqlInsertProduct = "
    INSERT INTO PRODUCT (
                FIRST_CATEGORY, 
                SECOND_CATEGORY,
                PRODUCT_NAME,
                PRODUCT_PRICE,
                PRODUCT_PRICE_SALE,
                MATERIAL,
                MANUFACTURER,
                COUNTRY_OF_MANUFACTURER,
                CLEANING_METHOD,
                DETAIL_INFO,
                CRE_DATETIME,
                THICKNESS,
                REFLECTION,
                ELASTICITY,
                SEASON,
                TOUCH,
                FIT
    ) VALUES (
            '$first_category', 
            '$second_category', 
            '$product_name',
            '$product_price', 
            '$product_price_sale',
            '$product_material',
            '$product_manufacture', 
            '$country_of_manufacture', 
            '$cleaning_method', 
            '$contents',
            NOW(),
            '$thickness',
            '$reflection',
            '$elasticity',
            '$season',
            '$touch',
            '$fit'
    )";
} else {
    $sqlInsertProduct = "
    INSERT INTO PRODUCT (
                FIRST_CATEGORY, 
                SECOND_CATEGORY,
                PRODUCT_NAME,
                PRODUCT_PRICE,
                PRODUCT_PRICE_SALE,
                MATERIAL,
                MANUFACTURER,
                COUNTRY_OF_MANUFACTURER,
                CLEANING_METHOD,
                DETAIL_INFO,
                CRE_DATETIME
    ) VALUES (
            '$first_category', 
            '$second_category', 
            '$product_name',
            '$product_price', 
            '$product_price_sale',
            '$product_material',
            '$product_manufacture', 
            '$country_of_manufacture', 
            '$cleaning_method', 
            '$contents',
            NOW()
    )";
}

$resultInsertProduct = mysqli_query($conn, $sqlInsertProduct);

// 결과값(실패 시 false 들어감)
$result = $resultInsertProduct;

if($result === false){
    echo $sqlInsertProduct;
    echo '<script>alert("실패$resultInsertProduct")</script>';
    return;
}

// 결과값(실패 시 false 들어감)
$result = $resultInsertProduct;

//최상위 상품번호 조회
$sqlSelectProduct = "SELECT MAX(PRODUCT_SEQ) FROM PRODUCT";
$resultSelectProduct = mysqli_query($conn, $sqlSelectProduct);
$row = mysqli_fetch_array($resultSelectProduct);
// 상품번호
$seqOfProduct = $row[0];

// PRODUCT_OPTION 테이블
for($i=0; $i < count($product_colorArr); $i++){
    $sqlInsertOption = "
        INSERT INTO PRODUCT_OPTION (
            PRODUCT_SEQ,
            COLOR,
            SIZE,
            QUANTITY,
            CRE_DATETIME
        ) VALUES(
            $seqOfProduct,
            '$product_colorArr[$i]',
            '$product_sizeArr[$i]',
            $product_numberArr[$i],
            now()   
        )";
    $resultInsertOption = mysqli_query($conn, $sqlInsertOption);

    // 결과값(실패 시 false 들어감)
    $result = $resultInsertOption;

    if($result === false){
        echo $sqlInsertOption;
        echo '<script>alert("실패$resultInsertOption")</script>';
        return;
    }
}

//PRODUCT_SIZE 테이블
for($i=0; $i < count($sizeArr); $i++){
    $sqlInsertSize = null;

    if($first_category == '2'){
        $sqlInsertSize = "
        INSERT INTO PRODUCT_SIZE (
                  PRODUCT_SEQ 
                , TOP_SHOULDER_SIZE 
                , TOP_CHEST_SIZE 
                , TOP_ARMHOLE_SIZE 
                , TOP_ARM_SIZE 
                , TOP_TOTAL_LENGTH 
                , SIZE
        ) VALUES(
                 $seqOfProduct
                , $TOP_SHOULDER_SIZEArr[$i]
                , $TOP_CHEST_SIZEArr[$i] 
                , $TOP_ARMHOLE_SIZEArr[$i] 
                , $TOP_ARM_SIZEArr[$i]
                , $TOP_TOTAL_LENGTHArr[$i]
                , '$sizeArr[$i]'
        )";
    } elseif($first_category == '3'){
        $sqlInsertSize = "
        INSERT INTO PRODUCT_SIZE (
                  PRODUCT_SEQ 
                , OUTER_SHOULDER_SIZE 
                , OUTER__CHEST_SIZE 
                , OUTER_SLEEVE_LENGTH 
                , OUTER_TOTAL_LENGTH 
                , SIZE
        ) VALUES(
                 $seqOfProduct
                , $OUTER_SHOULDER_SIZEArr[$i] 
                , $OUTER__CHEST_SIZEArr[$i]
                , $OUTER_SLEEVE_LENGTHArr[$i] 
                , $OUTER_TOTAL_LENGTHArr[$i] 
                , '$sizeArr[$i]'
        )";
    } elseif($first_category == '4'){
        $sqlInsertSize = "
        INSERT INTO PRODUCT_SIZE (
                  PRODUCT_SEQ 
                , BOTTOM_WAIST_SIZE 
                , BOTTOM_RISE 
                , BOTTOM_THIGH_SIZE 
                , BOTTOM_HEM_SIZE 
                , BOTTOM_TOTAL_LENGTH 
                , SIZE
        ) VALUES(
                 $seqOfProduct
                , $BOTTOM_WAIST_SIZEArr[$i] 
                , $BOTTOM_RISEArr[$i] 
                , $BOTTOM_THIGH_SIZEArr[$i] 
                , $BOTTOM_HEM_SIZEArr[$i] 
                , $BOTTOM_TOTAL_LENGTHArr[$i] 
                , '$sizeArr[$i]'
        )";
    } elseif($first_category == '27'){
        $sqlInsertSize = "
        INSERT INTO PRODUCT_SIZE (
                  PRODUCT_SEQ 
                , HAT_ROUND 
                , HAT_LENGTH 
                , HAT_HEIGHT 
                , SIZE
        ) VALUES(
                 $seqOfProduct
                , $HAT_ROUNDArr[$i] 
                , $HAT_LENGTHArr[$i] 
                , $HAT_HEIGHTArr[$i] 
                , '$sizeArr[$i]'
        )";
    }

    $resultInsertSize = mysqli_query($conn, $sqlInsertSize);

    // 결과값(실패 시 false 들어감)
    $result = $resultInsertSize;

    if($result === false){
        echo $sqlInsertSize;

        echo '<script>alert("실패$resultInsertSize")</script>';
        return;
    }
}
//PRODUCT_MODEL_SIZE 테이블
for($i=0; $i < count($model_heightArr); $i++){
    $sqlInsertModelSize = "
        INSERT INTO PRODUCT_MODEL_SIZE (
                  PRODUCT_SEQ 
                , MODEL_HEIGHT 
                , MODEL_WEIGHT 
                , MODEL_SIZE
        ) VALUES(
                $seqOfProduct
                , $model_heightArr[$i]
                , $model_weightArr[$i]
                , '$model_sizeArr[$i]'
        )";
    $resultInsertModelSize = mysqli_query($conn, $sqlInsertModelSize);

    // 결과값(실패 시 false 들어감)
    $result = $resultInsertModelSize;

    if($result === false){
        echo $sqlInsertModelSize;
        echo '<script>alert("실패$resultInsertModelSize")</script>';
        return;
    }
}


// 대표이미지 업로드(이미지 경로 저장)
$allowedExts = array("gif", "jpeg", "jpg", "png");

if (isset($_FILES)) {
    $file = $_FILES["file_represent"];
    $error = $file["error"];
    $name = $file["name"]; //원본 파일명
    $type = $file["type"];
    $size = $file["size"];
    $tmp_name = $file["tmp_name"];
    $saved_name = null; //저장 파일명
    $saved_path = null; //저장경로

    if ( $error > 0 ) {
        /*echo "Error: " . $error . "<br>";
        return;*/
    }
    else {
        $temp = explode(".", $name);
        $extension = end($temp);
        $saved_name = $name."_".date("YmdHis").'.'.$extension;;

        if ( ($size/1024/1024) < 5. && in_array($extension, $allowedExts) ) {
            /*echo "Upload: " . $name . "<br>";
            echo "Type: " . $type . "<br>";
            echo "Size: " . ($size / 1024 / 1024) . " Mb<br>";
            echo "Stored in: " . $tmp_name. "<br>";*/
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name)) {
                /*echo $name . " already exists. ";
                return;*/
            }
            else {
                if(move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name)){
                    //파일저장 경로(img 태그에서 이 값 사용)
                    $saved_path = "/mall/img/clothes/" . $saved_name;

                    // 대표이미지 파일 저장
                    //구분값 0: 상품대표이미지
                    $sqlInsertFileRep = "
                            INSERT INTO FILE (
                                REF_SEQ,
                                TYPE,
                                FILE_NAME_ORIGIN,
                                FILE_NAME_SAVE,
                                SAVE_PATH,
                                CRE_DATETIME
                            ) VALUES(
                                $seqOfProduct,
                                0, 
                                '$name',
                                '$saved_name',
                                '$saved_path',
                                now()   
                            )";
                    $resultInsertFileRep = mysqli_query($conn, $sqlInsertFileRep);
                    // 결과값(실패 시 false 들어감)
                    $result = $resultInsertFileRep;

                   /* echo "Stored in: " . $_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name;*/
                } else{
                    echo "실패". $_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name;
                }
            }
        }
        else {
            /*echo ($size/1024/1024) . " Mbyte is bigger than 2 Mb ";
            echo $extension . "format file is not allowed to upload ! ";*/
        }
    }
}
else {
    /*echo "File is not selected";*/
}

// 상세이미지 업로드(이미지 경로 저장)
for($i = 0; $i < count($_FILES['file_detail']['name']); $i++){
    $file = $_FILES['file_detail'][$i];
    $error = $_FILES['file_detail']["error"][$i];
    $name = $_FILES['file_detail']["name"][$i]; //파일 원본명
    $type = $_FILES['file_detail']["type"][$i];
    $size = $_FILES['file_detail']["size"][$i];
    $tmp_name =  $_FILES['file_detail']["tmp_name"][$i];
    $saved_name_detail = null; //저장 파일명
    $saved_path = null; //저장경로

    // 파일명
    $fileDetail = $_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/";
    $filePath = "/mall/img/clothes/";

    if ( $error > 0 ) {
        echo "Error: " . $error . "<br>";
    }
    else {
        $temp = explode(".", $name);
        $extension = end($temp);
        $saved_name_detail = $name."_".date("YmdHis").'.'.$extension;;

        if ( ($size/1024/1024) < 5. && in_array($extension, $allowedExts) ) {
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name_detail)) {
                /*echo $name . " already exists. ";
                return;*/
            }
            else {
                if(move_uploaded_file($tmp_name, $fileDetail . $saved_name_detail)){
                    // 저장경로
                    $saved_path = "/mall/img/clothes/" . $saved_name_detail;

                    // 상세이미지 파일 저장
                    //구분값 1: 상품상세이미지
                    $sqlInsertFileRep = "
                        INSERT INTO FILE (
                            REF_SEQ,
                            TYPE,
                            FILE_NAME_ORIGIN,
                            FILE_NAME_SAVE,
                            SAVE_PATH,
                            CRE_DATETIME
                        ) VALUES(
                            $seqOfProduct,
                            1, 
                            '$name',
                            '$saved_name_detail',
                            '$saved_path',
                            now()   
                        )";
                    $resultInsertFileRep = mysqli_query($conn, $sqlInsertFileRep);

                    // 결과값(실패 시 false 들어감)
                    $result = $resultInsertFileRep;

                    /*echo "Stored in: " . $fileDetail . $saved_name_detail;*/
                } else{
                    echo "실패". $fileDetail . $saved_name_detail;
                }
            }
        }
        else {
            /*echo ($size/1024/1024) . " Mbyte is bigger than 2 Mb ";
            echo $extension . "format file is not allowed to upload ! ";*/
        }

    }
}

// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo '<script>alert("작성 실패했습니다")</script>';
} else {
    echo '<script>alert("작성 완료했습니다")</script>';
}
echo "<script> document.location.href='/mall/category.php?menu_no=$second_category'</script>";