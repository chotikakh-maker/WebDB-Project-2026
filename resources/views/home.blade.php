@extends('layouts.master')

@section('title', 'หน้าแรก | CineMatrix')

@section('content')
<!-- Hero Section (ส่วนหัวดึงดูดสายตา) -->
<section class="relative pt-20 pb-32 flex flex-col items-center justify-center overflow-hidden">
    <!-- แสงพื้นหลัง (Glow Effect) -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-blue-600/20 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
        <h1 class="text-5xl md:text-7xl font-black text-white mb-6 tracking-tight leading-tight">
            ค้นพบภาพยนตร์ที่คุณ <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">หลงใหลที่สุด</span>
        </h1>
        <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto font-light">
            ฐานข้อมูลภาพยนตร์เชิงวิเคราะห์ระดับ Enterprise ค้นหา กรองข้อมูล และดูสถิติได้อย่างรวดเร็วแบบเรียลไทม์
        </p>

        <!-- Search Input พรีเมียม -->
        <div class="relative max-w-3xl mx-auto group">
            <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none">
                <svg class="w-6 h-6 text-slate-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text" id="liveSearchInput" 
                   class="w-full bg-secondary/80 backdrop-blur-sm text-white text-xl py-6 pl-16 pr-6 rounded-3xl border border-slate-700/50 shadow-2xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder:text-slate-500" 
                   placeholder="พิมพ์ชื่อหนัง, หมวดหมู่ (เช่น War, Action)...">
            
            <!-- Loading Spinner (ซ่อนไว้ก่อน) -->
            <div id="loadingIcon" class="hidden absolute right-6 top-1/2 -translate-y-1/2">
                <svg class="w-8 h-8 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- ผลลัพธ์การค้นหา -->
<section class="container mx-auto px-6 pb-20">
    <div id="searchGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- ข้อมูลหนังจำลอง (โชว์ตอนเพิ่งเปิดเว็บก่อนค้นหา) -->
        <div class="col-span-full text-center py-20 text-slate-500">
            <p class="text-xl font-light">พิมพ์ชื่อภาพยนตร์ในช่องค้นหาด้านบนเพื่อเริ่มต้น</p>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const input = document.getElementById('liveSearchInput');
    const grid = document.getElementById('searchGrid');
    const loader = document.getElementById('loadingIcon');
    let debounceTimer;

    // ฟังก์ชันสร้าง Card ภาพยนตร์สไตล์ Netflix
    const createMovieCard = (movie) => {
        const genres = movie.genres ? movie.genres.map(g => `<span class="bg-blue-500/10 text-blue-400 border border-blue-500/20 text-xs px-2 py-1 rounded-md">${g.name}</span>`).join(' ') : '';
        const posterUrl = movie.poster_image ? `/storage/${movie.poster_image}` : 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?q=80&w=400&h=600&fit=crop';
        
        return `
            <div class="group relative bg-secondary rounded-2xl overflow-hidden border border-slate-800 hover:border-slate-600 hover:shadow-2xl hover:shadow-blue-500/10 transition-all duration-300 transform hover:-translate-y-2 cursor-pointer h-[450px] flex flex-col">
                <div class="relative h-[300px] overflow-hidden">
                    <img src="${posterUrl}" alt="${movie.title}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 opacity-80 group-hover:opacity-100">
                    <div class="absolute inset-0 bg-gradient-to-t from-secondary via-secondary/20 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 flex flex-wrap gap-2">
                        ${genres}
                    </div>
                </div>
                <div class="p-5 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1 truncate">${movie.title}</h3>
                        <p class="text-slate-400 text-sm font-medium">ปีที่ฉาย: <span class="text-blue-400">${movie.release_year}</span></p>
                    </div>
                    <button class="w-full mt-4 bg-slate-800 hover:bg-blue-600 text-white text-sm font-bold py-2 rounded-xl transition-colors">ดูรายละเอียด</button>
                </div>
            </div>
        `;
    };

    input.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            grid.innerHTML = '<div class="col-span-full text-center py-20 text-slate-500"><p class="text-xl font-light">พิมพ์ชื่อภาพยนตร์ในช่องค้นหาด้านบนเพื่อเริ่มต้น</p></div>';
            return;
        }

        loader.classList.remove('hidden');

        // หน่วงเวลาพิมพ์ (Debounce)
        debounceTimer = setTimeout(async () => {
            try {
                // อย่าลืมเช็คว่า /api/v1/search ใช้งานได้จริงใน routes/api.php
                const response = await fetch(`/api/v1/search?q=${encodeURIComponent(query)}`);
                const json = await response.json();
                
                loader.classList.add('hidden');
                grid.innerHTML = '';

                if(json.data.length === 0) {
                    grid.innerHTML = `<div class="col-span-full text-center py-20 text-rose-400"><p class="text-xl font-light">ไม่พบภาพยนตร์ที่ค้นหา</p></div>`;
                    return;
                }

                json.data.forEach(movie => {
                    grid.insertAdjacentHTML('beforeend', createMovieCard(movie));
                });
            } catch (error) {
                console.error('AJAX Error:', error);
                loader.classList.add('hidden');
                grid.innerHTML = `<div class="col-span-full text-center py-20 text-rose-500"><p>เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์</p></div>`;
            }
        }, 500);
    });
</script>
@endsection