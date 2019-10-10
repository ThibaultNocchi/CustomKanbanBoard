<?php

namespace App\Exceptions;

use App\Exceptions\MyException;

class NoLineException extends MyException
{

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        if(!isset($this->_data['message'])){
            $this->_data['message'] = 'The requested object wasn\'t found.';
        }
        $this->_code = 404;
        return parent::render();
    }
}