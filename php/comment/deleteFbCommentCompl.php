<?php
/* 자유게시판 게시글의 댓글을삭제한다.
    1.
*/

// 글 번호
$seq = $_POST['seq'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "DELETE FROM FREE_BOARD_COMMENT WHERE SEQ = '$seq'";

// 자유게시판 댓글
$result = mysqli_query($conn, $sql);

// delete가 실패했다면 false, 성공이라면 ok
if ($result == false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}