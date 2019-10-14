<?php

namespace App\Http\Controllers\Api;

use Dingo\Api\Routing\Helpers;

class Controller extends \App\Http\Controllers\Controller
{
    use Helpers;

    /**
     * @return App\Models\User $user
     */
    public function u()
    {
        return $this->user ?: request()->user('api');
    }
}
