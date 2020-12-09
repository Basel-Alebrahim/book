@extends('layouts.master')
@section('content')
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            <h1><span class="badge badge-info">Add New Author</span></h1>
        </div>

        <br>

        @if (Session::has('success'))
        <div class="alert alert-success" role="alert">
        {{Session::get('success')}}
        </div>
        @endif
        
        <br>

        <form method="POST" action="{{route('authors.store')}}">
            @csrf
            <div class="form-group">
                <label for="name">Author Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control col-sm-4" name="name" id="name" value="{{old('name')}}">
                @error('name')
                <small id="nameHelp" class="form-text text-danger">{{$message}}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
@endsection

