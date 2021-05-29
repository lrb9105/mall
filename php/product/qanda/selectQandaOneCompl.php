<?php
/* 리뷰를 가져온다.
    1.
*/

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

// 후기번호
$seq = $_POST['seq'];

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
                 WHERE SEQ = $seq
                 ";

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
    array_push($dbName, preg_replace('/.(?=.$)/u','○',$rowQAndAInfo['NAME']));
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
    , 'name'=> $dbName));
}