<?php

const I_0 = 0;
const I_1 = 1;
const I_2 = 2;
const I_3 = 3;

const S_0 = '0';
const S_1 = '1';
const S_2 = '2';
const S_3 = '3';

const DELIMITER = '_';


const HF_S_QUOT = '\'';
const HF_SLASH = '/';   //TODO:参照なし。削除検討

const HYPHEN = '-';

const STAFF_ID = 'staff_id';

const STAFF_PASS = 'staff_pass';

const LOGIN = 'login';



const HIDDEN = 'hidden';



const TEMPORARY = 'temporary';

const PRIVILEGE_A = 'A';
const PRIVILEGE_O = 'O';



const H2 = 'h2';

//----------使用以前に記載されていなければならないため、一時的に移動
const S_STAFF = 'スタッフ';
const S_LOGIN = 'ログイン';


//----------

//TODO:パスワードがクリアされたりされなかったりは保留(24/04/19)
const A_HISTORY_BACK = '<a href="javascript: history.back();">戻る</a>';

const A_STAFF_LOGIN = '<a href="../staff_login/staff_login.html">' . S_STAFF . S_LOGIN . 'へ</a>';

//----------

const LOCATION = 'Location: ';



//----------

const STAFF_LOGIN_CHECK = 'staff_login_check';
const SYSTEM_TOP = 'system_top';

const FROM = 'from';

const LF = "\n";

const EMPTY_STR = '';


const PREV = 'prev_';

const LAST_NAME = 'last_name';
const FIRST_NAME = 'first_name';
const KANA = '_kana';
const SEX = 'sex';
const BIRTH_YEAR = 'birth_year';
const BIRTH_MONTH = 'birth_month';
const BIRTH_DAY = 'birth_day';
const PRIVILEGE = 'privilege';

const ID = 'id';



//----------------------------------------

const ERR_MSG = 'err_msg';
const H2_AND_ERR_MSG = 'h2_and_err_msg';



/*--------------------------------------------------------------------------------
以下「TS_」は開始タグ、「TE_」は終了タグ、「T_」はタグ、「P_」は属性、「E_」は要素の意
--------------------------------------------------------------------------------*/

const TS_P = '<p>';
const TE_P = '</p>';

//TODO:table、formの定数化検討




/*--------------------------------------------------------------------------------
以下「L_」は小文字、「U_」は大文字、「S_」は文字列の意
--------------------------------------------------------------------------------*/

const U_ID = 'ID';

const S_COLON = '：';

const S_GA = 'が';

const S_NO = 'の';

//TODO:下記定数化検討
// const S_WO = 'を';


const S_TOROKU = '登録';
const S_KOSHIN = '更新';



const S_MINYURYOKU = '未入力';
const S_MISENTAKU = '未選択';


const S_SHI = '氏';
const S_MEI = '名';
const S_KANA = '（カナ）';
const S_SEIBETSU = '性別';
const S_SEINENGAPPI = '生年月日';
const S_NEN = '年';
const S_TSUKI = '月';
const S_HI = '日';
const S_KENGEN = '権限';

const S_PASSWORD = 'パスワード';

const S_KAKUNIN = '（確認）';

const S_SHIPPAI = '失敗';

const S_SYSTEM_SHOGAI_HASSEI = '（システム障害発生）';

const S_FUSEI = '不正';

const S_YOMIDASHI = '読み出し';

const S_OTOKO = '男';
const S_ONNA = '女';

const S_HENKO = '変更';

const S_KANRYO = '完了';

//--------------------
// const SEIBETSU_ARR = 'seibetsu_arr';
// const KENGEN_ARR = 'kengen_arr';

// 性別
define('S_SEIBETSU_ARR', [S_0 => HYPHEN, S_1 => S_OTOKO, S_2 => S_ONNA, S_3 => S_MISENTAKU]);
// 権限
define('S_KENGEN_ARR', [PRIVILEGE_O => '一般', PRIVILEGE_A => '管理者']);




/*----------------------------------------
以上整理ずみ。以下参照なし削除可
----------------------------------------
const KEY_ARR = 'key_arr';
const VAL_ARR = 'val_arr';
// const STAFF_PASS2 = 'staff_pass2';参照なし削除可(09/21)
// const STAFF_NAME = 'staff_name';参照なし削除可(09/21)
// const HREF = '&href=';参照なし削除可(09/21)
// const A_HISTORY_BACK = '<input type="button" value="戻る" onclick="history.back();">';参照なし削除可(09/21)
// const FROM_CREATE = 'from_create';  //TODO:9月16日未使用を確認ずみ。場合により削除
// const FROM_UPDATE = 'from_update';  //TODO:9月16日未使用を確認ずみ。場合により削除
// const FROM = 'from';

*/

?>
