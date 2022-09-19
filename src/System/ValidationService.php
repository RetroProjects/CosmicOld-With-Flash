<?php
namespace Cosmic\System;

use Cosmic\System\Helpers\Validation\Pattern;
use Cosmic\System\Helpers\Validation\Figure;
use Cosmic\System\Helpers\Validation\Captcha;

use Rakit\Validation\Validator;

class ValidationService
{
  
    public static function validate($array, $post = null) 
    {
        $validator = new Validator();
      
        $validator->addValidator('pattern', new Pattern());
        $validator->addValidator('figure', new Figure());
        $validator->addValidator('captcha', new Captcha());

        $validation = $validator->validate(($post != null) ? $post : $_POST, $array);
        ($validation->fails()) ? response()->json([
            'status'   => 'error',
            'message' => $validation->errors()->all()[0]
        ]) : true;
    }
}
