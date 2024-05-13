<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScraperController;

class ScraperUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates scraped data from hacker news';

    public function handle()
    {
        $scraper = new ScraperController;
        $scraper->update();
        $this->info('Update Success');
    }
}
