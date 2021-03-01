<?php
/* 공지사항 혹은 자주묻는 질문을 입력한다.
    1.
*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$searchSelect = $_POST['searchSelect'];
$searchText = $_POST['searchText'];

if($searchSelect != '' && $searchText != ''){
    // 작성자
    if($searchSelect == '1') {
        $sql = "SELECT FB.SEQ
          , FB.TITLE
          , FB.WRITER
          , FB.CRE_DATETIME
          , FB.CNT
          , FB.DEPTH
          , US.NAME
          , IFNULL((SELECT COUNT(*) FROM FREE_BOARD_COMMENT WHERE PARENT_SEQ = FB.SEQ),0) COMMENT_CNT
    FROM FREE_BOARD FB
    INNER JOIN USER US ON FB.WRITER = US.LOGIN_ID
    WHERE US.NAME LIKE '%$searchText%'
    ORDER BY GROUP_NO DESC, GROUP_ORDER ASC, CRE_DATETIME";
    } else { //제목
        $sql = "SELECT FB.SEQ
          , FB.TITLE
          , FB.WRITER
          , FB.CRE_DATETIME
          , FB.CNT
          , FB.DEPTH
          , US.NAME
          , IFNULL((SELECT COUNT(*) FROM FREE_BOARD_COMMENT WHERE PARENT_SEQ = FB.SEQ),0) COMMENT_CNT
    FROM FREE_BOARD FB
    INNER JOIN USER US ON FB.WRITER = US.LOGIN_ID
    WHERE TITLE LIKE '%$searchText%'
    ORDER BY GROUP_NO DESC, GROUP_ORDER ASC, CRE_DATETIME";
    }
} else{
    $sql = "SELECT FB.SEQ
          , FB.TITLE
          , FB.WRITER
          , FB.CRE_DATETIME
          , FB.CNT
          , FB.DEPTH
          , US.NAME
          , IFNULL((SELECT COUNT(*) FROM FREE_BOARD_COMMENT WHERE PARENT_SEQ = FB.SEQ),0) COMMENT_CNT
    FROM FREE_BOARD FB
    INNER JOIN USER US ON FB.WRITER = US.LOGIN_ID
    ORDER BY GROUP_NO DESC, GROUP_ORDER ASC, CRE_DATETIME";
}

$result = mysqli_query($conn, $sql);


// 가져온 데이터의 각각 컬럼값 배열에 넣어주기
$dbSeq = array();
$dbTitle = array();
$dbWriter = array();
$dbCreDatetime = array();
$dbCnt = array();
$dbDepth = array();
$dbName = array();
$dbCommentCnt = array();

// iconv: 한글이 깨지지 않게 하기위해 인코딩
while($row = mysqli_fetch_array($result)) {
    array_push($dbSeq, $row['SEQ']);
    array_push($dbTitle, $row['TITLE']);
    //array_push($dbTitle, iconv("CP949","UTF-8", $row['TITLE']));
    array_push($dbWriter, $row['WRITER']);
    array_push($dbCreDatetime, $row['CRE_DATETIME']);
    array_push($dbCnt, $row['CNT']);
    array_push($dbDepth, $row['DEPTH']);
    array_push($dbName, $row['NAME']);
    array_push($dbCommentCnt, $row['COMMENT_CNT']);
}

// insert가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result' => 'fail', 'sql' => $sql));
} else {
    echo json_encode(array('result' => 'ok', 'seq'=> $dbSeq, 'title'=> $dbTitle, 'writer'=> $dbWriter, 'creDatetime'=> $dbCreDatetime, 'cnt'=> $dbCnt, 'depth'=> $dbDepth, 'name'=> $dbName, 'commentCnt' => $dbCommentCnt));
}