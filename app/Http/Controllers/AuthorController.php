<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Client;


class AuthorController extends Controller
{
    public $client;

    public function __construct()
    {
       $this->client = new Client();
    }

    public function index()
    {
        $data = $this->client->call('authors');

        return view('authors/index', ['data' => $data]);
    }

    public function view($id)
    {
        $data = $this->client->call('authors/'.$id);

        return view('authors/view', ['data' => $data]);
    }

    public function delete($id)
    {
        $authorData = $this->client->call('authors/'.$id);

        if(!empty($authorData['books'])) {
            return redirect()->route('authors')->with('msg', 'You cannot delete an author who has books!');
        } else {
            $this->client->call('authors/' . $id, [], 'DELETE');
            return redirect()->route('authors')->with('msg', 'You are successfully delete author!');
        }
    }
}
