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

/* 입력값 출력 */
/*// 상품명
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
echo $contents = $_POST['contents'];*/


/* 입력값 db 저장 */
//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// PRODUCT 테이블
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
$resultInsertProduct = mysqli_query($conn, $sqlInsertProduct);

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
        echo '<script>alert("실패111")</script>';
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
        echo "Error: " . $error . "<br>";
        return;
    }
    else {
        $temp = explode(".", $name);
        $extension = end($temp);
        $saved_name = $name."_".date("YmdHis").'.'.$extension;;

        if ( ($size/1024/1024) < 5. && in_array($extension, $allowedExts) ) {
            echo "Upload: " . $name . "<br>";
            echo "Type: " . $type . "<br>";
            echo "Size: " . ($size / 1024 / 1024) . " Mb<br>";
            echo "Stored in: " . $tmp_name. "<br>";
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name)) {
                echo $name . " already exists. ";
                return;
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

                    echo "Stored in: " . $_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name;
                } else{
                    echo "실패". $_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name;
                }
            }
        }
        else {
            echo ($size/1024/1024) . " Mbyte is bigger than 2 Mb ";
            echo $extension . "format file is not allowed to upload ! ";
        }
    }
}
else {
    echo "File is not selected";
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
        return;
    }
    else {
        $temp = explode(".", $name);
        $extension = end($temp);
        $saved_name_detail = $name."_".date("YmdHis").'.'.$extension;;

        if ( ($size/1024/1024) < 5. && in_array($extension, $allowedExts) ) {
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/mall/img/clothes/" . $saved_name_detail)) {
                echo $name . " already exists. ";
                return;
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

                    echo "Stored in: " . $fileDetail . $saved_name_detail;
                } else{
                    echo "실패". $fileDetail . $saved_name_detail;
                }
            }
        }
        else {
            echo ($size/1024/1024) . " Mbyte is bigger than 2 Mb ";
            echo $extension . "format file is not allowed to upload ! ";
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

return;