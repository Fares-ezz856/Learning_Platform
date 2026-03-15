<?php
namespace App\Interface;
interface InstructorInterface {
    public function register(array $data);
    public function mystudent();
    public function edit(array $data);
    public function getlesson($id);
    public function getreviews();
    public function mycourses();
}
