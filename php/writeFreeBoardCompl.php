<?php
/* 공지사항 혹은 자주묻는 질문을 입력한다.
    1.
*/

// 글 정보
$writer = $_POST['login_id'];
$title = $_POST['title'];
$contents = $_POST['contents'];
$seq = $_POST['seq'];

$group_no = 0;
$group_order = 1;
$depth = 1;

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 만약 답글이라면(seq가 존재한다면)
if($seq != ''){
    // 데이터 가져오기 - 자유게시판
    $sql = "SELECT FB.DEPTH ,
            FB.GROUP_NO,
            FB.GROUP_ORDER
            FROM FREE_BOARD FB
            WHERE FB.SEQ = '$seq'
            ";
    // 쿼리를 통해 가져온 결과
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_array($result);

    //그룹번호, 그룹내순서, DEPTH, 제목생성
    $group_no = $row['GROUP_NO'];
    $group_order = $row['GROUP_ORDER'] + 1;
    $depth = $row['DEPTH'] + 1;
}
// 자유게시판 게시글 입력
$sql = "
    INSERT INTO FREE_BOARD (
            TITLE ,
            CONTENTS ,
            WRITER ,
            CNT,
            DEPTH ,
            GROUP_ORDER, 
            CRE_DATETIME,
            UPD_DATETIME
    ) VALUES (
            '$title',
            '$contents',
            '$writer' ,
             0 ,
             '$depth' ,
             '$group_order' ,
             now() ,
             null
    )";
$result = mysqli_query($conn, $sql);

// 그룹번호 업데이트
// 자유게시판 게시글 조회
$sql = "SELECT MAX(SEQ) FROM FREE_BOARD";

// 자신의 seq가져오기!
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);
$sequence = $row[0];

if($seq == ''){
    // 새 글이라면(그룹번호 - 자신의 번호)
    $sql = "UPDATE FREE_BOARD SET GROUP_NO = '$sequence' WHERE SEQ = '$sequence'";
} else{ // 답글이라면 원글의 그룹번호를 입력
    $sql = "UPDATE FREE_BOARD SET GROUP_NO = '$group_no' WHERE SEQ = '$sequence'";
}
$result = mysqli_query($conn, $sql);


// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo '<script>alert("작성 실패했습니다")</script>';
} else {
    echo '<script>alert("작성 완료했습니다")</script>';
}
echo "<script> document.location.href='/mall/board.php?board_no=3'</script>";