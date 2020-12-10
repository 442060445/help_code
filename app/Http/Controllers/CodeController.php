<?php

namespace App\Http\Controllers;

use App\Http\Business\CodeBusiness;
use App\Http\Business\ConversionBusiness;
use App\Http\Common\Helper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

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

    public function read($type) {
        return $this->code_business->read($type);
    }

    public function count($type) {
        return $this->code_business->count($type);
    }
}
