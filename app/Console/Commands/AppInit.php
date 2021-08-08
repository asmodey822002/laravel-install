<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\Settings\Env\EnvHandler;
use App\Libraries\Files\FilesHandler;
use App\Libraries\Settings\Database\DatabaseHandler;
use App\Libraries\Install\InstallHandler;

class AppInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Init app. Set .env file variables';

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
        $this->info('Init env file. If .env file exists it will be overrided');

        // get .env.example content
        $env_content = EnvHandler::getEnvFileContent(true);

        // get .env content
        $existed_env_content = EnvHandler::getEnvFileContent();
        // set APP_KEY variable from EXISTED env FILE
        $app_key_pair = EnvHandler::returnKeyAndValueFromDotEnvContent(
            $existed_env_content,
            'APP_KEY'
        );

        //was there a file .env and if it did not exist - create it
        if (empty($existed_env_content) || empty($app_key_pair['value'])) {
            EnvHandler::writeEnvFileContent($env_content);
            //generate keys to the created file
            $this->call('key:generate');
            //read the file for further processing
            $existed_env_content = EnvHandler::getEnvFileContent();
            // set APP_KEY variable from EXISTED env FILE
            $app_key_pair = EnvHandler::returnKeyAndValueFromDotEnvContent(
                $existed_env_content,
                'APP_KEY'
            );
        }

        //storage link
        if (! in_array('storage', scandir(base_path() . '/public'))) {
            $this->call('storage:link');
        }

        //Let's write the key to a variable (a string with data from a file .env.example)
        $env_content = EnvHandler::setEnvVariable(
            $env_content,
            $app_key_pair['key'],
            $app_key_pair['value']
        )['content'];

        //get the template data for installation
        $sections_info = config('envfilesectionsinfo');
        $db_type = null;
        $db_path = null;

        //Let's start the installation step by step from the template
        foreach ($sections_info as $section_info) {
            $this->info('Initiallize ' . strtolower($section_info['desc']));
            foreach ($section_info['content'] as $variable_info) {
                if (
                    $section_info['desc'] !== 'Database settings' &&
                    $section_info['desc'] !== 'Drivers mailer settings'
                ) {
                    $app_value = $this->getTheValueToWrite($variable_info);
                }

                if ($section_info['desc'] === 'Database settings') {
                    if ($variable_info['variable'] === 'DB_CONNECTION') {
                        $app_value = $this->getTheValueToWrite($variable_info);
                        $db_type = $app_value;
                    } else {
                        if ($db_type !== 'sqlite') {
                            if ($variable_info['type'] !== 'sqlite') {
                                $app_value = $this->getTheValueToWrite($variable_info);
                            }
                        } else {
                            if ($variable_info['visibility']) {
                                if (
                                    $variable_info['type'] !== 'sqlite' &&
                                    $variable_info['type'] !== 'core'
                                ) {
                                    $app_value = '';
                                } elseif (
                                    $variable_info['type'] === 'core' &&
                                    $variable_info['variable'] === 'DB_DATABASE'
                                ) {
                                    $app_value = DatabaseHandler::createSqliteDB($env_content);
                                    $db_path = $app_value;
                                } else {
                                    $app_value = $this->getTheValueToWrite($variable_info);
                                }
                            } else {
                                $app_value = $variable_info['default'];
                            }
                        }
                    }
                }

                if ($section_info['desc'] === 'Drivers mailer settings') {
                    if (EnvHandler::checkMailer($variable_info, $env_content)) {
                        continue;
                    }

                    if (
                        $variable_info['variable'] === 'MAIL_DRIVER' ||
                        $variable_info['variable'] === 'MAIL_MAILER'
                    ) {
                        $app_value = $variable_info['default'];
                    } else {
                        $app_value = $this->getTheValueToWrite($variable_info);
                    }
                }

                //for each iteration step, we write data to a temporary file
                $env_content = EnvHandler::setEnvVariable(
                    $env_content,
                    $variable_info['variable'],
                    $app_value
                )['content'];
            }

            $this->newLine();
        }

        //write the generated file as .env
        EnvHandler::writeEnvFileContent(
            EnvHandler::cleanFromEmptyString(
                $env_content
            )
        );

        //add an installation trigger file (indicating that the installation is complete)
        InstallHandler::addFileTregger($sections_info);

        //get data from our new .env
        $env_content = EnvHandler::getEnvFileContent();

        $this->call('config:cache');

        $this->newLine();
        $this->info('Initialization completed! For correct operation, it is recommended to update the composer.');
        $this->info('You can execute it now, or whenever it is convenient for you, using the command composer:update.');

        $app_value = $this->questionAnswers();

        if (strtolower($app_value) == 'yes') {
            //run the composer update
            exec('COMPOSER_MEMORY_LIMIT=-1 composer update');
            $this->call('config:cache');
            $this->info('The composer has been updated!');
        }

        $this->newLine();
        $this->info('For correct operation, it is necessary to fill the database with initial data.');
        $this->info('You can execute it now, or whenever it is convenient for you, using the command php artisan fill:base.');

        $app_value = $this->questionAnswers();
        $error = null;

        if (strtolower($app_value) == 'yes') {
            try {
                $this->call('config:clear');
                $this->call('cache:clear');

                DatabaseHandler::setBaseSettings($db_type, $db_path ?? null, $env_content);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if (! $error) {
                \Log::info('NO ERROR');
                $this->call('fill:base');
                $this->info('Data entered into the database!');
                $this->info('Installation is complete!');
                return 0;
            } else {
                $this->info('Error: ' . $error);
                $this->newLine();
                $this->info('Failed to add initial data to the database!');
                $this->info('Try using the command php artisan fill:base');
                return 0;
            }
        }

        $this->info('Installation is complete!');

        $this->info('For correct operation, it is necessary to fill the database with initial data.');
        $this->info('Use php artisan fill:base command for this.');

        return 0;
    }

    /**
     * Question output function
     *
     * @return int.
     * */
    public function questionAnswers()
    {
        $app_value = $this->choice(
            'Execute now?',
            [
                0 => 'yes',
                1 => 'skip',
            ],
            0 ?? 0
        );
        $app_value = !empty($app_value) ? $app_value : 0;

        return $app_value;
    }

    /**
     * Returns the value to write to .env
     *
     * @param string $variable_info
     *
     * @return string $app_value
     * */
    public function getTheValueToWrite($variable_info)
    {
        $variable_info_string = 'Configuring ' . strtolower(
            $variable_info['description']
        );
        if (!empty($variable_info['choise'])) {
            $app_value = $this->choice(
                $variable_info_string,
                $variable_info['choise'],
                $variable_info['choise_default_index'] ?? 0
            );
        } else {
            $default_value_string = !empty($variable_info['default']) ?
                                    '(default - ' . $variable_info['default'] . ')' :
                                    '';

            $app_value = $this->ask(
                $variable_info_string . $default_value_string
            );

            $app_value = !empty($app_value) ?
                $app_value :
                ($variable_info['default'] ?? '');
        }

        return $app_value;
    }
}
