<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;

class SubscribersController extends Controller
{
    public function list()
    {
        return response()->json(
            Subscriber::all()
        );
    }
}
