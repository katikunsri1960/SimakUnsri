<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UserMutasi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:user-mutasi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $db = new User();
        $dbRiwayat = new RiwayatPendidikan();
        $users = $db->join('riwayat_pendidikans as rp', 'users.fk_id', '=', 'rp.id_registrasi_mahasiswa')
            ->select('users.id as id', 'users.fk_id as fk_id', 'users.username as username', 'users.name as name', 'rp.id_registrasi_mahasiswa as id_registrasi_mahasiswa', 'rp.nim as nim', 'rp.nama_mahasiswa as nama_mahasiswa')
            ->where('rp.id_prodi', '88a3482b-3cc8-4beb-9d48-c3da2f1501bd')
            // ->limit(5)
            ->get();

        $this->info($users->count());
        $count = 0;
        $userCount = $users->count();
        $dataGagal = [];
        DB::beginTransaction();
        try {
            foreach ($users as $user) {
                $riwayat = $dbRiwayat->where('nim', $user->username)->where('id_jenis_daftar', 8)->first();
                if (!$riwayat) {
                    $this->info('Riwayat Tidak Di temukan: '. $user->username);
                    $dataGagal[] = [
                        'nim' => $user->username,
                        'nama_mahasiswa' => $user->name
                    ];
                    // continue;
                } else {
                    $this->info($riwayat->id_registrasi_mahasiswa. " - ". $riwayat->nim. " - ". $riwayat->nama_mahasiswa);
                    $this->info($user->fk_id. " - " .$user->username. ' - '. $user->name);
                    if ($user->fk_id != $riwayat->id_registrasi_mahasiswa) {
                        // Debugging information
                        $this->info('Updating user: ' . $user->username . ' with fk_id: ' . $riwayat->id_registrasi_mahasiswa);
                        $new = User::find($user->id);
                        if ($new) {
                            $new->fk_id = $riwayat->id_registrasi_mahasiswa;
                            $updated = $new->save();
                            if ($updated) {
                                $this->info('User updated successfully: ' . $user->username);
                            } else {
                                $this->error('Failed to update user: ' . $user->username);
                            }
                        } else {
                            $this->error('User not found: ' . $user->id);
                        }
                    }
                    $this->info('User updated'. ' - ' . $user->fk_id);
                    $count++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Transaction failed: ' . $e->getMessage());
        }

        $this->info('Total user: '. $userCount);
        $this->info('Total user updated: '. $count);

        if (count($dataGagal) > 0) {
            $this->info('Data Gagal: ');
            foreach ($dataGagal as $data) {
                $this->info($data['nim']. ' - '. $data['nama_mahasiswa']);
            }
        }
    }
}
