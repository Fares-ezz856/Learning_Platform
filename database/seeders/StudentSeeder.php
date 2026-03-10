<?php
namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $students = [
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

        // foreach ($students as $student) {
        //     Student::create($student);
        // }

        // for($i=1;$i<=10;$i++){
        //     Student::create([
        //         'name'=>'student'.$i,
        //         'email'=>'student'.$i.'example.com',
        //         'password'=>Hash::make('password'.$i),
        //     ]);

            $i=11;
            while($i<=20){
                      Student::create([
                'name'=>'student'.$i,
                'email'=>'student'.$i.'example.com',
                'password'=>Hash::make('password'.$i),
            ]);
            $i++;
            }
        }
    }

