<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    public function index()
    {
    	$services = Cache::remember('orders', 3600*24*30, function () {
            return Service::all();
        });
        
        return new ServiceCollection($services);
    }
}
