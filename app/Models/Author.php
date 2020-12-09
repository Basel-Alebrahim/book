<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Book;

class Author extends Model
{
    protected $table = 'authors';

    protected $fillable = ['name'];
    public $timestamps = false;

    public function books(){
        return $this -> hasMany(Book::class,'author_id','id');
    }
}
