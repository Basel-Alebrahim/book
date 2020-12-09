<?php

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Book::create([
            'title'     => 'Laravel 7',
            'author_id' => 1,
        ]);

        factory(Book::class, 20)->create();
    }
}
