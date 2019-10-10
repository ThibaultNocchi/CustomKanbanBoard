<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class MyException extends Exception {

    protected $_data = [];
    protected $_code = 400;

    public function __construct(string $message = '', MessageBag $validation_errors = null, int $code = 0, Throwable $previous = null) {
        if ('' != $message) {
            $this->_data['message'] = $message;
        }
        if($validation_errors){
            $this->_data['errors'] = $validation_errors;
        }
        parent::__construct($message, $code, $previous);
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report() {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return \Illuminate\Http\Response
     */
    public function render() {
        $this->_data['success'] = false;
        $this->_data['exception'] = (new \ReflectionClass(get_class($this)))->getShortName();
        if (!isset($this->_data['message'])) {
            $this->_data['message'] = 'An unknown error occurred. Please retry.';
        }
        ksort($this->_data);
        return response()->json($this->_data, $this->_code);
    }
}