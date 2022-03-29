@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session()->has('msg'))
            <div class="alert alert-primary" role="alert">
                {{session()->get('msg')}}
            </div>
        @endif
        <div class="row styled-table p-5 bg-white">
            <h3>Author list</h3>
            <div class="col-md-12">
                <table class="table text-center">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Birthday</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Place of Birth</th>
                        <th scope="col">Actions</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data['items'] as $author)
                        <tr>
                            <td>{{$author['first_name']}}</td>
                            <td>{{$author['last_name']}}</td>
                            <td>{{date('d M, Y', strtotime($author['birthday']))}}</td>
                            <td>{{$author['gender']}}</td>
                            <td>{{$author['place_of_birth']}}</td>
                            <td>
                                <a href="{{route('author.view', ['id' => $author['id']])}}">View</a>
                                <a href="{{route('author.delete', ['id' => $author['id']])}}">Delete</a>

                            </td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
