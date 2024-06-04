<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

        public function run()
        {
            $userRoles = [
                "aero" =>[
                    "name" => "",
                    "zone" => "",
                    "type" => true
                ],
                "ammar" =>[
                    "name" => "",
                    "zone" => "",
                    "type" => false
                ],
                "BGI" => [
                    "name" => "BANGI",
                    "zone" => "B4",
                    "type" => false
                ],
                "KSL" => [
                    "name" => "KUALA SELANGOR",
                    "zone" => "B1",
                    "type" => false
                ],
                "PKL" => [
                    "name" => "PELABUHAN KLANG",
                    "zone" => "B2",
                    "type" => false
                ],
                "CRS" => [
                    "name" => "CHERAS",
                    "zone" => "B4",
                    "type" => false
                ],
                "BTG" => [
                    "name" => "BANTING",
                    "zone" => "B4",
                    "type" => false
                ],
                "SPG" => [
                    "name" => "SEPANG",
                    "zone" => "PC",
                    "type" => false
                ],
                "PJY" => [
                    "name" => "PETALING JAYA",
                    "zone" => "B1",
                    "type" => false
                ],
                "RWG" => [
                    "name" => "RAWANG",
                    "zone" => "B1",
                    "type" => false
                ],
                "KLP" => [
                    "name" => "KUALA LUMPUR PUSAT",
                    "zone" => "W1",
                    "type" => false
                ],
                "KLG" => [
                    "name" => "KLANG",
                    "zone" => "B2",
                    "type" => false
                ],
                "PCH" => [
                    "name" => "PUCHONG",
                    "zone" => "PK",
                    "type" => false
                ],
                "PPC" => [
                    "name" => "PUTRAJAYA & CYBERJAYA",
                    "zone" => "B4",
                    "type" => false
                ],
            ];

            foreach ($userRoles as $key => $userData) {
                DB::table('users')->insert([
                    'name' => $key,
                    'email' => $key . '@aero.com',
                    'password' => Hash::make('abcd1234'),
                    'is_admin' => $userData['type'],
                    'id_team' => 1,
                    'ba' => $userData['name'],
                    'zone' => $userData['zone'],
                ]);
            }
        }

}
