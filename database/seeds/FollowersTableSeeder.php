<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $user = $users->find(1);
        $user_id = $user->id;

        $followers = $users->reject(function($user) use ($user_id){
            return $user->id === $user_id;
        });
        $follower_ids = $followers->pluck('id')->toArray();

        $user->follow($follower_ids);

        foreach ($followers as $follower) {
            $follower->follow($user_id);
        }
    }
}

