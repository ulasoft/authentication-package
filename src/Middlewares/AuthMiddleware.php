<?php

namespace Usoft\Auth\Middleware;

use Closure;
use Usoft\Users\Services\AuthorizationService;

use App\Http\Resources\ErrorResource;

use Usoft\Users\Exceptions\UnAuthorizedException;
use App\Domain\Users\Exceptions\JWTDecodeException;


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
