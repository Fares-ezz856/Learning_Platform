<?php
namespace App\Interface;
interface StudentInterface{
    public function register(array $data);
    public function courses();
    public function mycourses();
    public function mylessons();
}
