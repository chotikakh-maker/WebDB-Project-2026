<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CineMatrix | Premium Database')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 
                        primary: '#000000',      /* ดำสนิท */
                        secondary: '#111111',    /* ดำเทา */
                        accent: '#f59e0b',       /* เหลือง Amber (คล้ายแบรนด์ที่ต้องการ) */
                        accentHover: '#d97706'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-primary text-gray-300 font-sans antialiased selection:bg-accent selection:text-black flex flex-col min-h-screen">

    <nav class="sticky top-0 z-50 bg-primary border-b border-gray-800 shadow-lg">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-3xl font-black tracking-tighter text-white flex items-center gap-1">
                CINE<span class="bg-accent text-black px-2 py-0.5 rounded-md ml-1">MATRIX</span>
            </a>
            
            <div class="flex items-center space-x-6">
    <a href="/" class="text-sm font-bold text-gray-300 hover:text-accent transition">หน้าแรก</a>
    
    <!-- เช็คสถานะการเข้าสู่ระบบ -->
    @guest
        <a href="/login" class="text-sm font-bold text-gray-300 hover:text-white transition">เข้าสู่ระบบ</a>
        <a href="/register" class="text-sm font-bold bg-accent text-black px-4 py-2 rounded hover:bg-yellow-600 transition shadow-[0_0_10px_rgba(245,158,11,0.3)]">สมัครสมาชิก</a>
    @else
        <!-- ถ้าเป็น Admin (role_id = 1) -->
        @if(auth()->user()->role_id == 1)
            <a href="/admin/analytics" class="text-sm font-bold bg-gray-800 text-white px-4 py-2 rounded border border-gray-600 hover:border-accent hover:text-accent transition">
                Admin Dashboard
            </a>
        <!-- ถ้าเป็น User ทั่วไป -->
        @else
            <a href="/movies/create" class="text-sm font-bold border border-accent text-accent px-4 py-2 rounded hover:bg-accent hover:text-black transition">
                + เสนอเพิ่มหนัง
            </a>
        @endif
        
        <!-- ปุ่ม Logout -->
        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
            @csrf
            <button type="submit" class="text-sm font-bold text-gray-500 hover:text-red-500 transition">ออกจากระบบ</button>
        </form>
    @endguest
</div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-secondary border-t border-gray-800 mt-20 py-8 text-center text-gray-500 text-sm">
        <p>&copy; 2026 CineMatrix. SC362004 / SC362005</p>
    </footer>

    @yield('scripts')
</body>
</html>