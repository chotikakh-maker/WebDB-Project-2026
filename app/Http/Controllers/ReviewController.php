<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request, $movieId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'movie_id' => $movieId,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_spoiler' => $request->has('is_spoiler'), // ถ้าติ๊กซ่อนสปอยล์มาจะเป็น true
            'is_approved' => true, // สมมติให้อนุมัติเลยเพื่อความรวดเร็ว
        ]);

        return back()->with('success', 'บันทึกรีวิวของคุณแล้ว!');
    }
}