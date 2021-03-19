<?php
/* 리뷰를 가져온다.
    1.
*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 상품번호
$seq = $_POST['seq'];
// 타입 0: 포토후기, 1: 일반후기
$type = $_POST['type'];

// 실제 데이터
if($type == 0){
    $sqlReviewInfo = "SELECT   R.SEQ,
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
                                OPL.PRODUCT_SIZE,
                                OPL.PRODUCT_COLOR,
                                U.NAME,
                                F.SAVE_PATH
                 FROM REVIEW R
                 INNER JOIN ORDER_PRODUCT_LIST OPL ON R.PRODUCT_SEQ = OPL.PRODUCT_SEQ
                 INNER JOIN USER  U ON U.LOGIN_ID = R.WRITER
                 INNER JOIN FILE  F ON R.SEQ = F.REVIEW_SEQ
                 WHERE R.SEQ = $seq
                 AND OPL.REVIEW_YN = '1'
                 AND OPL.REVIEW_SEQ = R.SEQ
                 AND R.USE_YN = 'Y'
                 ORDER BY CRE_DATETIME DESC";
} else{
    $sqlReviewInfo = "SELECT   R.SEQ,
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
                                OPL.PRODUCT_SIZE,
                                OPL.PRODUCT_COLOR,
                                U.NAME
                 FROM REVIEW R
                 INNER JOIN ORDER_PRODUCT_LIST OPL ON R.PRODUCT_SEQ = OPL.PRODUCT_SEQ
                 INNER JOIN USER  U ON U.LOGIN_ID = R.WRITER
                 WHERE R.SEQ = $seq
                 AND OPL.REVIEW_YN = '1'
                 AND OPL.REVIEW_SEQ = R.SEQ
                 AND R.USE_YN = 'Y'
                 ORDER BY CRE_DATETIME DESC";
}


$resultReviewInfo = mysqli_query($conn, $sqlReviewInfo);

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
while($rowReviewInfo = mysqli_fetch_array($resultReviewInfo)) {
    array_push($dbSeq, $rowReviewInfo['SEQ']);
    array_push($dbTitle, $rowReviewInfo['TITLE']);
    array_push($dbWriter, $rowReviewInfo['WRITER']);
    array_push($dbContents, $rowReviewInfo['CONTENTS']);
    array_push($dbStarScore, $rowReviewInfo['STAR_SCORE']);
    array_push($dbEvalSize, $rowReviewInfo['EVAL_SIZE']);
    array_push($dbEvalLightness, $rowReviewInfo['EVAL_LIGHTNESS']);
    array_push($dbEvalColor, $rowReviewInfo['EVAL_COLOR']);
    array_push($dbEvalThickness, $rowReviewInfo['EVAL_THICKNESS']);
    array_push($dbProductSize, $rowReviewInfo['PRODUCT_SIZE']);
    array_push($dbProductColor, $rowReviewInfo['PRODUCT_COLOR']);
    array_push($dbName, $rowReviewInfo['NAME']);
    array_push($dbSavePath, $rowReviewInfo['SAVE_PATH']);
}

// select가 실패했다면 false, 성공이라면 ok
if ($resultReviewInfo === false) {
    echo json_encode(array('result' => 'fail', 'sql' => $sqlReviewInfo));
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
        , 'productSize'=> $dbProductSize
        , 'productColor'=> $dbProductColor
        , 'name'=> $dbName
        , 'savePath'=> $dbSavePath));
}