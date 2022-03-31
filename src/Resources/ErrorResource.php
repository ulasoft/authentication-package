<?php

namespace Usoft\Auth\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ErrorResource extends JsonResource
{
    protected $message;

    public function __construct($error, $message)
    {
        Log::error($error);
        $this->message = $message;
    }

    public static $wrap = false;

    public function toArray($request)
    {
        return [
            'message' => $this->message
        ];
    }
}
