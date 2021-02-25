<?php
/* 공지사항 혹은 자주묻는 질문을 수정한다.
    1.
*/

// 글 번호
$seq = $_POST['SEQ'];
$title = $_POST['TITLE'];
$contents = $_POST['CONTENTS'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "UPDATE NOTICE_AND_FAQ
        SET TITLE = '$title', 
            CONTENTS = '$contents',
            UPD_DATETIME = NOW()
        WHERE SEQ = '$seq'";

// 데이터 수정
$result = mysqli_query($conn, $sql);

// UPDATE가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result' => 'fail'));
} else {
    echo json_encode(array('result' => 'ok'));
}