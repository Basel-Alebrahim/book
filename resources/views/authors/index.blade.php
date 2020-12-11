@extends('layouts.master')
@section('content')


<style>
    .card-header .fa {
        transition: .3s transform ease-in-out;
    }
    .card-header .collapsed .fa {
        transform: rotate(90deg);
    }
</style>


    <h1><span class="badge badge-info">Authors List</span></h1>
    <br>

    @if (Session::has('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        {{Session::get('success')}}
    </div>
    @endif

    <br>
    <div class="alert alert-dark d-flex justify-content-between align-items-center mb-0" role="alert">
        <div class="col-sm-4 pl-5">
            <label for="" class="alert-link">Author Name</label>
                @if (Request::query('sortByAuthorName') && Request::query('sortByAuthorName') =='asc')
                <a href="{{ route('authors.all',['sortByAuthorName' => 'desc']) }}"><i class="fas fa-sort-down"></i></a>
                @elseif(Request::query('sortByAuthorName') && Request::query('sortByAuthorName') =='desc')
                <a href="{{ route('authors.all',['sortByAuthorName' => 'asc']) }}"><i class="fas fa-sort-up"></i></a>
                @else
                <a href="{{ route('authors.all',['sortByAuthorName' => 'asc']) }}"><i class="fas fa-sort"></i></a>
                @endif
        </div>
        <div class="col-md-offset-4 ccol-sm-4">
            <a href="{{url('authors/create/')}}" class="btn btn-success ">Add New Author</a>
        </div>
    </div>
    <div class="accordion" id="accordionExample">
        @forelse ($authors as $author)
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">

                    <div class="col-sm-9">
                        <button type="button" class="btn " data-toggle="collapse" data-target="#_{{$author->id}}">
                            <i class="fa fa-angle-right"></i> {{$author->name}}</button>
                    </div>
                    <div class="col-sm-3">
                        <a href="{{url('authors/edit/'.$author->id)}}" class="btn btn-warning d-inline-block mr-4">Edit</a>
                        <a href="{{route('authors.delete', $author->id)}}" class="btn btn-danger d-inline-block mr-4">Delete</a>
                    </div>
                </div>
            </div>
            <div id="_{{$author->id}}" class="panel-collapse collapse"  data-parent="#accordionExample">
                <div class="card-body">
                    <div class="panel-title pl-3 alert alert-dark">Book Title</div>
                    <ul class="list-group">
                        @forelse ($author->books as $book)
                            <li class="list-group-item">{{$book->title}}</li>
                        @empty
                            <p class="pl-3 alert alert-warning">No books found</p>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
        @empty
            <p colspan="4">No authors found</p>
        @endforelse

        <div class="d-flex justify-content-center mt-5">
            {!!  $authors -> links() !!}
        </div>
    </div>
@endsection
