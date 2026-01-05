<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanLocalStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanLocalStorage';

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
        $signature = 'signatures';
        $image = 'image';
        $this->delete_directory( $signature );
        $this->delete_directory( $image );

    }


    protected function delete_directory( $directory )
    {
        if( Storage::disk('public')->exists( $directory  ) ) {
            Storage::disk('public')->deleteDirectory( $directory );
        }
    }
}
