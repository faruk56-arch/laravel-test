<?php

use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('notifications')->insert([
            0 => [
                'id' => '1',
                'type' => 'App\Notifications\WithMail',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => '1',
                'data' => '{"research_id":2,"status":"in_progress"}',
            ],
            1 => [
                'id' => '2',
                'type' => 'App\Notifications\WithoutMail',
                'notifiable_type' => 'App\Models\User',
                'notifiable_id' => '1',
                'data' => '{"research_id":3,"status":"finished"}',
            ],
        ]);
    }
}
