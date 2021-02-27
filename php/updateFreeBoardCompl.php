<?php
/* 자유게시판 게시글을 수정한다.
    1.
*/

// 글 정보
$seq = $_POST['seq'];
$title = $_POST['title'];
$contents = $_POST['contents'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 회원정보 입력 쿼리
$sql = "
    UPDATE FREE_BOARD SET
            TITLE = '$title',
            CONTENTS = '$contents'
    WHERE SEQ = '$seq'";

// 자유게시판
$result = mysqli_query($conn, $sql);

// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo '<script>alert("수정 실패했습니다")</script>';
} else {
    echo '<script>alert("수정 완료했습니다")</script>';
}
echo "<script> document.location.href='/mall/detailFreeBoard.php?board_no=3&seq=' + $seq</script>";