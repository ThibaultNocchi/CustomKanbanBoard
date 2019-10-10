<?php

namespace App\Exceptions;

use App\Exceptions\MyException;

class CantValidateBoardCodeException extends MyException
{

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if(!isset($this->_data['message'])){
            $this->_data['message'] = 'The board code given can\'t be found.';
        }
        $this->_code = 501;
        return parent::render();
    }
}