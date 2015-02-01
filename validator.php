<?php
abstract class Validator {
    protected $msg;

    public function __construct($args) {
        $this->msg = isset($args[0]) ? $args[0]:null;
    }

    abstract function run($form, $field);
}

class Required extends Validator {

    public function run($form, $field) {
        if (empty($field->data)) {
            $msg = $this->msg == null ? $field->name.' is required!':$this->msg;
            $field->error = $msg;
            return false;
        }
        return true;
    }
}

class Email extends Validator {
    public function run($form, $field) {
        $pattern = '/^.+@[^.].*\.[a-z]{2,10}$/';
        if (!preg_match($pattern, $field->data)) {
            $msg = $this->msg == null ? $field->name.' require a email!':$this->msg;
            $field->error = $msg;
            return false;
        }
        return true;
    }
}

class EqualTo extends Validator {
    public function __construct($args) {
        $this->equal = isset($args[0]) ? $args[0]:null;
        $this->msg = isset($args[1]) ? $args[1]:null;
        if (!$this->equal) {
            throw new Exception("EqualTo validator need a equal field");
        }
    }

    public function run($form, $field) {
        $equal = $this->equal;
        if ($field->data != $form->$equal->data) {
            $msg = $this->msg == null ? $field->name.' not equal to '.$form->$equal->data.'!':$this->msg;
            $field->error = $msg;
            return false;
        }
        return true;
    }
}

class Length extends Validator {
    public function __construct($args) {
        $this->lt = isset($args[0]) ? $args[0]:null;
        $this->gt = isset($args[1]) ? $args[1]:null;
        $this->msg = isset($args[2]) ? $args[2]:null;
        if (!$this->lt || !$this->gt) {
            throw new Exception("require begin & end number");
        }
    }

    public function run($form, $field) {
        if (count($field->data) < $this->lt || count($field->data) > $this->gt) {
            $msg = $this->msg == null ? $field->name.' should be between '.$this->lt.' and '. $this->gt .'!':$this->msg;
            $field->error = $msg;
            return false;
        }
        return true;
    }
}