<?php
/* 리뷰를 가져온다.
    1.
*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 상품번호
$product_no = $_POST['product_no'];

// 후기 총 갯수
$sqlReviewInfo = "SELECT SEQ,
                        PRODUCT_SEQ,
                        TYPE ,
                        TITLE ,
                        CONTENTS,
                        SECRET_YN ,
                        ANSWER_STATE, 
                        QA.CRE_DATETIME,
                        WRITER,
                        ANSWER,
                        ANSWER_YN,
                        U.NAME
                 FROM QANDA QA
                 INNER JOIN USER U ON QA.WRITER = U.LOGIN_ID
                 WHERE PRODUCT_SEQ = $product_no
                 AND QA.USE_YN = 'Y'
                 ORDER BY CRE_DATETIME DESC
        ";
$resultReviewInfo = mysqli_query($conn, $sqlReviewInfo);
$cntReview = mysqli_num_rows($resultReviewInfo);

// 현재 페이지번호
$page_no = $_POST['page_no'] != 0 ? $_POST['page_no'] : 1;
// 총 게시물 개수
$total_count_of_post = mysqli_num_rows($resultReviewInfo);
// 한 페이지당 보여줄 게시물 개수
$count_of_post_per_page = 5;
// 총 페이지 개수(나머지가 있다면 1추가)
//$total_count_of_page = $total_count_of_post / $count_of_post_per_page + ($total_count_of_post % $count_of_post_per_page > 0 ? 1 : 0);
$total_count_of_page = ceil($total_count_of_post / $count_of_post_per_page);
// 한 페이지에서 보여줄 블록 개수
$count_of_block_per_page = 10;
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
$sqlQAndAInfo = "SELECT SEQ,
                        PRODUCT_SEQ,
                        TYPE ,
                        TITLE ,
                        CONTENTS,
                        SECRET_YN ,
                        ANSWER_STATE, 
                        QA.CRE_DATETIME,
                        WRITER,
                        ANSWER,
                        ANSWER_YN,
                        U.NAME
                 FROM QANDA QA
                 INNER JOIN USER U ON QA.WRITER = U.LOGIN_ID
                 WHERE PRODUCT_SEQ = $product_no
                 AND QA.USE_YN = 'Y'
                 ORDER BY CRE_DATETIME DESC
                 LIMIT $s_point,$count_of_post_per_page";

$resultQAndAInfo = mysqli_query($conn, $sqlQAndAInfo);

// 가져온 데이터의 각각 컬럼값 배열에 넣어주기
$dbSeq = array();
$dbProductSeq = array();
$dbType = array();
$dbTitle = array();
$dbContents = array();
$dbSecretYn = array();
$dbAnswerState = array();
$dbCreDatetime = array();
$dbWriter = array();
$dbAnswer = array();
$dbAnswerYn = array();
$dbName = array();

// iconv: 한글이 깨지지 않게 하기위해 인코딩
while($rowQAndAInfo = mysqli_fetch_array($resultQAndAInfo)) {
    array_push($dbSeq, $rowQAndAInfo['SEQ']);
    array_push($dbProductSeq, $rowQAndAInfo['PRODUCT_SEQ']);
    array_push($dbType, $rowQAndAInfo['TYPE']);
    array_push($dbTitle, $rowQAndAInfo['TITLE']);
    array_push($dbContents, $rowQAndAInfo['CONTENTS']);
    array_push($dbSecretYn, $rowQAndAInfo['SECRET_YN']);
    array_push($dbAnswerState, $rowQAndAInfo['ANSWER_STATE']);
    array_push($dbCreDatetime, $rowQAndAInfo['CRE_DATETIME']);
    array_push($dbWriter, $rowQAndAInfo['WRITER']);
    array_push($dbAnswer, $rowQAndAInfo['ANSWER']);
    array_push($dbAnswerYn, $rowQAndAInfo['ANSWER_YN']);
    array_push($dbName, $rowQAndAInfo['NAME']);
}

// select가 실패했다면 false, 성공이라면 ok
if ($rowQAndAInfo === false) {
    echo json_encode(array('result' => 'fail', 'sql' => $sqlQAndAInfo));
} else {
    echo json_encode(array('result' => 'ok'
        , 'seq'=> $dbSeq
        , 'productSeq'=> $dbProductSeq
        , 'type'=> $dbType
        , 'title'=> $dbTitle
        , 'contents'=> $dbContents
        , 'secretYn'=> $dbSecretYn
        , 'answerState'=> $dbAnswerState
        , 'creDatetime'=> $dbCreDatetime
        , 'writer'=> $dbWriter
        , 'answer'=> $dbAnswer
        , 'answerYn'=> $dbAnswerYn
        , 'name'=> $dbName
        , 'total_count_of_post' => $total_count_of_post
        , 'count_of_post_per_page' => $count_of_post_per_page
        , 'total_count_of_page' => $total_count_of_page
        , 'count_of_block_per_page' => $count_of_block_per_page
        , 'total_count_of_block' => $total_count_of_block
        , 'current_num_of_block' => $current_num_of_block
        , 'start_page_num_of_block' => $start_page_num_of_block
        , 'end_page_num_of_block' => $end_page_num_of_block));
}