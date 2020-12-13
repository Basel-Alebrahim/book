@extends('layouts.master')
@section('content')

<br>

@if (Session::has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{Session::get('success')}}
</div>
@endif

<br>

<h1><span class="badge badge-info">Books List</span></h1>
<div class="row mt-4">
    <div class="col-sm">
        <a target="_blank" href="{{route('books.authors.csv')}}" class="btn btn-success ">Export Books and Authors CSV</a>
    </div>
    <div class="col-sm"><a target="_blank" href="{{route('authors.csv')}}" class="btn btn-success ">Export Authors CSV</a></div>
    <div class="col-sm"><a target="_blank" href="{{route('books.csv')}}" class="btn btn-success ">Export Book CSV</a></div>
</div>
<div class="row mt-4 mb-4">
    <div class="col-sm"><a target="_blank" href="{{route('books.authors.xml')}}" class="btn btn-dark ">Export Books Authors XML</a></div>
    <div class="col-sm"><a target="_blank" href="{{route('authors.xml')}}" class="btn btn-dark ">Export Authors XML</a></div>
    <div class="col-sm"><a target="_blank" href="{{route('books.xml')}}" class="btn btn-dark ">Export Books XML</a></div>
</div>

<form  method="get" action="{{route('books.all')}}">
    <div class="form-group row">
        <label for="book_title" class="col-sm-2 col-form-label">Book Title</label>
        <div class="col-sm-2">
            <input type="search" class="form-control" id="book_title_search" name="book_title_search" value="{{ request()->input('book_title_search') }}">
        </div>

        <label for="author_name" class="col-sm-2 col-form-label">Author Name</label>
        <div class="col-sm-2">
            <input type="search" class="form-control" id="author_name_search" name="author_name_search" value="{{ request()->input('author_name_search') }}" >
        </div>

        <div class="col-sm-2">
            <input type="submit" class="form-control btn btn-primary" value="Search">
        </div>
    </div>
    <div class="form-group row">
        <div class="offset-sm-8 col-sm-2">
            @if (Request::query('book_title_search') || Request::query('author_name_search'))
                <a class="btn btn-success form-control" href="{{ route('books.all')}}">Clear</a>
            @endif
        </div>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th scope="col">
                <label for="">Book Title</label>
                @if (Request::query('sortByTitle') && Request::query('sortByTitle') =='asc')
                <a href="{{ route('books.all',['sortByTitle' => 'desc']) }}"><i class="fas fa-sort-down"></i></a>
                @elseif(Request::query('sortByTitle') && Request::query('sortByTitle') =='desc')
                <a href="{{ route('books.all',['sortByTitle' => 'asc']) }}"><i class="fas fa-sort-up"></i></a>
                @else
                <a href="{{ route('books.all',['sortByTitle' => 'asc']) }}"><i class="fas fa-sort"></i></a>
                @endif
            </th>
            <th scope="col">
                <label for="">Author Name</label>
                @if (Request::query('sortByAuthorName') && Request::query('sortByAuthorName') =='asc')
                <a href="{{ route('books.all',['sortByAuthorName' => 'desc']) }}"><i class="fas fa-sort-down"></i></a>
                @elseif(Request::query('sortByAuthorName') && Request::query('sortByAuthorName') =='desc')
                <a href="{{ route('books.all',['sortByAuthorName' => 'asc']) }}"><i class="fas fa-sort-up"></i></a>
                @else
                <a href="{{ route('books.all',['sortByAuthorName' => 'asc']) }}"><i class="fas fa-sort"></i></a>
                @endif
            </th>
            <th scope="col">
                <a href="{{url('books/create/')}}" class="btn btn-success ">Add New Book</a>
            </th>
        </tr>
    </thead>
    <tbody>
            @forelse ($books as $book)
            <tr>
                <td>{{$book->title}}</td>
                <td>{{$book->author->name}}</td>
                <td>
                    <a href="{{route('books.edit',$book->id)}}" class="btn btn-warning ">Edit</a>
                    <a href="{{route('books.delete', $book->id)}}" class="btn btn-danger "> Delete</a>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="4">No books found</td>
                </tr>
            @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-center">
    {!! $books->appends(\Request::except('page'))->render() !!}
</div>

@endsection
