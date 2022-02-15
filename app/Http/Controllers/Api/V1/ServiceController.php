<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCollection;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return new ServiceCollection(Service::all());
    }
}
