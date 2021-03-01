<?php
/* 공지사항 혹은 자주묻는 질문을 입력한다.
    1.
*/

// 댓글 정보
$writer = $_POST['writer'];
$parent_seq = $_POST['parent_seq'];
$contents = $_POST['contents'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 자유게시판 게시글 입력
$sql = "
    INSERT INTO FREE_BOARD_COMMENT (
            PARENT_SEQ ,
            WRITER ,
            CONTENTS,
            CRE_DATETIME,
            UPD_DATETIME
    ) VALUES (
            '$parent_seq',
            '$writer' ,
            '$contents',
             now() ,
             null
    )";
$result = mysqli_query($conn, $sql);

// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}