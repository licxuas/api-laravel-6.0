<?php

namespace App\Http\Controllers\Api\V1;


use App\Helpers\VerificationCode;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\AuthorizationsRequest;
use App\Http\Requests\Api\V1\SocialAuthorizationRequest;
use App\Models\User;
use App\Transformers\UserTransformer;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationsRequest $request, VerificationCode $verificationCode)
    {
        $verifyData = $verificationCode->auth();
        return $this->authUser('mobile', $verifyData);
    }

    public function socialStore($social_type, SocialAuthorizationRequest $request)
    {

    }

    protected function authUser($type, $data)
    {
        switch ($type) {
            case 'mobile':
                $user = $this->mobileAuthorization($data);
                break;

            default:
                throw new HttpException(400, '认证非法');
        }

        $token = \Auth::guard('api')->fromUser($user);
        return $this->response->item($user, new UserTransformer())->withHeader('Authorization', 'Bearer '. $token);
    }

    protected function mobileAuthorization($data)
    {
        $user = User::where('mobile', $data['mobile'])->first();
        if (!$user) {
            $user = new User;
            $user->mobile = $data['mobile'];
            $user->password = bcrypt('123456');
            $user->nick_name = 'YH_' . str_random(6);
            $user->save();
        }
        return $user;
    }
}
