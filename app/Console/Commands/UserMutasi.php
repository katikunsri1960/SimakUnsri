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
            ->where('rp.id_prodi', '88a3482b-3cc8-4beb-9d48-c3da2f1501bd')
            // ->limit(5)
            ->get();

        $this->info($users->count());
        $count = 0;
        $userCount = $users->count();
        DB::beginTransaction();
        foreach ($users as $user) {
            $riwayat = $dbRiwayat->where('nim', $user->username)->where('id_jenis_daftar', 8)->first();
            $this->info($riwayat->id_registrasi_mahasiswa. " - ". $riwayat->nim. " - ". $riwayat->nama_mahasiswa);
            $this->info($user->fk_id. " - " .$user->username. ' - '. $user->name);
            $user->update(['fk_id' => $riwayat->id_registrasi_mahasiswa]);
            $count++;
        }
        DB::commit();
        $this->info('Total user: '. $userCount);
        $this->info('Total user updated: '. $count);

    }
}
