<?php

namespace App\Console\Commands;

use App\Models\Dosen\BiodataDosen;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateUserDosen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-user-dosen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate user dosen from table biodata dosen using NIDN as username';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notGenerateStatus = [2, 22];

        $dosen = BiodataDosen::select('id_dosen', 'nidn', 'nama_dosen', 'email')->whereNotIn('id_status_aktif', $notGenerateStatus)->get();

        $db = new User;
        $countDosen = $dosen->count();
        $count = 0;
        foreach ($dosen as $d) {
            $user = $db->where('username', $d->nidn)->first();
            if (! $user) {
                $db->create([
                    'username' => $d->nidn,
                    'password' => bcrypt($d->nidn),
                    'role' => 'dosen',
                    'fk_id' => $d->id_dosen,
                    'name' => $d->nama_dosen,
                    'email' => $d->email,
                ]);

            }

            $count++;

            // output progress
            $this->info('Progress: '.$count.'/'.$countDosen);
        }

        $this->info('Generate user dosen success');

    }
}
