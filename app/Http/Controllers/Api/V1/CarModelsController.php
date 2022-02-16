<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarModelCollection;
use App\Models\CarModel;
use Illuminate\Support\Facades\Cache;

class CarModelsController extends Controller
{
    public function index()
    {
        $carModels = Cache::remember('carModels', 3600*24*30, function () {
            return CarModel::paginate(10);
        });
        
        return new CarModelCollection($carModels);
    }
}
