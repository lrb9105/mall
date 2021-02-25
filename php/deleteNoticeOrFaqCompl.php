<?php
/* 공지사항 혹은 자주묻는 질문을 삭제한다.
    1.
*/

// 글 번호
$seq = $_GET['SEQ'];
$type = $_GET['TYPE'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "DELETE FROM  NOTICE_AND_FAQ WHERE SEQ = '$seq'";

// 공지사항 or 자주묻는 질문
$result = mysqli_query($conn, $sql);

// delete가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo '<script>alert("삭제에 실패했습니다.")</script>';
} else {
    echo '<script>alert("삭제를 완료했습니다.")</script>';
}
echo "<script> document.location.href='/mall/board.php?board_no=' + $type</script>";