<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\Client;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function index()
    {
        $data = $this->client->call('books');

        return view('books/index', ['data' => $data]);
    }

    public function view($id)
    {
        $data = $this->client->call('books/' . $id);

        return view('books/view', ['data' => $data]);
    }

    public function delete($id)
    {
        $this->client->call('books/' . $id, [], 'DELETE');

        return redirect()->route('books')->with('msg', 'You are successfully delete book!');
    }

    public function create()
    {
        $data = $this->client->call('authors');

        return view('books/form', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $data = [
            'author' => [
                'id' => $request->input('author')
            ],
            'title' => $request->input('title'),
            'release_date' => $request->input('date'),
            'description' => $request->input('description'),
            'isbn' => $request->input('isbn'),
            'format' => $request->input('format'),
            'number_of_pages' => (int)$request->input('pages')
        ];

        $book = $this->client->call('books', $data, 'POST');

        return view('books/view', ['data' => $book]);
    }

}
