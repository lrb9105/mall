<?php
// DB연결
$conn = mysqli_connect('127.0.0.1', 'lrb9105', '!vkdnj91556', 'MALL');
// 첫번째 메뉴정보
$sqlFirstMenuInfo = "SELECT MENU_ID 
                          , MENU_NAME 
                          , (SELECT COUNT(*) FROM PRODUCT WHERE FIRST_CATEGORY = MENU_ID AND USE_YN = 'Y') CNT 
                     FROM MENU 
                     WHERE DEPTH = 2
                     ORDER BY CAST(MENU_PARENT_ID AS UNSIGNED), MENU_ORDER
            ";
$resultFirstMenuInfo = mysqli_query($conn, $sqlFirstMenuInfo);
$countFirstMenuInfo = mysqli_num_rows($resultFirstMenuInfo);

//두번째 메뉴 정보
$sqlSecondMenuInfo = "SELECT MENU_ID 
                          , MENU_NAME 
                          , (SELECT COUNT(*) FROM PRODUCT WHERE SECOND_CATEGORY = MENU_ID AND USE_YN = 'Y') CNT 
                     FROM MENU 
                     WHERE DEPTH = 3
                     AND USE_YN = 'Y'
                     ORDER BY CAST(MENU_PARENT_ID AS UNSIGNED), MENU_ORDER
            ";
$resultSecondMenuInfo = mysqli_query($conn, $sqlSecondMenuInfo);
$countSecondMenuInfo = mysqli_num_rows($resultSecondMenuInfo);

$sizeArr = array(3, 3, 3, 3, 3);
?>

<div class="col-lg-2">
    <!--
    *** MENUS AND FILTERS ***
    _________________________________________________________
    -->
    <div class="card sidebar-menu mb-4">
        <div class="card-header">
            <h3 class="h4 card-title">카테고리</h3>
        </div>
        <div class="card-body">
            <ul class="nav nav-pills flex-column category-menu" id="category-menu">
                <?for($i = 0; $i< $countFirstMenuInfo; $i++){
                    $rowFirstMenuInfo = mysqli_fetch_array($resultFirstMenuInfo);
                    $cnt = $sizeArr[$i];
                    ?>
                    <?if($i == 0){?>
                        <li><a href="#" aria-expanded="false" data-toggle="collapse" data-target="#collapse<?=$i?>" class="nav-link"><?=$rowFirstMenuInfo[1]?> <span class="badge badge-secondary"><?=$rowFirstMenuInfo[2]?></span></a>
                    <?} else{?>
                        <li><a href="#" aria-expanded="false" data-toggle="collapse" data-target="#collapse<?=$i?>" class="nav-link"><?=$rowFirstMenuInfo[1]?> <span class="badge badge-secondary"><?=$rowFirstMenuInfo[2]?></span></a>
                    <?}?>
                    <ul id="collapse<?=$i?>" class="list-unstyled outer_ul collapse">
                    <?for($j = 0; $j < $cnt; $j++) {
                        $rowSecondMenuInfo = mysqli_fetch_array($resultSecondMenuInfo);
                        ?>
                        <li><a href="category.php?menu_no=<?=$rowSecondMenuInfo[0]?>" class="nav-link" ><?=$rowSecondMenuInfo[1]?><span style="text-align: left;" class="badge badge-light"><?=$rowSecondMenuInfo[2]?></span></a>
                    <?}?>
                    </ul>
                <?}?>
            </ul>
        </div>
    </div>
</div>