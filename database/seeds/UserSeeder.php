<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
    // Membuat role admin
    $adminRole = new Role();
    $adminRole->name = "admin";
    $adminRole->display_name = "Admin";
    $adminRole->save();

    // Membuat role karyawan
    $karyawanRole = new Role();
    $karyawanRole->name = "karyawan";
    $karyawanRole->display_name = "Karyawan";
    $karyawanRole->save(); 

    // Membuat sample admin
    $admin = new User();
    $admin->name = 'Admin Larapus';
    $admin->email = 'admin@gmail.com';
    $admin->password = bcrypt('rahasia'); 
    $admin->no_hp = '-';
    $admin->alamat = '-';
    $admin->status = '1'; 
    $admin->save();
    $admin->attachRole($adminRole); 

    // Membuat sample karyawan
    $karyawan = new User();
    $karyawan->name = "Sample Karyawan";
    $karyawan->email = 'karyawan@gmail.com';
    $karyawan->password = bcrypt('rahasia'); 
    $karyawan->no_hp = '-';
    $karyawan->alamat = '-'; 
    $karyawan->save();
    $karyawan->attachRole($karyawanRole);
    }
}
