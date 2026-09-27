<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;

class MovieController extends Controller
{
    // เปิดหน้าฟอร์ม
    public function create()
    {
        return view('movies.create');
    }

    // รับข้อมูลจากฟอร์มบันทึกลง Database
    public function store(Request $request)
    {
        // 1. ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $request->validate([
            'title' => 'required|string|max:255',
            'release_year' => 'required|integer',
            'duration_minutes' => 'nullable|integer',
            'poster_image' => 'nullable|image|max:2048', // รับเฉพาะไฟล์รูป ไม่เกิน 2MB
        ]);

        // 2. จัดการอัปโหลดรูปภาพ (ถ้ามี)
        $imagePath = null;
        if ($request->hasFile('poster_image')) {
            $imagePath = $request->file('poster_image')->store('posters', 'public');
        }

        // 3. บันทึกข้อมูลลงฐานข้อมูล
        Movie::create([
            'title' => $request->title,
            'synopsis' => $request->synopsis,
            'release_year' => $request->release_year,
            'duration_minutes' => $request->duration_minutes,
            'poster_image' => $imagePath,
            'status' => 'Released',
            'is_approved' => false, // *** สำคัญมาก: ตั้งค่าเป็น false เพื่อรอ Admin อนุมัติ ***
        ]);

        // 4. ส่งกลับไปหน้าเดิมพร้อมข้อความแจ้งเตือน (ต้องอยู่ตรงนี้ก่อนปิดฟังก์ชัน)
        return redirect()->route('movies.create')->with('success', 'ส่งคำขอเพิ่มภาพยนตร์สำเร็จ! รอ Admin ตรวจสอบเพื่อแสดงผลครับ');
    } // <--- ปิดปีกกาของ store ตรงนี้!

    // แสดงรายละเอียดและรีวิว (แยกออกมาอยู่ด้านนอก store แล้ว)
    public function show($id)
    {
        $movie = Movie::with(['genres', 'reviews.user'])->findOrFail($id);
        return view('movies.show', compact('movie'));
    }
}