<?php
include_once('./MyForms/field.php');

abstract class Form {
    protected $fields = array();
    protected $_errors = 'this is error';
    protected $field_objs = array();        //form的域列表
    protected $pass_validate = true;

    //实例化表单的每个域对象
    public function __construct() {
        $validators = array();
        foreach($this->fields as $field=>$value) {
            $field_type_class = $value['type'];
            $name = $field;

            $validators = isset($value['validators']) ? $value['validators']:array();
            $this->$field = new $field_type_class($name, $validators, $this);
            array_push($this->field_objs, $field);
        }
    }
    
    //当调用表单字段时，给表单附加属性
    public function __call($name, $args) {
        if (in_array($name, $this->field_objs)) {
            if (empty($args)) {
                return $this->$name;
            }else {
                if (!is_array($args[0])){
                    return $this->$name;
                } else {
                    return $this->$name->set_attr($args[0]);
                }
            }
        } else {
            throw new Exception("undefine field");
        }
    }

    //提交表单
    /*
    循环form的域列表，
    将post上来的数据赋值给对应的表单域，
    执行每个表单域对象的validate_data方法
    */
    public function validate_on_submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($this->field_objs as $obj) {
                $this->$obj->data = $_POST[$this->$obj->name];
                if (!$this->$obj->validate_data()) {
                    $this->pass_validate = false;
                }
            }
            return $this->pass_validate;
        }
        return false;
    }

    public function render() {
        $res = '<form method="post">';
        foreach ($this->field_objs as $obj) {
            $res .= $this->$obj;
        }
        $res .= '</form>';
        return $res;
    }
}