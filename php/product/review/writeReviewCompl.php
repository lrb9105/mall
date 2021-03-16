<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];
/* 리뷰를 등록한다.
    1.
*/


//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$result = null;
$saved_path = null;

// 리뷰 타입("0" : 포토, "1" : 일반)
$type = $_POST['type'];
// 값 잘 넘어오는지 확인
/*echo $photo_review_title;
echo $photo_review_contents;
echo $photo_review_evaluation_size;
echo $photo_review_evaluation_color;
echo $photo_review_evaluation_lightness;
echo $photo_review_evaluation_thickness;
echo $photo_review_raty;*/

// 리뷰 정보
$product_no = $_POST['product_no'];
$photo_review_selected_product = $_POST['photo_review_selected_product'];
$photo_review_title = $_POST['photo_review_title'];
$photo_review_contents = $_POST['photo_review_contents'];
$photo_review_evaluation_size = $_POST['photo_review_evaluation_size'];
$photo_review_evaluation_color = $_POST['photo_review_evaluation_color'];
$photo_review_evaluation_lightness = $_POST['photo_review_evaluation_lightness'];
if($_POST['photo_review_evaluation_thickness'] == null || $_POST['photo_review_evaluation_thickness'] == ''){
    $photo_review_evaluation_thickness = '';
} else{
    $photo_review_evaluation_thickness = $_POST['photo_review_evaluation_thickness'];
}
$photo_star_score = $_POST['photo_review_raty'];

// 리뷰 게시글 입력
$sql = "
    INSERT INTO REVIEW  (
            PRODUCT_SEQ,
            TYPE,
            WRITER,
            TITLE,
            CONTENTS,
            STAR_SCORE,
            EVAL_SIZE,
            EVAL_LIGHTNESS,
            EVAL_COLOR,
            EVAL_THICKNESS,
            CRE_DATETIME
    ) VALUES (
             $product_no,
             $type,
            '$login_id',
            '$photo_review_title' ,
            '$photo_review_contents' ,
             $photo_star_score ,
            '$photo_review_evaluation_size' ,
            '$photo_review_evaluation_lightness' ,
            '$photo_review_evaluation_color' ,
            '$photo_review_evaluation_thickness' ,
             now()
    )";
$result = mysqli_query($conn, $sql);

if($result == false){
    echo '실패';
    echo $sql;
    return;
}

// 리뷰 조회
$sql = "SELECT MAX(SEQ) FROM REVIEW";

// 자신의 seq가져오기!
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$seqOfReview = $row[0];

if($result == false){
    echo '실패1111';
    echo $sql;
    return;
}

// 리뷰 작성한 상품 리뷰완료 및 리뷰번호 업데이트
$sql = "
    UPDATE ORDER_PRODUCT_LIST 
    SET REVIEW_YN = '1'
      , REVIEW_SEQ = $seqOfReview
    WHERE SEQ = $photo_review_selected_product
    ";
$result = mysqli_query($conn, $sql);

if($result == false){
    echo '실패';
    echo $sql;
    return;
}

// 리뷰한 상품의 색상, 사이즈 가져오기
$sqlProductColAndSize = "
    SELECT PRODUCT_COLOR
         , PRODUCT_SIZE
    FROM ORDER_PRODUCT_LIST
    WHERE SEQ = $photo_review_selected_product
    ";
$resultProductColAndSize = mysqli_query($conn, $sqlProductColAndSize);
$rowProductColAndSize = mysqli_fetch_array($resultProductColAndSize);

if($resultProductColAndSize == false){
    echo '실패';
    echo $sqlProductColAndSize;
    return;
}

// 상품이미지
if (isset($_FILES)) {
    // 상품이미지 업로드(이미지 경로 저장)
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    $file = $_FILES["photo_review_file"];
    $error = $file["error"];
    $name = $file["name"]; //원본 파일명
    $type = $file["type"];
    $size = $file["size"];
    $tmp_name = $file["tmp_name"];
    $saved_name = null; //저장 파일명


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
            if (file_exists($_SERVER['DOCUMENT_ROOT']."/mall/img/review/" . $saved_name)) {
                /*echo $name . " already exists. ";
                return;*/
            }
            else {
                if(move_uploaded_file($tmp_name, $_SERVER['DOCUMENT_ROOT']."/mall/img/review/" . $saved_name)){
                    //파일저장 경로(img 태그에서 이 값 사용)
                    $saved_path = "/mall/img/review/" . $saved_name;

                    // 대표이미지 파일 저장
                    //구분값 0: 상품대표이미지
                    $sqlInsertFileRep = "
                            INSERT INTO FILE (
                                REVIEW_SEQ,
                                REF_SEQ,
                                TYPE,
                                FILE_NAME_ORIGIN,
                                FILE_NAME_SAVE,
                                SAVE_PATH,
                                CRE_DATETIME
                            ) VALUES(
                                $seqOfReview,
                                $product_no,
                                2, 
                                '$name',
                                '$saved_name',
                                '$saved_path',
                                now()   
                            )";
                    $resultInsertFileRep = mysqli_query($conn, $sqlInsertFileRep);
                    // 결과값(실패 시 false 들어감)
                    $result = $resultInsertFileRep;

                    if($result == false){
                        echo '실패22222';
                        echo $sqlInsertFileRep;
                        return;
                    }
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
// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok', 'save_path'=>$saved_path, 'color'=> $rowProductColAndSize['PRODUCT_COLOR'], 'size'=>$rowProductColAndSize['PRODUCT_SIZE']));
}