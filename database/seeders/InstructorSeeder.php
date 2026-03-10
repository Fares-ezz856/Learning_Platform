<?php
namespace Database\Seeders;

use App\Models\Instructor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $instructors = [
        //     [
        //         'name'     => 'Mohamed Ali',
        //         'email'    => 'mohamed@example.com',
        //         'password' => Hash::make('password123'),
        //     ],

        //     [
        //         'name'     => 'Ahmed Hassan',
        //         'email'    => 'ahmed@example.com',
        //         'password' => Hash::make('password123'),
        //     ],

        //     [
        //         'name'     => 'Sara Mahmoud',
        //         'email'    => 'sara@example.com',
        //         'password' => Hash::make('password123'),
        //     ],
        // ];

        // foreach ($instructors as $instructor) {
        //     Instructor::create($instructor);
        // }
        // for($i=1;$i<=10;$i++){
        //     Instructor::create([
        //         'name'=>'instructor'.$i,
        //         'email'=>'instructor'.$i.'@example.com',
        //         'password'=>Hash::make('password')
        //     ]);
        // }
        $i=11;
        while($i<=20){
            Instructor::create([
                   'name'=>'instructor'.$i,
                'email'=>'instructor'.$i.'@example.com',
                'password'=>Hash::make('password')
            ]);
            $i++;
        }
    }
}
