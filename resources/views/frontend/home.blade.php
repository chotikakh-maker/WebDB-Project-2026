<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>CineMatrix Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .loader { border-top-color: #3498db; -webkit-animation: spinner 1.5s linear infinite; animation: spinner 1.5s linear infinite; }
        @keyframes spinner { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen">
    <header class="p-6 text-center border-b border-slate-700 bg-slate-800 shadow-xl">
        <h1 class="text-5xl font-black bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
            CINEMATRIX
        </h1>
        <p class="mt-2 text-slate-400 font-light tracking-widest">ADVANCED MOVIE ANALYTICS PLATFORM</p>
    </header>

    <main class="container mx-auto p-8">
        <!-- Live Search Component -->
        <div class="max-w-3xl mx-auto relative">
            <input type="text" id="liveSearchInput" 
                   class="w-full bg-slate-800 text-white text-xl p-5 rounded-2xl border border-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-900 transition-all shadow-2xl" 
                   placeholder="ค้นหาภาพยนตร์, ทหาร, สไนเปอร์, หรือผู้กำกับ...">
            <div id="loadingIcon" class="hidden absolute right-5 top-5 w-8 h-8 border-4 border-slate-600 rounded-full loader"></div>
        </div>

        <!-- แสดงผลลัพธ์แบบ Dynamic Grid -->
        <div id="searchGrid" class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Data will be injected here via Vanilla JS -->
        </div>
    </main>

    <script>
        const input = document.getElementById('liveSearchInput');
        const grid = document.getElementById('searchGrid');
        const loader = document.getElementById('loadingIcon');
        let debounceTimer;

        input.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            clearTimeout(debounceTimer); // ยกเลิกการค้นหาถ้าพิมพ์ต่อเนื่อง (Debounce)

            if (query.length < 2) {
                grid.innerHTML = '';
                return;
            }

            loader.classList.remove('hidden');

            // หน่วงเวลา 400ms ก่อนยิง API (ประสิทธิภาพระดับ Enterprise)
            debounceTimer = setTimeout(async () => {
                try {
                    const response = await fetch(`/api/v1/search?q=${encodeURIComponent(query)}`);
                    const json = await response.json();
                    
                    loader.classList.add('hidden');
                    grid.innerHTML = '';

                    if(json.data.length === 0) {
                        grid.innerHTML = `<div class="col-span-full text-center text-slate-500 text-xl py-10">ไม่พบภาพยนตร์ในฐานข้อมูล</div>`;
                        return;
                    }

                    json.data.forEach(movie => {
                        // ดึง Genres ออกมาเป็น Tags
                        const genres = movie.genres.map(g => `<span class="bg-slate-700 text-xs px-3 py-1 rounded-full border border-slate-500">${g.name}</span>`).join('');
                        
                        grid.insertAdjacentHTML('beforeend', `
                            <article class="bg-slate-800 rounded-2xl overflow-hidden shadow-2xl hover:-translate-y-2 transition-transform duration-300 border border-slate-700">
                                <img src="/storage/${movie.poster_image ?? 'default.jpg'}" class="w-full h-80 object-cover opacity-90 hover:opacity-100 transition-opacity" alt="${movie.title}">
                                <div class="p-6">
                                    <h3 class="text-2xl font-bold text-blue-100 truncate">${movie.title}</h3>
                                    <p class="text-slate-400 mt-1 mb-4 text-sm font-semibold">RELEASED: ${movie.release_year}</p>
                                    <div class="flex flex-wrap gap-2 mb-4">${genres}</div>
                                    <a href="/movie/${movie.id}" class="block text-center w-full bg-gradient-to-r from-blue-600 to-blue-800 text-white font-bold py-3 rounded-xl hover:from-blue-500 hover:to-blue-700 shadow-lg">VIEW ANALYTICS</a>
                                </div>
                            </article>
                        `);
                    });
                } catch (error) {
                    console.error('Data Fetch Failed:', error);
                    loader.classList.add('hidden');
                }
            }, 400);
        });
    </script>
</body>
</html>