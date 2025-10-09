<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\EscortModel;

class EscortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default = 200;
        $count = env('ESCORT_SEED_COUNT') ?: $default;

        $batchSize = 100;

        $kategoris = ['Ambulans', 'Karyawan', 'Perorangan', 'Satlantas'];
        $jenis_kelamin_pasien_options = ['Laki-laki', 'Perempuan'];

        $created = 0;
        // Get Faker instance from the container (works across Laravel versions)
        $faker = app(\Faker\Generator::class);
        while ($created < $count) {
            $toCreate = min($batchSize, $count - $created);
            $rows = [];
            for ($i = 0; $i < $toCreate; $i++) {
                $kategori = $kategoris[array_rand($kategoris)];
                $jenis_kelamin_pasien = $jenis_kelamin_pasien_options[array_rand($jenis_kelamin_pasien_options)];

                if ($jenis_kelamin_pasien === 'Laki-laki') {
                    $nama_pengantar = $faker->firstNameMale() . ' ' . $faker->lastName();
                } else {
                    $nama_pengantar = $faker->firstNameFemale() . ' ' . $faker->lastName();
                }

                $nomor_hp = preg_replace('/[^0-9]/', '', $faker->phoneNumber());
                $nomor_hp = substr($nomor_hp, 0, 15);

                // Ambulance name pattern
                $nama_ambulan = 'Ambulans ' . rand(1, 20);

                $nama_pasien = $faker->name();

                $rows[] = [
                    'kategori_pengantar' => $kategori,
                    'nama_pengantar' => $nama_pengantar,
                    'jenis_kelamin_pasien' => $jenis_kelamin_pasien,
                    'nomor_hp' => $nomor_hp,
                    'nama_ambulan' => $kategori === 'Ambulans' ? $nama_ambulan : null,
                    'nama_pasien' => $nama_pasien,
                    'foto_pengantar' => null,
                    'submission_id' => (string) Str::uuid(),
                    'submitted_from_ip' => null,
                    'api_submission' => false,
                    'status' => $faker->randomElement(['pending', 'verified', 'rejected']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Use bulk insert for performance; model events will not fire.
            EscortModel::insert($rows);
            $created += $toCreate;
        }
    }
}
