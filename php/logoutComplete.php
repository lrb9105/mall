<?php
session_start();
// 세션 삭제 완료
if(session_destroy()){
    echo json_encode(array('result'=>'ok'));
} else{
    echo json_encode(array('result'=>'fail'));
}
