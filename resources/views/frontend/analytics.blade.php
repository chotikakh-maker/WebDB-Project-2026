<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Analytics | CineMatrix</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 font-sans">
    <nav class="bg-blue-900 text-white px-6 py-4 flex justify-between items-center shadow-md">
        <div class="text-xl font-bold">CineMatrix Admin Panel</div>
        <a href="/" class="text-blue-200 hover:text-white transition">← กลับหน้าแรก</a>
    </nav>

    <main class="container mx-auto p-8">
        <h2 class="text-3xl font-black text-slate-800 mb-8">📊 รายงานวิเคราะห์ข้อมูลภาพยนตร์เชิงลึก</h2>
        
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-200">
            <table class="w-full text-left">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="p-5 font-semibold">หมวดหมู่ (Genre)</th>
                        <th class="p-5 font-semibold">จำนวนหนัง</th>
                        <th class="p-5 font-semibold">ยอดรีวิวทั้งหมด</th>
                        <th class="p-5 font-semibold text-center">เรตติ้งเฉลี่ย</th>
                        <th class="p-5 font-semibold">ภาพยนตร์ยอดเยี่ยมในหมวด</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <!-- ข้อมูลตรงนี้ดึงมาจาก AnalyticsController ที่เขียน Raw SQL ไว้ -->
                    @forelse($analytics as $data)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-5 text-lg font-bold text-blue-900">{{ $data->genre_name }}</td>
                        <td class="p-5 text-slate-600">{{ $data->total_movies }} เรื่อง</td>
                        <td class="p-5 text-slate-600">{{ $data->total_reviews }} รีวิว</td>
                        <td class="p-5 text-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold">
                                ⭐ {{ number_format($data->avg_rating, 2) }}
                            </span>
                        </td>
                        <td class="p-5 font-semibold text-slate-700">
                            {{ $data->top_movie_in_genre ?? 'ยังไม่มีข้อมูล' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-500 text-xl">ไม่มีข้อมูลสถิติที่ตรงตามเงื่อนไข (ต้องการรีวิว > 5)</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>