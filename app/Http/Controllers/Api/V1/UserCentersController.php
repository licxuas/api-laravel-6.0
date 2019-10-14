<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Api\Controller;
use App\Transformers\UserTransformer;

class UserCentersController extends Controller
{
    public function index()
    {
        return $this->response->item($this->u(), new UserTransformer());
    }
}
