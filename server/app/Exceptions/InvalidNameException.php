<?php

namespace App\Exceptions;

use App\Exceptions\MyException;

class InvalidNameException extends MyException
{

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if(!isset($this->_data['message'])){
            $this->_data['message'] = 'Name already taken.';
        }
        $this->_code = 422;
        return parent::render();
    }
}