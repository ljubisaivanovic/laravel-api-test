@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row p-5 bg-white" >
            <form method="post" action="{{ route('book.store') }}">
                @csrf
                <div class="form-group row pb-3">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Title">
                    </div>
                </div>
                <div class="form-group row pb-3">
                    <label for="author" class="col-sm-2 col-form-label">Author</label>
                    <div class="col-sm-10">
                        <select id="author" class="form-control" name="author">
                            <option selected>--Choose author--</option>
                            @foreach($data['items'] as $author)
                                <option
                                    value="{{$author['id']}}">{{$author['first_name']}} {{$author['last_name']}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row pb-3">
                    <label for="date" class="col-sm-2 col-form-label">Release date</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control"  name="date"  id="date" placeholder="Release date">
                    </div>
                </div>
                <div class="form-group row pb-3">
                    <label for="isbn" class="col-sm-2 col-form-label">ISBN</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="isbn" id="isbn" placeholder="isbn">
                    </div>
                </div>
                <div class="form-group row pb-3">
                    <label for="format" class="col-sm-2 col-form-label">Format</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="format" id="format" placeholder="format">
                    </div>
                </div>
                <div class="form-group row pb-3">
                    <label for="pages" class="col-sm-2 col-form-label">number of pages</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" name="pages" id="pages">
                    </div>
                </div>
                <div class="form-group row pb-3">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <textarea name="description" id="description" rows="5" style="width: 100%"></textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
