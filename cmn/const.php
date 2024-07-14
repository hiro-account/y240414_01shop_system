<?php

const I_0 = 0;
const I_1 = 1;
const I_2 = 2;
const I_3 = 3;

const STAFF_LOGIN = 'スタッフログイン';
const STAFF_ADD = 'スタッフ登録';


const DELIMITER = '_';
const NOT_ENTERED = 'が未入力';
const UNSELECTED = 'が未選択';

const KEY_ARR = 'key_arr';
const VAL_ARR = 'val_arr';

const STAFF_ID = 'staff_id';
const STAFF_PASS = 'staff_pass';
const STAFF_PASS2 = 'staff_pass2';
const STAFF_NAME = 'staff_name';

const LOGIN = "login";

//--------------------------------------------------------------------------------
// const ERR_PAGE = 'Location: ../cmn/err.php?h2=';
const ERR_HEADER = 'Location: ../cmn_err/err.php?from=';
const ERR_MSG = '&err_msg=';
const HREF = '&href=';

const A = 'a';
const H2 = 'h2';

//TODO:パスワードがクリアされたりされなかったりは保留(24/04/19)
const A_HISTORY_BACK = '<a href="javascript: history.back();">戻る</a>';
// const A_HISTORY_BACK = '<input type="button" value="戻る" onclick="history.back();">';
const A_STAFF_LOGIN = '<a href="../staff_login/staff_login.html">' . STAFF_LOGIN . 'へ</a>';


//--------------------------------------------------------------------------------



//--------------------------------------------------------------------------------
const STAFF_LOGIN_CHECK = 'staff_login_check';
const SYSTEM_TOP = 'system_top';

const STAFF_ADD_CHECK = 'staff_add_check';


const FROM = 'from';


//--------------------------------------------------------------------------------


const FOR_STAFF_TOP = <<<EOT
<div class="m-t-05em">スタッフ一覧（スタッフ更新、スタッフ削除はスタッフ詳細を表示）</div>
<form method="post" action="staff_detail.php">
<table class="border">
<tr><th>スタッフID</th><th>スタッフ名</th><th>スタッフ詳細</th><tr>

EOT;



/*--------------------------------------------------------------------------------
以下新設のため一時整理済
--------------------------------------------------------------------------------*/
// パス？
const LOCATION = 'Location: ';
const HTTP = 'http://';

//--------------------------------------------------------------------------------
const LF = "\n";



?>
