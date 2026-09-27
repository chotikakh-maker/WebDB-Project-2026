@extends('layouts.master')
@section('title', $movie->title . ' | CineMatrix')

@section('content')
<div class="container mx-auto px-6 py-12 max-w-5xl">
    <!-- ส่วนแสดงข้อมูลภาพยนตร์ -->
    <div class="flex flex-col md:flex-row gap-8 bg-secondary p-8 rounded-2xl border border-gray-800 shadow-2xl mb-12">
        <div class="md:w-1/3">
            <img src="{{ $movie->poster_image ? '/storage/'.$movie->poster_image : 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?q=80&w=400&h=600&fit=crop' }}" alt="{{ $movie->title }}" class="w-full rounded-lg shadow-lg">
        </div>
        <div class="md:w-2/3">
            <h1 class="text-4xl font-black text-white mb-2">{{ $movie->title }}</h1>
            <p class="text-accent text-xl font-bold mb-4">ปีที่ฉาย: {{ $movie->release_year }} | ความยาว: {{ $movie->duration_minutes ?? '-' }} นาที</p>
            <div class="flex gap-2 mb-6">
                @foreach($movie->genres as $genre)
                    <span class="bg-gray-800 border border-gray-700 text-gray-300 text-sm px-3 py-1 rounded">{{ $genre->name }}</span>
                @endforeach
            </div>
            <h3 class="text-xl font-bold text-white mb-2 border-b border-gray-800 pb-2">เรื่องย่อ</h3>
            <p class="text-gray-400 leading-relaxed">{{ $movie->synopsis ?? 'ไม่มีข้อมูลเรื่องย่อ' }}</p>
        </div>
    </div>

    <!-- ส่วนแสดงรีวิวและระบบซ่อนสปอยล์ -->
    <div class="mb-12">
        <h2 class="text-3xl font-black text-white mb-6 border-l-4 border-accent pl-4">รีวิวจากผู้ชม</h2>
        
        @if(session('success'))
            <div class="bg-green-900/50 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
        @endif

        @forelse($movie->reviews as $review)
            <div class="bg-secondary p-6 rounded-lg border border-gray-800 mb-4">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-accent font-bold">{{ $review->user->name }} <span class="text-gray-500 text-sm ml-2">ให้คะแนน {{ $review->rating }}/5</span></h4>
                    <!-- ปุ่มรีพอร์ต (ดีไซน์ไว้เผื่อเชื่อมต่อ Backend) -->
                    <button class="text-xs text-gray-500 hover:text-red-500 transition">🚨 รายงานความไม่เหมาะสม</button>
                </div>

                @if($review->is_spoiler)
                    <div class="relative group">
                        <input type="checkbox" id="spoiler-{{ $review->id }}" class="peer hidden">
                        <label for="spoiler-{{ $review->id }}" class="absolute inset-0 z-10 flex items-center justify-center cursor-pointer peer-checked:hidden bg-primary/80 rounded border border-red-900/50 backdrop-blur-sm transition-all hover:bg-primary/90">
                            <span class="text-red-500 font-bold text-sm tracking-wide">⚠️ คอมเมนต์นี้มีเนื้อหาสปอยล์ (คลิกเพื่อดู)</span>
                        </label>
                        <p class="text-gray-300 peer-checked:opacity-100 opacity-0 transition-opacity duration-300 select-none peer-checked:select-auto p-2">
                            {{ $review->comment }}
                        </p>
                    </div>
                @else
                    <p class="text-gray-300">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">ยังไม่มีรีวิวสำหรับภาพยนตร์เรื่องนี้</p>
        @endforelse
    </div>

    <!-- ฟอร์มเพิ่มรีวิว (เช็คว่าล็อกอินหรือยัง) -->
    @auth
        <div class="bg-primary p-8 rounded-2xl border border-gray-700">
            <h3 class="text-2xl font-bold text-white mb-4">เขียนรีวิวของคุณ</h3>
            <form action="{{ route('reviews.store', $movie->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-300 font-bold mb-2">คะแนน (1-5)</label>
                    <input type="number" name="rating" min="1" max="5" required class="w-full bg-secondary border border-gray-700 text-white px-4 py-2 rounded focus:border-accent outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-300 font-bold mb-2">ความคิดเห็น</label>
                    <textarea name="comment" rows="4" required class="w-full bg-secondary border border-gray-700 text-white px-4 py-2 rounded focus:border-accent outline-none"></textarea>
                </div>
                <div class="mb-6 flex items-center">
                    <input type="checkbox" name="is_spoiler" id="is_spoiler" class="w-4 h-4 text-accent bg-secondary border-gray-700 rounded focus:ring-accent accent-accent">
                    <label for="is_spoiler" class="ml-2 text-gray-400 font-bold cursor-pointer text-sm">คอมเมนต์นี้มีเนื้อหาสปอยล์ (ซ่อนไว้)</label>
                </div>
                <button type="submit" class="bg-accent text-black font-bold px-6 py-3 rounded hover:bg-yellow-500 transition">บันทึกรีวิว</button>
            </form>
        </div>
    @else
        <div class="bg-secondary p-8 rounded-lg border border-gray-800 text-center">
            <p class="text-gray-400 mb-4">กรุณาเข้าสู่ระบบเพื่อเขียนรีวิว</p>
            <a href="/login" class="bg-accent text-black px-6 py-2 rounded font-bold hover:bg-yellow-500 transition">เข้าสู่ระบบ</a>
        </div>
    @endauth
</div>
@endsection