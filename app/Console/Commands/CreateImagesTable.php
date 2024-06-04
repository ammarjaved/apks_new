<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateImagesTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iamgeTable:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create table for all tbl_savr table images';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = "insert into test ( ba ) Values  ('testing')";

        // Execute the query
        DB::statement($query);

        $this->info('Nightly query executed successfully.');
    }
}
