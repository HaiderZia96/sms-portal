<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\UserGroup;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $group = UserGroup::create(['name' => 'admin']);
        $group_id = $group->id;
        $user = User::create([
            'name' => 'Web Team',
            'email' => 'web@tuf.edu.pk',
            'role' => '1',
            'status' => '1',
            'group_id' => $group_id,
            'email_verified_at' => now(),
            'password' => bcrypt('XE1L4wKHHXdpul4P')
        ]);
    }
}
