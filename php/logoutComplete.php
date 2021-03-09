<?php
session_start();

setcookie("LOGIN_ID", "", time() - 3600,'/');
setcookie("PW", "", time() - 3600,'/');

// 세션 삭제 완료
if(session_destroy()){
    echo json_encode(array('result'=>'ok', 'COOKIE'=> $_COOKIE['LOGIN_ID']));
} else{
    echo json_encode(array('result'=>'fail'));
}
