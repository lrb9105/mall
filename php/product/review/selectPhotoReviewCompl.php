<?php
/* 포토리뷰를 가져온다.
    1.
*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 상품번호
$product_no = $_POST['product_no'];

// 포토후기 총 갯수
$sqlPhotoReviewInfo = "SELECT   R.SEQ,
                                R.PRODUCT_SEQ,
                                R.TYPE,
                                R.WRITER,
                                R.TITLE,
                                R.CONTENTS,
                                R.STAR_SCORE,
                                R.EVAL_SIZE,
                                R.EVAL_LIGHTNESS,
                                R.EVAL_COLOR,
                                R.EVAL_THICKNESS,
                                R.CRE_DATETIME,
                                F.SAVE_PATH,
                                OPL.PRODUCT_SIZE,
                                OPL.PRODUCT_COLOR,
                                U.NAME
                 FROM REVIEW R
                 INNER JOIN FILE F ON R.SEQ = F.REVIEW_SEQ
                 INNER JOIN ORDER_PRODUCT_LIST OPL ON R.PRODUCT_SEQ = OPL.PRODUCT_SEQ
                 INNER JOIN USER  U ON U.LOGIN_ID = R.WRITER
                 WHERE R.PRODUCT_SEQ = $product_no
                 AND OPL.REVIEW_YN = '1'
                 AND OPL.REVIEW_SEQ = R.SEQ
                 AND F.TYPE = 2
                 AND R.USE_YN = 'Y'
                 ORDER BY CRE_DATETIME DESC
        ";
$resultPhotoReviewInfo = mysqli_query($conn, $sqlPhotoReviewInfo);
$cntPhotoReview = mysqli_num_rows($resultPhotoReviewInfo);

// 현재 페이지번호
$page_no = $_POST['page_no'] != 0 ? $_POST['page_no'] : 1;
// 총 게시물 개수
$total_count_of_post = mysqli_num_rows($resultPhotoReviewInfo);
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
$sqlPhotoReviewInfo = "SELECT   R.SEQ,
                                R.PRODUCT_SEQ,
                                R.TYPE,
                                R.WRITER,
                                R.TITLE,
                                R.CONTENTS,
                                R.STAR_SCORE,
                                R.EVAL_SIZE,
                                R.EVAL_LIGHTNESS,
                                R.EVAL_COLOR,
                                R.EVAL_THICKNESS,
                                R.CRE_DATETIME,
                                F.SAVE_PATH,
                                OPL.PRODUCT_SIZE,
                                OPL.PRODUCT_COLOR,
                                U.NAME
                 FROM REVIEW R
                 INNER JOIN FILE F ON R.SEQ = F.REVIEW_SEQ
                 INNER JOIN ORDER_PRODUCT_LIST OPL ON R.PRODUCT_SEQ = OPL.PRODUCT_SEQ
                 INNER JOIN USER  U ON U.LOGIN_ID = R.WRITER
                 WHERE R.PRODUCT_SEQ = $product_no
                 AND OPL.REVIEW_YN = '1'
                 AND OPL.REVIEW_SEQ = R.SEQ
                 AND F.TYPE = 2
                 AND R.USE_YN = 'Y'
                 ORDER BY CRE_DATETIME DESC
                 LIMIT $s_point,$count_of_post_per_page";

$resultPhotoReviewInfo = mysqli_query($conn, $sqlPhotoReviewInfo);

// 가져온 데이터의 각각 컬럼값 배열에 넣어주기
$dbSeq = array();
$dbTitle = array();
$dbWriter = array();
$dbContents = array();
$dbStarScore = array();
$dbEvalSize = array();
$dbEvalLightness = array();
$dbEvalColor = array();
$dbEvalThickness = array();
$dbSavePath = array();
$dbProductSize = array();
$dbProductColor = array();
$dbName = array();

// iconv: 한글이 깨지지 않게 하기위해 인코딩
while($rowPhotoReviewInfo = mysqli_fetch_array($resultPhotoReviewInfo)) {
    array_push($dbSeq, $rowPhotoReviewInfo['SEQ']);
    array_push($dbTitle, $rowPhotoReviewInfo['TITLE']);
    array_push($dbWriter, $rowPhotoReviewInfo['WRITER']);
    array_push($dbContents, $rowPhotoReviewInfo['CONTENTS']);
    array_push($dbStarScore, $rowPhotoReviewInfo['STAR_SCORE']);
    array_push($dbEvalSize, $rowPhotoReviewInfo['EVAL_SIZE']);
    array_push($dbEvalLightness, $rowPhotoReviewInfo['EVAL_LIGHTNESS']);
    array_push($dbEvalColor, $rowPhotoReviewInfo['EVAL_COLOR']);
    array_push($dbEvalThickness, $rowPhotoReviewInfo['EVAL_THICKNESS']);
    array_push($dbSavePath, $rowPhotoReviewInfo['SAVE_PATH']);
    array_push($dbProductSize, $rowPhotoReviewInfo['PRODUCT_SIZE']);
    array_push($dbProductColor, $rowPhotoReviewInfo['PRODUCT_COLOR']);
    array_push($dbName, preg_replace('/.(?=.$)/u','○',$rowPhotoReviewInfo['NAME']));
}

// insert가 실패했다면 false, 성공이라면 ok
if ($resultPhotoReviewInfo === false) {
    echo json_encode(array('result' => 'fail', 'sql' => $sqlPhotoReviewInfo));
} else {
    echo json_encode(array('result' => 'ok'
        , 'seq'=> $dbSeq
        , 'title'=> $dbTitle
        , 'writer'=> $dbWriter
        , 'contents'=> $dbContents
        , 'starScore'=> $dbStarScore
        , 'evalSize'=> $dbEvalSize
        , 'evalLightness'=> $dbEvalLightness
        , 'evalColor'=> $dbEvalColor
        , 'evalThickness'=> $dbEvalThickness
        , 'savePath'=> $dbSavePath
        , 'productSize'=> $dbProductSize
        , 'productColor'=> $dbProductColor
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