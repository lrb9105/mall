<?php
/* 공지사항 혹은 자주묻는 질문을 입력한다.
    1.
*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$searchSelect = $_POST['searchSelect'];
$searchText = $_POST['searchText'];

// 총갯수
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

// 현재 페이지번호
$page_no = $_POST['page_no'] != 0 ? $_POST['page_no'] : 1;
// 총 게시물 개수
$total_count_of_post = mysqli_num_rows($result);
// 한 페이지당 보여줄 게시물 개수
$count_of_post_per_page = 3;
// 총 페이지 개수(나머지가 있다면 1추가)
//$total_count_of_page = $total_count_of_post / $count_of_post_per_page + ($total_count_of_post % $count_of_post_per_page > 0 ? 1 : 0);
$total_count_of_page = ceil($total_count_of_post / $count_of_post_per_page);
// 한 페이지에서 보여줄 블록 개수
$count_of_block_per_page = 2;
// 총 블록그룹 개수(총 페이지 / 페이지 당 블록 수) + 1(나머지 있다면, 없다면 0)
//$total_count_of_block = $total_count_of_page / $count_of_block_per_page + ($total_count_of_page % $count_of_block_per_page > 0 ? 1 : 0);
$total_count_of_block = ceil($total_count_of_page / $count_of_block_per_page);
// 현재 블록그룹 번호
if($page_no != 1){
    $current_num_of_block = ceil($page_no/$count_of_block_per_page);
} else{
    $current_num_of_block = 1;
}
// 블록의 시작페이지 번호
$start_page_num_of_block = $current_num_of_block * $count_of_block_per_page - ($count_of_block_per_page - 1);
// 블록의 종료페이지 번호
$end_page_num_of_block = $current_num_of_block * $count_of_block_per_page;
if($end_page_num_of_block > $total_count_of_page){
    $end_page_num_of_block = $total_count_of_page;
}

// 조회 해야할 데이터 시작번호
$s_point = ($page_no-1) * $count_of_post_per_page;

// 실제 데이터
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
    ORDER BY GROUP_NO DESC, GROUP_ORDER ASC, CRE_DATETIME
    LIMIT $s_point,$count_of_post_per_page";
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
    ORDER BY GROUP_NO DESC, GROUP_ORDER ASC, CRE_DATETIME
    LIMIT $s_point,$count_of_post_per_page";
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
    ORDER BY GROUP_NO DESC, GROUP_ORDER ASC, CRE_DATETIME
    LIMIT $s_point,$count_of_post_per_page";
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
    echo json_encode(array('result' => 'ok', 'seq'=> $dbSeq, 'title'=> $dbTitle
        , 'writer'=> $dbWriter, 'creDatetime'=> $dbCreDatetime, 'cnt'=> $dbCnt, 'depth'=> $dbDepth, 'name'=> $dbName, 'commentCnt' => $dbCommentCnt
        , 'total_count_of_post' => $total_count_of_post
        , 'count_of_post_per_page' => $count_of_post_per_page
        , 'total_count_of_page' => $total_count_of_page
        , 'count_of_block_per_page' => $count_of_block_per_page
        , 'total_count_of_block' => $total_count_of_block
        , 'current_num_of_block' => $current_num_of_block
        , 'start_page_num_of_block' => $start_page_num_of_block
        , 'end_page_num_of_block' => $end_page_num_of_block));
}