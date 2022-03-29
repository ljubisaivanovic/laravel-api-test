<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\Client;
use Illuminate\Console\Command;

class CreateAuthor extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:author {email} {password} {first_name} {last_name} {birthday} {biography} {gender} {place_of_birth}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add new author.';

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
     * To create an author from cli run this command: komanda
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $client = $this->authorize($this->argument('email'), $this->argument('password'));

            $dataArray = $this->arguments();
            unset($dataArray['command']);


            $author = $client->call('authors', $dataArray, 'POST');
            if ($author) {
                echo 'Author: ' . $author['first_name'] . ' ' . $author['last_name'] . ' is created successfully! ' . PHP_EOL;
            }
        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }

    }

    /**
     * Generate and store token_key
     * @param string $email
     * @param string $password
     * @return Client
     * @throws \Exception
     */
    private function authorize(string $email, string $password)
    {
        $client = new Client();

        try {
            $client->setPassword($password);
            $client->setEmail($email);
            $clientResponse = $client->init();
            $client->setToken($clientResponse['token_key']);


        } catch (\Exception $e) {
            echo $e->getMessage().PHP_EOL;
        }
        return $client;
    }
}
