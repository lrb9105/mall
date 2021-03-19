<?php
/* 리뷰를 수정한다.
    1.
*/
session_start();
$login_id = $_SESSION['LOGIN_ID'];

// 리뷰 타입("0" : 포토, "1" : 일반)
$type = $_POST['type'];

// 리뷰 정보
$photo_review_seq = $_POST['photo_review_seq'];
$photo_review_type = $_POST['photo_review_type'];
$photo_review_title = $_POST['photo_review_title'];
$photo_review_contents = $_POST['photo_review_contents'];
$photo_review_evaluation_size = $_POST['photo_review_evaluation_size'];
$photo_review_evaluation_color = $_POST['photo_review_evaluation_color'];
$photo_review_evaluation_lightness = $_POST['photo_review_evaluation_lightness'];
$photo_review_evaluation_thickness = $_POST['photo_review_evaluation_thickness'];
$photo_review_raty = $_POST['photo_review_raty'];

// 값 잘 넘어오는지 확인
/*echo $photo_review_seq;
echo $photo_review_type;
echo $photo_review_title;
echo $photo_review_contents;
echo $photo_review_evaluation_size;
echo $photo_review_evaluation_color;
echo $photo_review_evaluation_lightness;
echo $photo_review_evaluation_thickness;
echo $photo_review_raty;*/


//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

$sql = "UPDATE REVIEW 
        SET   TITLE = '$photo_review_title'
            , CONTENTS = '$photo_review_contents'
            , STAR_SCORE = $photo_review_raty
            , EVAL_SIZE = '$photo_review_evaluation_size'
            , EVAL_LIGHTNESS = '$photo_review_evaluation_lightness'
            , EVAL_COLOR = '$photo_review_evaluation_color'
            , EVAL_THICKNESS = '$photo_review_evaluation_thickness'
        WHERE SEQ = $photo_review_seq";

$result = mysqli_query($conn, $sql);

// 상품이미지
$saved_path = null; // 저장 경로
$saved_name = null; // 저장 파일명

if (isset($_FILES)) {
    // 상품이미지 업로드(이미지 경로 저장)
    $allowedExts = array("gif", "jpeg", "jpg", "png");

    $file = $_FILES["photo_review_modify_file_photo_review"];
    $error = $file["error"];
    $name = $file["name"]; //원본 파일명
    $type = $file["type"];
    $size = $file["size"];
    $tmp_name = $file["tmp_name"];


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
                            UPDATE FILE SET 
                                FILE_NAME_ORIGIN = '$name' ,
                                FILE_NAME_SAVE = '$saved_name',
                                SAVE_PATH = '$saved_path',
                                CRE_DATETIME = NOW()    
                            WHERE REVIEW_SEQ = $photo_review_seq
                            ";
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


// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    if($saved_path != null){
        echo json_encode(array('result'=>'ok', 'saved_path'=>$saved_path));
    } else{
        echo json_encode(array('result'=>'ok'));
    }

}