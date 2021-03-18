<?php
/*id체크 모듈
    1. 아이디 존재하는지 확인
    2. 없다면 'ok'반환
    3. 있으면 'duplication'반환
*/
function passwordGenerator( $length=12 ){

    $counter = ceil($length/4);
    // 0보다 작으면 안된다.
    $counter = $counter > 0 ? $counter : 1;

    $charList = array(
        array("0", "1", "2", "3", "4", "5","6", "7", "8", "9", "0"),
        array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
        array("!", "@", "#", "%", "^", "&", "*")
    );
    $password = "";
    for($i = 0; $i < $counter; $i++)
    {
        $strArr = array();
        for($j = 0; $j < count($charList); $j++)
        {
            $list = $charList[$j];

            $char = $list[array_rand($list)];
            $pattern = '/^[a-z]$/';
            // a-z 일 경우에는 새로운 문자를 하나 선택 후 배열에 넣는다.
            if( preg_match($pattern, $char) ) array_push($strArr, strtoupper($list[array_rand($list)]));
            array_push($strArr, $char);
        }
        // 배열의 순서를 바꿔준다.
        shuffle( $strArr );

        // password에 붙인다.
        for($j = 0; $j < count($strArr); $j++) $password .= $strArr[$j];
    }
    // 길이 조정
    return substr($password, 0, $length);
}

$name = $_POST['name'];
$phone_num1 = $_POST['phoneNum1'];
$phone_num2 = $_POST['phoneNum2'];
$phone_num3 = $_POST['phoneNum3'];
$login_id = $_POST['loginId'];

//mysql연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
$sql = "SELECT LOGIN_ID
        FROM USER 
        WHERE NAME ='$name' 
        AND PHONE_NUM1 = '$phone_num1'
        AND PHONE_NUM2 = '$phone_num2'
        AND PHONE_NUM3 = '$phone_num3'
        AND LOGIN_ID = '$login_id'
        ";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

// 해당 유저가 존재한다면
if($row[0] != null){
    $tempPw = passwordGenerator();
    $sql = "UPDATE USER SET PASSWORD = '$tempPw' WHERE LOGIN_ID = '$login_id'";

    $result = mysqli_query($conn, $sql);

    echo json_encode(array('result'=>'ok','temp_pw'=>$tempPw));
} else{ // 해당 아이디를 사용중이라면
    echo json_encode(array('result'=>'null'));
}