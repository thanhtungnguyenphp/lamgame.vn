<?php
namespace App\Http\Controllers\Api\Sport;

use App\Http\Controllers\Controller;
use App\Models\Sport\Sport;
use Illuminate\Support\Facades\Cache;

class SportController extends Controller
{
    public function index()
    {
        $data = Cache::remember('sports:all', 86400, fn () => Sport::orderBy('order')->get(['id', 'name', 'icon', 'order']));
        return response()->json(['data' => $data]);
    }
}
