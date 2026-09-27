<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class DataFetchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->query('q');
        
        // ใช้ Eager Loading เพื่อลดปัญหา N+1 Query
        $movies = Movie::with(['genres', 'directors'])
            ->where('title', 'LIKE', "%{$keyword}%")
            ->orWhereHas('directors', function($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->select('id', 'title', 'release_year', 'poster_image')
            ->limit(10)
            ->get();
            
        return response()->json([
            'status' => 'success',
            'data' => $movies
        ]);
    }
}