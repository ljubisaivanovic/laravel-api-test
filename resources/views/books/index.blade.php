@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session()->has('msg'))
            <div class="alert alert-primary" role="alert">
                {{session()->get('msg')}}
            </div>
        @endif
        <div class="row styled-table p-5 bg-white">
            <div class="col-md-10 text-left">
                <h3>Book list</h3>
            </div>
            <div class="col-md-2 text-md-end">
                <a href="{{route('book.create')}}" class="btn btn-outline-dark btn-sm">Create book</a>
            </div>
            <div class="col-md-12">
                <table class="table text-center">
                    <thead class="thead-light">
                    <tr>
                        <th>Title</th>
                        <th>Release date</th>
                        <th>Isbn</th>
                        <th>Format</th>
                        <th>Number of pages</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['items'] as $book)
                        <tr>
                            <td>{{$book['title']}}</td>
                            <td>{{date('d M, Y', strtotime($book['release_date']))}}</td>
                            <td>{{$book['isbn']}}</td>
                            <td>{{$book['format']}}</td>
                            <td>{{$book['number_of_pages']}}</td>
                            <td>
                                <a href="{{route('book.view', ['id'=>$book['id']])}}">View</a>
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
