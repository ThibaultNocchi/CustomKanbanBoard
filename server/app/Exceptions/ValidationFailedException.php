<?php

namespace App\Exceptions;

use App\Exceptions\MyException;

class ValidationFailedException extends MyException
{

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if(!isset($this->_data['message'])){
            $this->_data['message'] = 'Data couldn\'t be validated.';
        }
        $this->_code = 422;
        return parent::render();
    }
}