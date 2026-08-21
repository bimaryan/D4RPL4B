<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Project;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        User::updateOrCreate(
            ['email' => 'admin.d4rpl4b@ryaze.my.id'],
            [
                'name' => 'Admin D4RPL4B',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Create Students
        $students = [
            ["2205031", "ADITYA WISNU SETYA PAMUNGKAS"], ["2205032", "AHMAD MUZZAQQI ALFATHU DZIKRI"],
            ["2205033", "AHMAD ZOHARI"], ["2205034", "ALIN MAULIDAH"],
            ["2205035", "ANWAR MUSYADAD"], ["2205036", "BIMA RYAN ALFARIZI"],
            ["2205037", "DHIMAS BAGUS HANDIRA"], ["2205038", "DINDA MULYASARI"],
            ["2205039", "EVAN NURFAUZAN"], ["2205040", "FERLI SEPTIANA"],
            ["2205041", "FITRI YANI"], ["2205042", "GUSTIAN PRAYOGA JANUAR"],
            ["2205043", "IMAM ARYOSO"], ["2205044", "KOKO APRILIYANTAMA"],
            ["2205045", "LAYSHA NAZWARI IKLIES PUTRI"], ["2205046", "LISNA DESANTI"],
            ["2205047", "MOHAMMAD FARHAN FIRDAUS"], ["2205048", "MUHAMMAD CHAIDAR ALI"],
            ["2205050", "MUHAMMAD RANDI FAUZY"], ["2205051", "NICHO RAY RAMADHAN"],
            ["2205052", "NITA ADITYA ELFANI"], ["2205053", "RAMA DWI GUSPARA"],
            ["2205054", "RENDI HIDAYAT"], ["2205055", "SAHANATUR RIZKI"],
            ["2205056", "SALYA SHELOMITA CANDARANAYA"], ["2205057", "SIFAHUL MUTMAINAH"],
            ["2205058", "TEGUH FEBRIYANA"], ["2205059", "TIFANI ROSMEINAWATI"],
            ["2205060", "YUDI HERLAMBANG"], ["2105034", "ANUGERAH AHMAD FACHRUROCHIM"]
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['nim' => $student[0]],
                [
                    'name' => ucwords(strtolower($student[1])),
                    'password' => $student[0],
                    'github_url' => 'https://github.com',
                    'linkedin_url' => 'https://linkedin.com',
                ]
            );
        }
    }
}
