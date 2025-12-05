<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\Service\ServiceCollection;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use App\Traits\Common;

class ServiceController extends Controller
{
    use Common;
    
    public function index()
    {
        try {
            $services = Service::select('id', 'name')->get();
            return $this->success(new ServiceCollection($services), __('messages.services_fetch_success'));
        } catch (Exception $e) {
            return $this->fail([], $e->getMessage());
        }
    }
}
