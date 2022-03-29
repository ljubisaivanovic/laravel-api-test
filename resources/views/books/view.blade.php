@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row styled-table p-5 bg-white">
            <div class="col-md-12">
                <div class="col-md-12 pb-5">
                    <b>Title: </b>  {{$data['title']}}<br>
                    <b>Release date: </b>  {{date('d M, Y', strtotime($data['release_date']))}} <br>
                    <b>Description: </b>  {{strlen($data['description']) <= 150 ? $data['description'] : substr($data['description'], 0, 50) .'...'}} <br>
                    <b>Isbn: </b>  <small>{{$data['isbn']}}</small><br>
                    <b>Format: </b>  {{$data['format']}} <br>
                    <b>Number of pages: </b>  {{$data['number_of_pages']}} <br>
                </div>
            </div>
        </div>
    </div>

@endsection
