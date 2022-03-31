<?php

namespace Usoft\Auth\Middlewares;

use Closure;
use Usoft\Auth\Services\AuthorizationService;

use Usoft\Auth\Resources\ErrorResource;

use Usoft\Auth\Exceptions\UnAuthorizedException;
use Usoft\Auth\Exceptions\JWTDecodeException;


class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            $user = (new AuthorizationService($request))->token()->decode()->user();
        } catch (UnAuthorizedException | JWTDecodeException $exception) {
            return (new ErrorResource($exception->getMessage(), 'Unauthorized'))->response()->setStatusCode($exception->getCode());
        } catch (\Exception $exception) {
            return (new ErrorResource($exception->getMessage(), trans('app.errors.try_again')))->response()->setStatusCode(403);
        }

        return $next($request);
    }
}
