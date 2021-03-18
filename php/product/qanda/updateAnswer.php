<?php
/* Q&A답변을 완료한다.
    1.
*/

// seq QANDA 테이블에 있는 Q의 SEQ / 해당 Q의 ANSWER
$seq = $_POST['seq'];
$answer = $_POST['answer'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');

$sql = "UPDATE QANDA 
        SET   ANSWER = '$answer'
            , ANSWER_STATE = '답변완료'
            , ANSWER_YN = 1
        WHERE SEQ = $seq";

$result = mysqli_query($conn, $sql);


// update가 실패했다면 false, 성공이라면 ok
if ($result === false) {
    echo json_encode(array('result'=>'fail'));
} else {
    echo json_encode(array('result'=>'ok'));
}