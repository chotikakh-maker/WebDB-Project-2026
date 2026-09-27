<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Movie, ActivityLog};
use Illuminate\Support\Facades\{DB, Storage, Auth};

class MovieManagerController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validation กรองข้อมูลอย่างเข้มงวด
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1900|max:2100',
            'duration_minutes' => 'required|integer|min:1',
            'studio_id' => 'required|exists:studios,id',
            'poster_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // อัปโหลดสูงสุด 5MB
            'genre_ids' => 'required|array',
            'actor_ids' => 'required|array',
            'actor_characters' => 'required|array', // รับค่าบทบาทนักแสดงคู่กับ actor_ids
            'director_ids' => 'required|array'
        ]);

        $posterPath = null;
        if ($request->hasFile('poster_image')) {
            $posterPath = $request->file('poster_image')->store('posters', 'public');
        }

        // 2. Database Transaction: หาก Query ใดล้มเหลว จะยกเลิกทั้งหมด
        DB::beginTransaction();
        try {
            // Insert ข้อมูลหลัก
            $movie = Movie::create([
                'title' => $validated['title'],
                'synopsis' => $request->synopsis,
                'release_year' => $validated['release_year'],
                'duration_minutes' => $validated['duration_minutes'],
                'studio_id' => $validated['studio_id'],
                'poster_image' => $posterPath
            ]);

            // Insert Pivot: Genres & Directors
            $movie->genres()->attach($validated['genre_ids']);
            $movie->directors()->attach($validated['director_ids']);

            // Insert Pivot: Actors พร้อมระบุ Character Name
            $actorData = [];
            foreach ($validated['actor_ids'] as $index => $actorId) {
                $actorData[$actorId] = ['character_name' => $validated['actor_characters'][$index]];
            }
            $movie->actors()->attach($actorData);

            // บันทึก Audit Log
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'CREATE_MOVIE',
                'details' => 'Added movie ID: ' . $movie->id,
                'ip_address' => $request->ip()
            ]);

            DB::commit();
            return redirect()->route('admin.movies.index')->with('success', 'บันทึกภาพยนตร์ระดับ Enterprise สำเร็จ');

        } catch (\Exception $e) {
            DB::rollBack();
            // ลบไฟล์ภาพที่ถูกอัปโหลดไปแล้วหาก Database Error
            if ($posterPath && Storage::disk('public')->exists($posterPath)) {
                Storage::disk('public')->delete($posterPath);
            }
            return back()->withInput()->with('error', 'ระบบล้มเหลว: ' . $e->getMessage());
        }
    }
}