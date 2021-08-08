<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\Files\FilesHandler;

class FillBase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fill:base';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fill in the database by running the necessary migrations and seeds.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        exec('cd ' . base_path() . ' && php artisan migrate --force');

        exec('cd ' . base_path() . ' && php artisan --force db:seed --class=UserSuperAdminSeeder');

        $this->newLine();

        //data of the overridden superadmin
        $this->info('Remember the superadmin data:');
        $this->newLine();
        $admin_login = FilesHandler::returnValueFromFileByKey( base_path() . '/database/seeders/', 'UserSuperAdminSeeder.php', 'email', '=>');
        $this->info('LOGIN: ' . $admin_login);
        $this->newLine();
        $admin_passvord = FilesHandler::returnValueFromFileByKey( base_path() . '/database/seeders/', 'UserSuperAdminSeeder.php', 'password', '=>');
        $admin_passvord = trim( trim($admin_passvord, "bcrypt('"), "')" );
        $this->info('PASSWORD: ' . $admin_passvord);
        $this->newLine();

        $this->info('The initial data has been successfully entered into the database!');

        return 0;
    }
}
