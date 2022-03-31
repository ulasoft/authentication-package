<?php

namespace Usoft\Auth\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

use App\Models\User;

use App\Domain\Users\Exceptions\JWTDecodeException;
use Usoft\Users\Exceptions\UnAuthorizedException;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthorizationService
{
    protected $token;
    protected $request;
    protected $decode;


    public function __construct($request)
    {
        $this->request = $request;
    }

    public function token(): static
    {
        $this->token = str_replace(["Bearer", " "], "", $this->request->header('Authorization'));
        return $this;
    }

    /**
     * @throws JWTDecodeException
     */
    public function decode(): static
    {
        $key = env('JWT_SECRET');

        try {
            $this->decode = JWT::decode($this->token, new Key($key, 'HS256'));
        } catch (\Exception $exception) {
            throw new JWTDecodeException("JWT DECODE ERROR", 401);
        }

        return $this;
    }

    /**
     * @throws UnAuthorizedException
     */
    public function user(): static
    {
        if (!empty($this->decode)) {

            $user = Cache::store('redis')->remember("user-{$this->decode->sub}", 3600, function () {
                return User::findOrFail($this->decode->sub);
            });

            Auth::setUser($user);

            return $this;
        }

        throw new UnAuthorizedException('STATUS CODE 401, Header: '. $this->request->header('Authorization'), 401);
    }
}
