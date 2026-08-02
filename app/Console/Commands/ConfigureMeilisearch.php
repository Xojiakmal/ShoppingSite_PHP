<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Meilisearch\Client;

class ConfigureMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:configure';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Meilisearch configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $client = new Client(config('scout.meilisearch.host'));

        $client = new Client(
            config('scout.meilisearch.host'),
            config('scout.meilisearch.key')
        );

        $index = $client->index('products');

        $index->updateFilterableAttributes([
            'category',
            'price',
        ]);

        $index->updateSortableAttributes([
            'price',
            // 'created_at',
        ]);

        $this->info('Meilisearch configured.');
    }
}
