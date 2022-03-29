@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row styled-table p-5 bg-white">
            <div class="col-md-12 pb-5">
                <b>First Name: </b>  {{$data['first_name']}} <br>
                <b>Last Name: </b>  {{$data['last_name']}} <br>
                <b>Birthday: </b>  {{date('d M, Y',strtotime($data['birthday']))}} <br>
                <b>Biography: </b>  <small>{{$data['biography']}} </small><br>
                <b>Gender: </b>  {{$data['gender']}} <br>
                <b>Place of birth: </b>  {{$data['place_of_birth']}} <br>

            </div>
            <div class="col-md-12">
                <table class="table text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th>Title</th>
                        <th>Release date</th>
                        <th>Description</th>
                        <th>Isbn</th>
                        <th>Format</th>
                        <th>Number of pages</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['books'] as $book)
                        <tr>
                            <td>{{$book['title']}}</td>
                            <td>{{date('d M, Y', strtotime($book['release_date']))}}</td>
                            <td><small>{{strlen($book['description']) <= 150 ? $book['description'] : substr($book['description'], 0, 50) .'...'}}</small></td>
                            <td>{{$book['isbn']}}</td>
                            <td>{{$book['format']}}</td>
                            <td>{{$book['number_of_pages']}}</td>
                            <td>
                                <a href="{{route('book.delete', ['id'=>$book['id']])}}">Delete</a>
                               </td>

                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

@endsection
