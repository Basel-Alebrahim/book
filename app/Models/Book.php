<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = ['title','author_id'];
    public $timestamps = false;

    public function author(){
        return $this -> belongsTo(Author::class,'author_id','id');
    }
}
