<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\V1\VerificationCodesRequest;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodesRequest $request, EasySms $easySms) {
        $mobile = $request->mobile;
        $template = $this->getTemplate($request->type);
        if (!app()->environment('production')) {
            $code = '999999';
        } else {
            $code = str_pad(random_int(10000, 999999), 6, 0, STR_PAD_LEFT);
            try {
                $result = $easySms->send($mobile, [
                    'template' => $template,
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('aliyun')->getMessage();
                $this->response->error('短信发送异常', 422);
            }
        }

        $key = 'verificationCode_'. str_random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存验证码 10分钟过期。
        \Cache::put($key, ['mobile' => $mobile, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }

    protected function getTemplate($type)
    {
        $templates = [
            'login'         => 'SMS_167531317',
            'bind'          => 'SMS_167526480',
        ];
        try {
            return $templates[$type];
        } catch (\Exception $exception) {
            $this->response->errorBadRequest();
        }
    }
}
