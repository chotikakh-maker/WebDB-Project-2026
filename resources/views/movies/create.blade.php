@extends('layouts.master')
@section('title', 'เสนอเพิ่มภาพยนตร์ | CineMatrix')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-3xl">
    <div class="bg-secondary p-8 md:p-10 rounded-2xl border border-gray-800 shadow-2xl">
        <h2 class="text-3xl font-black text-white mb-2">
            <span class="text-accent">+</span> เสนอเพิ่มภาพยนตร์ใหม่
        </h2>
        <p class="text-gray-400 mb-8 border-b border-gray-800 pb-6">ข้อมูลที่เสนอจะถูกส่งให้ผู้ดูแลระบบ (Admin) ตรวจสอบก่อนแสดงผลจริง</p>

        <form action="{{ route('movies.store.user') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!-- ชื่อหนัง -->
            <div>
                <label class="block text-gray-300 font-bold mb-2">ชื่อภาพยนตร์ <span class="text-accent">*</span></label>
                <input type="text" name="title" required 
                       class="w-full bg-primary border border-gray-700 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition shadow-inner">
            </div>

            <!-- เรื่องย่อ -->
            <div>
                <label class="block text-gray-300 font-bold mb-2">เรื่องย่อ</label>
                <textarea name="synopsis" rows="4" 
                          class="w-full bg-primary border border-gray-700 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition shadow-inner"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- ปีที่ฉาย -->
                <div>
                    <label class="block text-gray-300 font-bold mb-2">ปีที่ฉาย (ค.ศ.) <span class="text-accent">*</span></label>
                    <input type="number" name="release_year" required placeholder="เช่น 2026" 
                           class="w-full bg-primary border border-gray-700 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition shadow-inner">
                </div>
                <!-- ความยาว -->
                <div>
                    <label class="block text-gray-300 font-bold mb-2">ความยาว (นาที)</label>
                    <input type="number" name="duration_minutes" placeholder="เช่น 120" 
                           class="w-full bg-primary border border-gray-700 text-white px-4 py-3 rounded-lg focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition shadow-inner">
                </div>
            </div>

            <!-- โปสเตอร์ -->
            <div>
                <label class="block text-gray-300 font-bold mb-2">อัปโหลดรูปโปสเตอร์</label>
                <input type="file" name="poster_image" accept="image/*" 
                       class="w-full bg-primary border border-gray-700 text-gray-400 px-4 py-3 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-black file:bg-accent file:text-black hover:file:bg-yellow-600 cursor-pointer transition">
            </div>

            @if(session('success'))
    <div class="bg-green-900/50 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6 flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        {{ session('success') }}
    </div>
@endif

            <!-- Submit Button -->
            <div class="pt-6">
                <button type="submit" class="w-full bg-accent text-black font-black text-lg py-4 rounded-lg hover:bg-yellow-500 transition-all transform hover:scale-[1.02] shadow-[0_0_15px_rgba(245,158,11,0.3)]">
    ส่งข้อมูลให้ Admin ตรวจสอบ
</button>
            </div>
        </form>
    </div>
</div>
@endsection