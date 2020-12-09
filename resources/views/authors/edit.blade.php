

@extends('layouts.master')
@section('content')
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            <h1><span class="badge badge-info">Edit Author</span></h1>
        </div>
        <br>

        @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{Session::get('success')}}
        </div>
        @endif
        
        <br>

        <form method="POST" action="{{route('authors.update', $author->id)}}">
            @csrf
            <div class="form-group">
                <label for="name">Author Name <span class="text-danger">*</span></label>
                <input type="text" value="{{$author->name}}" class="form-control col-sm-4" name="name" id="name" >
                @error('name')
                <small id="nameHelp" class="form-text text-danger">{{$message}}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
