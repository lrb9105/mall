<?php
/* 공지사항 혹은 자주묻는 질문을 입력한다.
    1.
*/

// 글 정보
$board_type = $_POST['board_type'];
$title = $_POST['title'];
$contents = $_POST['contents'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "
    INSERT INTO NOTICE_AND_FAQ (
            TITLE ,
            CONTENTS ,
            TYPE,
            CRE_DATETIME,
            UPD_DATETIME
    ) VALUES (
            '$title',
            '$contents',
            '$board_type'
            , now()
            , null
    )";

// 공지사항 or 자주묻는 질문
$result = mysqli_query($conn, $sql);

// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result' => 'fail'));
} else {
    echo json_encode(array('result' => 'ok'));
}