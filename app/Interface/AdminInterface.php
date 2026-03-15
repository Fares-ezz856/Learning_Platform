<?php
namespace App\Interface;
interface AdminInterface{
    public function register(array $data);
    public function deletecourse($id);
    public function deletelesson($id);
    public function dashboard();

}
