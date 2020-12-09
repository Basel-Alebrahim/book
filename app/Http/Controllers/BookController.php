<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request) {
        $query = Book::with('author');

        //Search by book title
        if ($request->book_title_search && ($request->book_title_search != null)) {
            $query->where(function($q) use ($request){
                $q->where('title',  'like', '%'.$request->book_title_search.'%');});
        }

        //Search by author name
        if ($request->author_name_search && ($request->author_name_search != null)) {
            $query->whereHas('author', function($q) use ($request){
                $q->where('name',  'like', '%'.$request->author_name_search.'%');});
        }

        // sortByTilte ASC or DESC
        if ($request->has('sortByTitle') && ($request->sortByTitle != null)) {
            $query->orderBy('title', $request->sortByTitle);
        }
        // sortByAuthorName ASC or DESC
        if ($request->has('sortByAuthorName') && ($request->sortByAuthorName != null)) {
            $query = Book::join('authors','authors.id','=','books.author_id')
                    ->select('authors.*','books.*')
                    ->orderBy('authors.name', $request->sortByAuthorName);
        }
        $books = $query->paginate(5);
        return  view('books.index', compact('books'))->withInput($request->input());
    }

    public function createBook() {
        $authors = Author::select('id', 'name')->get();
        return view('books.create', compact('authors'));
    }

    public function storeBook(Request $request) {
        $this->validate($request,[
            'title' => 'required|max:100',
            'author_id' => 'required',
        ]);

        Book::create([
            'title'     => $request->title,
            'author_id' => $request->author_id,
            ]);

            return redirect()
            ->route('books.all')
            ->with(['success' => 'Book has been added successfully']);
    }

    public function editBook($author_id) {

        $book = Book::with('author')->find($author_id);
        if (!$book) {
            return redirect()->back();
        }

        $authors = Author::select('id', 'name')->get();

        return view('books.edit', compact('book', 'authors'));
    }

    public function UpdateBook(Request $request, $book_id) {
        $this->validate($request,[
            'title' => 'required|max:100',
            'author_id' => 'required',
        ]);

        $book = Book::with('author')->find($book_id);

        if (!$book) {
            return redirect()->back();
        }
        $book->update($request->all());

        return redirect()
        ->route('books.all')
        ->with(['success' => 'Book has been updated successfully']);
    }

    public function deleteBook($book_id)
    {
        $book = Book::find($book_id);

        if (!$book)
            return redirect()->back()->with(['error' => 'Book is not exist']);
        $book->delete();

        return redirect()
        ->route('books.all')
        ->with(['success' => 'Book has been deleted successfully']);
    }

    public function exportBooksWithAuthorstoCSV()
    {
        $fileName = 'books_authors.csv';
        $books = Book::with('author')->get();

            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('Author Name', 'Book Title');

            $callback = function() use($books, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($books as $book) {
                    $row['Author Name']  = $book->author->name;
                    $row['Book Title']    = $book->title;
                    fputcsv($file, array($row['Author Name'], $row['Book Title']));
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
    }

    public function exportBookstoCSV()
    {
        $fileName = 'books.csv';
        $books = Book::select('title')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Book Title');

        $callback = function() use($books, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($books as $book) {
                $row['Book Title']    = $book->title;
                fputcsv($file, array($row['Book Title']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportAuthorstoCSV()
    {
        $fileName = 'authors.csv';
        $authors = Author::select('name')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Author Name');

        $callback = function() use($authors, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($authors as $author) {
                $row['Author Name']    = $author->name;
                fputcsv($file, array($row['Author Name']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportAuthorstoXML()
    {
        $authors = Author::select('name')->get();
        $dom = new \DOMDocument('1.0','UTF-8');
        header('Content-Disposition: attachment;filename=authors.xml');
        $dom->formatOutput = true;

        $root = $dom->createElement('authors');
        $dom->appendChild($root);

        foreach($authors AS $auth){
            $author = $dom->createElement('author');
            $root->appendChild($author);
            $author->appendChild( $dom->createElement('name',  $auth->name) );
        }

        echo '<xmp>'. $dom->saveXML() .'</xmp>';
        $dom->save('authors.xml') or die('XML Create Error');

    }

    public function exportBookstoXML()
    {
        $books = Book::select('title')->get();
        $dom = new \DOMDocument('1.0','UTF-8');
        header('Content-Disposition: attachment;filename=books.xml');
        $dom->formatOutput = true;

        $root = $dom->createElement('books');
        $dom->appendChild($root);

        foreach($books AS $b){
            $book = $dom->createElement('book');
            $root->appendChild($book);
            $book->appendChild( $dom->createElement('title',  $b->title) );
        }

        echo '<xmp>'. $dom->saveXML() .'</xmp>';
        $dom->save('books.xml') or die('XML Create Error');

    }

    public function exportBooksWithAuthorstoXML()
    {
        $books = Book::with('author')->get();
        $dom = new \DOMDocument('1.0','UTF-8');
        header('Content-Disposition: attachment;filename=books_authors.xml');
        $dom->formatOutput = true;

        $root = $dom->createElement('books');
        $dom->appendChild($root);

        foreach($books AS $b){
            $book = $dom->createElement('book');
            $root->appendChild($book);
            $book->appendChild( $dom->createElement('title',  $b->title) );
            $book->appendChild( $dom->createElement('author',  $b->author->name) );
        }

        echo '<xmp>'. $dom->saveXML() .'</xmp>';
        $dom->save('books_authors.xml') or die('XML Create Error');

    }
}
