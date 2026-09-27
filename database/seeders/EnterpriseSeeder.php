<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, User, Studio, Genre, Movie, Actor, Director, Review};

class EnterpriseSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Roles & Auth
        $roleAdmin = Role::create(['name' => 'Super Admin']);
        $roleUser = Role::create(['name' => 'User']);
        User::create(['role_id' => $roleAdmin->id, 'name' => 'Admin KKU', 'email' => 'admin@kku.ac.th', 'password' => bcrypt('password')]);

        // 2. Setup Data Sources
        $studio = Studio::create(['name' => 'Universal Pictures', 'country' => 'USA']);
        $war = Genre::create(['name' => 'War']);
        $action = Genre::create(['name' => 'Action']);
        $thriller = Genre::create(['name' => 'Thriller']);
        $sniper = Genre::create(['name' => 'Sniper']);
        
        $actor1 = Actor::create(['name' => 'Mark Wahlberg', 'birthdate' => '1971-06-05']);
        $director1 = Director::create(['name' => 'Peter Berg']);

        // 3. Create Complex Movie Entry
        $movie = Movie::create([
            'studio_id' => $studio->id,
            'title' => 'Lone Survivor',
            'synopsis' => 'Marcus Luttrell and his team set out on a mission to capture or kill notorious Taliban leader Ahmad Shah.',
            'release_year' => 2013,
            'duration_minutes' => 121,
        ]);

        // 4. Attach Pivot with Extra Columns
        $movie->genres()->attach([$war->id, $action->id, $sniper->id]);
        $movie->directors()->attach([$director1->id]);
        $movie->actors()->attach([$actor1->id => ['character_name' => 'Marcus Luttrell']]); // M-to-M แบบระบุข้อมูลใน Pivot

        // 5. Generate Mass Data for Advanced Analytics
        for ($i = 1; $i <= 30; $i++) {
            $user = User::create(['role_id' => $roleUser->id, 'name' => "Student $i", 'email' => "std$i@cinematrix.com", 'password' => bcrypt('1234')]);
            Review::create([
                'user_id' => $user->id,
                'movie_id' => $movie->id,
                'rating' => mt_rand(35, 50) / 10, // สุ่ม 3.5 - 5.0
                'comment' => "หนังเรื่องนี้ออกแบบ Production ได้ดีมาก ให้คะแนนความสมจริง",
                'is_approved' => true
            ]);
        }
    }
}