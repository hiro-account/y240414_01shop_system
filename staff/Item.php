<?php

class Item {
    /** 物理名 */
    private $name;
    /** 論理名 */
    private $label;
    /** 初期値(チェック前の値) */
    private $default_value;
    /** エラー値 */
    private $err_value;
    /** エラーメッセージ */
    private $err_msg;
    /** 前回の値 */
    private $prev_value;
    /** サニタイズおよび空白文字の除去後の値 */
    private $trimmed_value;
    /** チェック済の値 */
    private $verified_value;
    /** 書式設定済の値 */
    private $formated_value;
    /** 備考項目 */ //TODO:変数名変更
    private $general;

    function __construct($prm_name, $prm_label, $prm_default_value
        , $prm_err_value, $prm_err_msg, $prm_prev_value, $prm_general) {

        $this->name = $prm_name;
        $this->label = $prm_label;
        $this->default_value = $prm_default_value;
        $this->err_value = $prm_err_value;
        $this->err_msg = $prm_err_msg;
        $this->prev_value = $prm_prev_value;
        $this->general = $prm_general;
    }

    function get_name() {
        return $this->name;
    }

    function get_label() {
        return $this->label;
    }

    function get_verified_value() {
        return $this->verified_value;
    }

    function get_formated_value() {
        return $this->formated_value;
    }

    function set_formated_value($prm_formated_value) {
        $this->formated_value = $prm_formated_value;
    }

    function get_general() {
        return $this->general;
    }

    function convert_sp_char_and_trim() {
        $this->trimmed_value = trim(htmlspecialchars($this->default_value));
    }

    function check_unenter_unslct_value() {
        if (strcmp($this->trimmed_value, $this->err_value) === I_0) {
            return $this->label . $this->err_msg;
        } else {
            $this->verified_value = $this->trimmed_value;

            return NULL;
        }
    }

    function check_value_changed() {
        if (strcmp($this->verified_value, $this->prev_value) !== I_0) {
            return $this->name;
        }
    }

    function is_value_changed() {
        if (strcmp($this->verified_value, $this->prev_value) === I_0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function build_input_type_hidden() {
        return '<input type="hidden" name="' . $this->name . '" value="' . $this->verified_value . '">';
    }

    function build_tr_td_label_value() {
        return '<tr><td>' . $this->label . '</td><td>：' . $this->verified_value . '</td></tr>';
    }


}


?>
