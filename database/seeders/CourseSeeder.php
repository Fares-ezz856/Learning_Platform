<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $courses = [
        //     [
        //         'title' => 'Course 1',
        //         'description' => 'Description 1',

        //         'instructor_id' => 1,
        //     ],
        //     [
        //         'title' => 'Course 2',
        //         'description' => 'Description 2',

        //         'instructor_id' => 2,
        //     ],
        // ];
        // foreach ($courses as $course) {
        //     Course::create($course);
        // }

        // $i=1;
        // while($i<=10){
        //     Course::create([
        //         'title'=>'Course '.$i,
        //         'description'=>'Description '.$i,
        //         'instructor_id'=>rand(1,4)
        //     ]);
        //     $i++;
        // }
        for($i=11;$i<=20;$i++){
            Course::create([
                'title'=>'Course '.$i,
                'description'=>'Description '.$i,
                'instructor_id'=>rand(1,4)
            ]);
        }
    }
}
