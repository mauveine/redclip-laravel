<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class PostController extends Base
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function test (Request $request) {
        return $this->respond([]);
    }

    public function create (Request $request) {

    }
}
