<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScraperController;

class ScraperScrapeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes the data from hacker news and stores the data';

    public function handle()
    {
        $scraper = new ScraperController;
        $scraper->scraper();
        $this->info('Scraping Success');
    }
}
