<?php
/* q&a를 수정한다.
    1.
*/
session_start();
$login_id = $_SESSION['LOGIN_ID'];

/* Q&A를 수정한다.
    1.
*/

// Q&A정보
$seq = $_POST['qanda_seq'];
//$secret_yn = $_POST['secret_yn'];
$question_type = $_POST['question_type'];
$qanda_title = $_POST['qanda_title'];
$qanda_contents = $_POST['qanda_contents'];

// 값 잘 넘어오는지 확인

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

$sql = "UPDATE QANDA 
        SET   TYPE = '$question_type',
            TITLE = '$qanda_title' ,
            CONTENTS = '$qanda_contents'
        WHERE SEQ = $seq";

$result = mysqli_query($conn, $sql);

// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}