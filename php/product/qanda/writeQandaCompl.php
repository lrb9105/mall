<?php
session_start();
$login_id = $_SESSION['LOGIN_ID'];

/* Q&A를 등록한다.
    1.
*/

// Q&A정보
$secret_yn = $_POST['secret_yn'];
$question_type = $_POST['question_type'];
$qanda_title = $_POST['qanda_title'];
$qanda_contents = $_POST['qanda_contents'];
$productNo = $_POST['product_no'];

/*echo $secret_yn;
echo $question_type;
echo $qanda_title;
echo $qanda_contents;
echo $productNo;*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// Q&A 입력
$sql = "
    INSERT INTO QANDA (
            PRODUCT_SEQ,
            TYPE ,
            TITLE ,
            CONTENTS,
            SECRET_YN ,
            ANSWER_STATE, 
            CRE_DATETIME,
            WRITER,
            ANSWER,
            ANSWER_YN
    ) VALUES (
            $productNo,
            '$question_type',
            '$qanda_title' ,
            '$qanda_contents',
            '$secret_yn' ,
            '답변중' ,
             now() ,
             '$login_id' ,
             null,
             0
    )";
$result = mysqli_query($conn, $sql);

if($result === false){
    echo $sql;
    return;
}

// insert가 실패했다면 false, 성공이라면 ok
if ($result == false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}