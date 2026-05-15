<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Province;
use App\Models\Ward;
use File;

class ImportAdminData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-admin-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import provinces and wards from JSON files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonPath = 'c:/laragon/www/kviet/json';
        $files = File::files($jsonPath);

        $this->info('Starting import...');
        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            if ($file->getExtension() !== 'json') {
                continue;
            }

            $content = json_decode(File::get($file->getRealPath()), true);
            if (empty($content)) {
                $bar->advance();
                continue;
            }

            // Get province info from the first entry
            $first = $content[0];
            $provinceString = $first['tinh_thanh']; // e.g. "tỉnh An Giang" or "thành phố Hồ Chí Minh"

            $provinceType = '';
            $provinceName = '';

            if (str_starts_with($provinceString, 'tỉnh ')) {
                $provinceType = 'tỉnh';
                $provinceName = str_replace('tỉnh ', '', $provinceString);
            } elseif (str_starts_with($provinceString, 'thành phố ')) {
                $provinceType = 'thành phố';
                $provinceName = str_replace('thành phố ', '', $provinceString);
            } else {
                $provinceName = $provinceString;
            }

            $province = Province::firstOrCreate([
                'name' => $provinceName,
                'type' => $provinceType
            ]);

            foreach ($content as $item) {
                Ward::create([
                    'province_id' => $province->id,
                    'name' => $item['ten'],
                    'type' => $item['loai'],
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Import completed successfully!');
    }
}
