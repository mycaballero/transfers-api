<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class DemandTypeException extends Exception
{
    protected $message;

    /**
     * UnavailableStandException constructor.
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct();
        $this->message = $message;
    }

    public function render(): \Illuminate\Foundation\Application|Response|Application|ResponseFactory
    {
        return response(['error' => $this->message], 500);
    }
}
