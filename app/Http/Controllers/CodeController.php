<?php

namespace App\Http\Controllers;

use App\Http\Business\V2\CodeBusiness;

class CodeController extends Controller
{
    private $code_business;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CodeBusiness $code_business)
    {
        //
        $this->code_business = $code_business;
    }

    public function create($type,$code) {
        return $this->code_business->create($type,$code);
    }

    public function read($type,$rankCount) {
        return $this->code_business->read($type,$rankCount);
    }

    public function count($type) {
        return $this->code_business->count($type);
    }

    public function cleanTime() {
        return $this->code_business->CleanTime();
    }
}
