<?php

namespace Glamstack\GoogleCloud\Commands;

use Illuminate\Console\Command;

class ApiClientCommand extends Command
{
    public $signature = 'apiclient';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
