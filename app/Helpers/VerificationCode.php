<?php declare(strict_types=1);

namespace App\Helpers;

use Dingo\Api\Exception\ResourceException;

/**
 * Class VerificationCode
 * @package App\Helpers
 */
class VerificationCode
{
    /**
     * @var string
     */
    protected $key = 'key';

    /**
     * @var string
     */
    protected $code = 'code';

    /**
     * @return array
     */
    public function auth()
    {
        $verifyData = \Cache::get(request()->get($this->key));
        if (!$verifyData) {
            throw new ResourceException('验证码已失效', [$this->code => '验证码已失效']);
        }
        if (!hash_equals($verifyData['code'], request()->get($this->code))) {
            throw new ResourceException('验证码错误', [$this->code => '验证码错误']);
        }

        \Cache::forget(request()->get($this->key));
        return $verifyData;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey(string $key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }
}
