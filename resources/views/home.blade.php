@extends('layouts.master')
@section('title', 'หน้าแรก | CineMatrix')

@section('content')
<section class="relative pt-24 pb-20 flex flex-col items-center justify-center">
    <div class="relative z-10 text-center max-w-4xl mx-auto px-4">
        <h1 class="text-5xl md:text-7xl font-black text-white mb-4 tracking-tight">
            คลังข้อมูลภาพยนตร์ <br>
            <span class="text-accent">ระดับ Enterprise</span>
        </h1>
        <p class="text-lg text-gray-400 mb-10">ระบบค้นหาและวิเคราะห์สถิติภาพยนตร์ขั้นสูง</p>

        <div class="relative max-w-3xl mx-auto group">
            <input type="text" id="liveSearchInput" 
                   class="w-full bg-secondary text-white text-xl py-5 pl-6 pr-6 rounded-lg border border-gray-700 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-all placeholder:text-gray-600 shadow-2xl" 
                   placeholder="ค้นหาภาพยนตร์, ทหาร, สไนเปอร์...">
            
            <div id="loadingIcon" class="hidden absolute right-6 top-1/2 -translate-y-1/2">
                <div class="w-6 h-6 border-4 border-gray-600 border-t-accent rounded-full animate-spin"></div>
            </div>
        </div>
    </div>
</section>

<section class="container mx-auto px-6 pb-20">
    <div id="searchGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="col-span-full text-center py-20 text-gray-600">พิมพ์ชื่อภาพยนตร์เพื่อเริ่มต้น</div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const input = document.getElementById('liveSearchInput');
    const grid = document.getElementById('searchGrid');
    const loader = document.getElementById('loadingIcon');
    let debounceTimer;

    const createMovieCard = (movie) => {
        const genres = movie.genres ? movie.genres.map(g => `<span class="bg-gray-800 text-gray-300 border border-gray-700 text-xs px-2 py-1 rounded">${g.name}</span>`).join(' ') : '';
        const posterUrl = movie.poster_image ? `/storage/${movie.poster_image}` : 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?q=80&w=400&h=600&fit=crop';
        
        // เปลี่ยนจาก <div class="bg-secondary..."> เป็น <a href="/movies/${movie.id}" class="bg-secondary...">
        return `
            <a href="/movies/${movie.id}" class="bg-secondary rounded-lg overflow-hidden border border-gray-800 hover:border-accent transition-colors duration-300 cursor-pointer flex flex-col block">
                <div class="relative h-[350px]">
                    <img src="${posterUrl}" alt="${movie.title}" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1 truncate">${movie.title}</h3>
                        <p class="text-gray-400 text-sm">ปีที่ฉาย: <span class="text-accent">${movie.release_year}</span></p>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">${genres}</div>
                </div>
            </a>
        `;
    };

    input.addEventListener('input', (e) => {
        const query = e.target.value.trim();
        clearTimeout(debounceTimer);

        if (query.length < 2) {
            grid.innerHTML = '<div class="col-span-full text-center py-20 text-gray-600">พิมพ์ชื่อภาพยนตร์เพื่อเริ่มต้น</div>';
            return;
        }

        loader.classList.remove('hidden');

        debounceTimer = setTimeout(async () => {
            try {
                const response = await fetch(`/api/v1/search?q=${encodeURIComponent(query)}`);
                const json = await response.json();
                
                loader.classList.add('hidden');
                grid.innerHTML = '';

                if(json.data.length === 0) {
                    grid.innerHTML = `<div class="col-span-full text-center py-20 text-accent">ไม่พบภาพยนตร์ที่ค้นหา</div>`;
                    return;
                }

                json.data.forEach(movie => {
                    grid.insertAdjacentHTML('beforeend', createMovieCard(movie));
                });
            } catch (error) {
                loader.classList.add('hidden');
            }
        }, 400);
    });
</script>
@endsection