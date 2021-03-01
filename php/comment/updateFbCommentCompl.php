<?php
/* 자유게시판 게시글을 수정한다.
    1.
*/

// 글 정보
$seq = $_POST['seq'];
$contents = $_POST['contents'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "
    UPDATE FREE_BOARD_COMMENT SET
            CONTENTS = '$contents',
            UPD_DATETIME = NOW()
    WHERE SEQ = '$seq'";

// 댓글
$result = mysqli_query($conn, $sql);

// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}
