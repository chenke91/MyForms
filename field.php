<?php
abstract class BaseField {
    protected $name;
    public $data;
    protected $validators;
    protected $input=array();

    public function __construct($name, $validators) {
        $this->name = $name;
        $this->validators = $validators;
        $this->input['name'] = $this->name;
        $this->input['id'] = $this->name;
    }

    public function __toString() {
        return self::array2html($this->input);
    }

    // 为表单输入框添加属性
    public function set_attr($args) {
        foreach ($args as $key => $value) {
            $this->input[$key] = $value;
        }
        return self::array2html($this->input);
    }
    //生成input框
    protected static function array2html($array) {
        $res = '';
        foreach ($array as $key => $value) {
            $res .= $key.'='.'"'.$value.'" ';
        }
        return '<input '.$res.'>';
    }

    //根据$this->validators 列表验证该字段
    public function validate_data() {

    }

}

class TextField extends BaseField {
    protected $input = array('type'=> 'text');
}

class PasswordField extends BaseField {
    protected $input = array('type'=> 'password');

}