<?php
require_once('validator.php');

abstract class BaseField {
    public $name;
    public $data;
    public $error;
    protected $validators;
    protected $input=array();
    protected $form;

    public function __construct($name, $validators, $form) {
        $this->name = $name;
        $this->validators = $validators;
        $this->form = $form;
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
        foreach ($this->validators as $value) {
            if (empty($value)) {
                throw new Exception("undefine validator");
            }
            $validator = new $value['type']($value['args']);
            if ($validator->run($this->form, $this)) {
                continue;
            } else {
                return false;
            }
        }
        return true;
    }

}

class TextField extends BaseField {
    protected $input = array('type'=> 'text');
}

class PasswordField extends BaseField {
    protected $input = array('type'=> 'password');

}
class SubmitField extends BaseField {
    protected $input = array('type'=> 'submit');
}