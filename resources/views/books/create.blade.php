@extends('layouts.master')
@section('content')
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            <h1><span class="badge badge-info">Add New Book</span></h1>
        </div>

        <br>
        @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{Session::get('success')}}
        </div>
        @endif

        <br>

    <form method="POST" action="{{route('books.store')}}">
        @csrf
        <div class="form-group">
            <label for="name">Book Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control col-sm-4" name="title" id="title" value="{{old('title')}}">
            @error('title')
            <small id="nameHelp" class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Select Author <span class="text-danger">*</span></label>
            <select class="form-control col-sm-4" name="author_id" id="author_id">
                <option value="" selected>-- Select Author --</option>
                @foreach($authors as $author)
                <option  {{ old('author_id') == $author->id ? 'selected="selected"' : '' }} value="{{ $author->id }}">{{$author->name}}</option>
                @endforeach
            </select>
            @error('author_id')
            <small id="nameHelp" class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
    </div>
</div>
@endsection
