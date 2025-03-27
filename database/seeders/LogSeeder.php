<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogSeeder extends Seeder
{
    public function run()
    {
        // Delete all old log data
        DB::table('logs')->truncate();

        $actions = [
            'approve_post', 'create_comment', 'create_post', 'delete_comment',
            'delete_notification', 'delete_post', 'login', 'logout',
            'notify_admin', 'read_notification', 'register', 'update_comment',
            'update_post', 'upload_image', 'view_post', 'toggle_like',
            'admin_mark_read', 'admin_mark_all_read', 'user_mark_read', 'user_mark_all_read'
        ];

        $data = [];
        $startDate = now()->subDays(6); // Start from 7 days ago to the present

        for ($day = 0; $day < 7; $day++) {
            // Set the date sequentially
            $currentDate = $startDate->copy()->addDays($day);

            // Set the number of logs for that day randomly (but not duplicated)
            $logCount = rand(5, 20) + ($day * 2); // The value will be different each day
            $usedActions = []; // Store used actions

            for ($i = 0; $i < $logCount; $i++) {
                // Random time of the day (from 00:00 to 23:59)
                $time = Carbon::createFromFormat('Y-m-d H:i:s', $currentDate->format('Y-m-d') . ' 00:00:00')
                    ->addMinutes(rand(0, 1439));  // Random time within 24 hours

                $action = $actions[array_rand($actions)];  // Random action from array
                $usedActions[] = $action;

                $data[] = [
                    'user_id' => rand(1, 15),  // Simulate multiple users
                    'action' => $action,
                    'description' => "{$action} action performed",
                    'created_at' => $time->format('Y-m-d H:i:s'),
                    'updated_at' => $time->format('Y-m-d H:i:s'),
                ];
            }

            // 🔥 **Add actions that are not yet present for that day**
            $missingActions = array_diff($actions, $usedActions);
            foreach ($missingActions as $missingAction) {
                $time = Carbon::createFromFormat('Y-m-d H:i:s', $currentDate->format('Y-m-d') . ' 00:00:00')
                    ->addMinutes(rand(0, 1439));

                $data[] = [
                    'user_id' => rand(1, 5),
                    'action' => $missingAction,
                    'description' => "{$missingAction} action performed",
                    'created_at' => $time->format('Y-m-d H:i:s'),
                    'updated_at' => $time->format('Y-m-d H:i:s'),
                ];
            }
        }

        // Save data to the database
        DB::table('logs')->insert($data);
    }
}