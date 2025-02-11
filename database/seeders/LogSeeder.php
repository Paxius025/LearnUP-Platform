<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogSeeder extends Seeder
{
    public function run()
    {
        // ลบข้อมูล log เก่าออกทั้งหมด
        DB::table('logs')->truncate();

        $actions = [
            'approve_post', 'create_comment', 'create_post', 'delete_comment',
            'delete_notification', 'delete_post', 'login', 'logout',
            'notify_admin', 'read_notification', 'register', 'update_comment',
            'update_post', 'upload_image', 'view_post'
        ];

        $data = [];

        // สุ่มข้อมูลด้วย for loop จำนวน 100 รายการ
        for ($i = 0; $i < 100; $i++) {
            // สุ่มวันที่ในช่วง 7 วันหลังสุด
            $date = now()->subDays(rand(0, 6));  // สุ่มวันที่ในช่วง 7 วันที่ผ่านมา
            // สุ่มเวลาในวันนั้น (จาก 00:00 ถึง 23:59)
            $time = Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' 00:00:00')
                ->addMinutes(rand(0, 1439));  // สุ่มเวลาในช่วง 24 ชั่วโมง

            $action = $actions[array_rand($actions)];  // สุ่ม action จาก array

            // สุ่มวันและเวลาให้กระจาย
            $data[] = [
                'date' => $time->format('Y-m-d H:i:s'),
                'action' => $action
            ];
        }

        // บันทึกข้อมูลลงฐานข้อมูล
        foreach ($data as $item) {
            DB::table('logs')->insert([
                'user_id' => 1,  
                'action' => $item['action'],
                'description' => "{$item['action']} action performed",
                'created_at' => $item['date'],
                'updated_at' => $item['date'],
            ]);
        }
    }
}