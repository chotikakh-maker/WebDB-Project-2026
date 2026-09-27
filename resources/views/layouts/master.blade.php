<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineMatrix | Premium Analytics')</title>
    <!-- ใช้ Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#0f172a', secondary: '#1e293b', accent: '#3b82f6' }
                }
            }
        }
    </script>
    <style>
        /* ซ่อน Scrollbar แต่ยังเลื่อนได้ */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0f172a; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="bg-primary text-slate-200 font-sans antialiased selection:bg-accent selection:text-white flex flex-col min-h-screen">

    <!-- Premium Navbar (Glassmorphism Effect) -->
    <nav class="sticky top-0 z-50 backdrop-blur-lg bg-primary/80 border-b border-slate-800 shadow-2xl">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-3xl font-black tracking-tighter text-white flex items-center gap-2 hover:scale-105 transition-transform">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-cyan-400">CINE</span>MATRIX
            </a>
            
            <div class="flex items-center space-x-6">
                <a href="/" class="text-sm font-semibold text-slate-300 hover:text-white transition">หน้าแรก</a>
                <a href="#" class="text-sm font-semibold text-slate-300 hover:text-white transition">หมวดหมู่</a>
                <!-- ปุ่ม Admin แบบพรีเมียม -->
                <a href="/admin/analytics" class="relative inline-flex items-center justify-center px-6 py-2 overflow-hidden font-bold text-white rounded-full bg-slate-800 border border-slate-700 hover:border-blue-500 group transition-all">
                    <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-blue-600 rounded-full group-hover:w-56 group-hover:h-56"></span>
                    <span class="relative flex items-center gap-2">เข้าสู่ระบบ Admin</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- พื้นที่สำหรับเนื้อหาของแต่ละหน้าเว็บ -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-secondary border-t border-slate-800 mt-20 py-8 text-center text-slate-500 text-sm">
        <p>&copy; 2026 CineMatrix. All rights reserved.</p>
        <p class="mt-2">SC362004 Web Application Programming / SC362005 Database Systems</p>
    </footer>

    <!-- พื้นที่สำหรับแทรก JavaScript เฉพาะหน้า -->
    @yield('scripts')

</body>
</html>