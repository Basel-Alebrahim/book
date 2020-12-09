<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BookTest extends TestCase
{
    use DatabaseTransactions;

    private $book;

    public function setup(): void
    {
        parent::setup();
        $this->book = factory(Book::class)->make();
    }

    public function testBookTableHasValue()
    {
        $this->assertDatabaseHas('books', [
            'title' => 'Laravel 7',
        ]);
    }

    public function testBookHasTitleAttribute()
    {
        $book = Book::create(['title'=> 'Steve Jobs', 'author_id' => 1]);
        $this->assertEquals('Steve Jobs', $book->title);
        $this->assertEquals(1, $book->author_id);
    }

    public function testBookTitleNotEmpty()
    {
        $this->assertNotEmpty($this->book->title);
    }

    public function testBookTitleNotNull()
    {
        $this->assertNotNull($this->book->title);
    }

    public function testCreateBookPage()
    {
        $response = $this->get("/books/create");
        $response->assertStatus(200);
    }

    public function testEditAuthorPage()
    {
        $book = factory(Book::class)->create();

        $response = $this->get("/books/edit/".$book->id);
        $response->assertSee($book->title);
        $response->assertStatus(200);
    }

    public function testAddBookPage()
    {
        $book = factory(Book::class)->make();
        $data = [
            'title' => $book->title,
            'author_id' => $book->author_id,
        ];
        $this->post('/books/store', $data);
        $this->assertDatabaseHas('books',$data);
    }

    public function testUpdateAuthorPage()
    {
        $book = factory(Book::class)->create(['title' => 'Software Engineering', 'author_id' => 1]);
        $otherBook = factory(Book::class)->create(['title' => 'Software Engineering', 'author_id' => 1]);

        $update = [
            'title' => 'Database Management',
            'author_id' => 2
        ];

        $this->post(route('books.update',['book_id' => $book->id,'book' => $book]), $update);

        $book->refresh();
        $otherBook->refresh();

        $this->assertEquals('Database Management', $book->title);
        $this->assertEquals(2, $book->author_id);
        $this->assertEquals('Software Engineering', $otherBook->title);
        $this->assertEquals(1, $otherBook->author_id);
    }

    public function testDeleteBook()
    {
        $book = factory(Book::class)->create();
        $this->get('/books/delete/'.$book->id);
        $this->assertDeleted($book);
        $this->assertDatabaseMissing('books',['id' => $book->id]);
    }
}
