<?php

namespace App\Exceptions;

use App\Exceptions\MyException;

class UnexpectedException extends MyException
{

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if(!isset($this->_data['message'])){
            $this->_data['message'] = 'Unexpected exception.';
        }
        $this->_code = 500;
        return parent::render();
    }
}