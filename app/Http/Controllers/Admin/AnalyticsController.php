<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function report()
    {
        $sql = "
            SELECT 
                g.name AS genre_name, 
                COUNT(DISTINCT m.id) AS total_movies,
                COUNT(r.id) AS total_reviews, 
                ROUND(AVG(r.rating), 2) AS avg_rating,
                (
                    SELECT m_sub.title 
                    FROM movies m_sub 
                    JOIN reviews r_sub ON m_sub.id = r_sub.movie_id 
                    JOIN movie_genre mg_sub ON m_sub.id = mg_sub.movie_id
                    WHERE mg_sub.genre_id = g.id 
                    GROUP BY m_sub.id 
                    ORDER BY AVG(r_sub.rating) DESC 
                    LIMIT 1
                ) AS top_movie_in_genre
            FROM genres g
            JOIN movie_genre mg ON g.id = mg.genre_id
            JOIN movies m ON mg.movie_id = m.id
            LEFT JOIN reviews r ON m.id = r.movie_id
            GROUP BY g.id, g.name
            HAVING total_reviews > 5
            ORDER BY avg_rating DESC
        ";

        $analytics = DB::select($sql);
        return view('admin.analytics', compact('analytics'));
    }
}