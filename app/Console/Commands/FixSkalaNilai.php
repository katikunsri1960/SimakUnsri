<?php

namespace App\Console\Commands;

use App\Models\SkalaNilai;
use App\Services\Feeder\FeederAPI;
use Illuminate\Console\Command;

class FixSkalaNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-skala-nilai';

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
        $dataSkala = SkalaNilai::all();
        $count = 0;

        foreach ($dataSkala as $d) {
            $id_bobot = $d->id_bobot_nilai;
            $filter = "id_bobot_nilai = '$id_bobot'";
            $req = new FeederAPI('GetListSkalaNilaiProdi', 0, 1, null, $filter);
            $result = $req->runWS();

            if (isset($result['error_code']) && empty($result['data'])) {
                $this->info(json_encode($result));
                $this->info('ID Bobot Nilai: '.$id_bobot);

                SkalaNilai::where('id_bobot_nilai', $id_bobot)->delete();
                $count++;
            }

        }
        $this->info('Total data yang dihapus: '.$count);
    }
}
