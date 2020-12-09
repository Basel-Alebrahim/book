<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use DatabaseTransactions;

    private $author;

    public function setup(): void
    {
        parent::setup();
        $this->author = factory(Author::class)->make();
    }

    public function testAuthorTableHasValue()
    {
        $this->assertDatabaseHas('authors', [
            'name' => 'Basel',
        ]);
    }

    public function testAuthorHasNameAttribute()
    {
        $author = Author::create(['name'=> 'Steve Jobs']);
        $this->assertEquals('Steve Jobs', $author->name);
    }

    public function testAuthorNameNotEmpty()
    {
        $this->assertNotEmpty($this->author->name);
    }

    public function testAuthorNameNotNull()
    {
        $this->assertNotNull($this->author->name);
    }

    public function testUserVisitLandingPage()
    {
        $response = $this->get("/");
        $response->assertStatus(200);
    }

    public function testCreateAuthorPage()
    {
        $response = $this->get("/authors/create");
        $response->assertStatus(200);
    }

    public function testEditAuthorsPage()
    {
        $author = factory(Author::class)->create();

        $response = $this->get("/authors/edit/".$author->id);
        $response->assertSee($author->name);
        $response->assertStatus(200);
    }

    public function testAddAuthorPage()
    {
        $author = factory(Author::class)->make();
        $data = [
            'name' => $author->name,
        ];
        $this->post('/authors/store', $data);
        $this->assertDatabaseHas('authors',$data);
    }

    public function testUpdateAuthor()
    {
        $author = factory(Author::class)->create(['name' => 'Bill Gates']);
        $otherAuthor = factory(Author::class)->create(['name' => 'Bill Gates']);

        $update = [
            'name' => 'Mark Zuckerberg'
        ];

        $this->post(route('authors.update',['author_id' => $author->id,'author' => $author]), $update);

        $author->refresh();
        $otherAuthor->refresh();

        $this->assertEquals('Mark Zuckerberg', $author->name);
        $this->assertEquals('Bill Gates', $otherAuthor->name);
    }

    public function testDeleteAuthor()
    {
        $author = factory(Author::class)->create();
        $this->get('/authors/delete/'.$author->id);
        $this->assertDeleted($author);
        $this->assertDatabaseMissing('authors',['id' => $author->id]);
    }
}
