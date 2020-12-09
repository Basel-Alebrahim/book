<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function index(Request $request) {
        $query = Author::with('books');
        // sortByAuthorName ASC or DESC
        if ($request->has('sortByAuthorName') && ($request->sortByAuthorName != null)) {
            $query->orderBy('name', $request->sortByAuthorName);
        }
        $authors = $query->paginate(5);
        return view('authors.index', compact('authors'));
    }

    public function createAuthor() {
        return view('authors.create');
    }

    public function storeAuthor(Request $request) {
        $this->validate($request,[
            'name' => 'required|max:100|unique:authors,name',
        ]);

        Author::create([
            'name' => $request->name,
            ]);

            return redirect()
            ->route('authors.all')
            ->with(['success' => 'Author has been added successfully']);
    }

    public function editAuthor($author_id) {
        $author = Author::find($author_id);
        if (!$author) {
            return redirect()->back();
        }

        $author = Author::select('id','name')->find($author_id);
        return view('authors.edit',compact('author'));
    }

    public function UpdateAuthor(Request  $request, $author_id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:authors,name,'.$author_id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInputs($request->all());
        }

        $author = Author::find($author_id);
        if (!$author) {
            return redirect()->back();
        }

        $author->update([
            'name' => $request->name,
        ]);
        return redirect()
            ->route('authors.all')
            ->with(['success' => 'Author has been updated successfully']);
    }

    public function deleteAuthor($author_id) {
        $author = Author::find($author_id);

        if (!$author)
            return redirect()->back()->with(['error' => 'Author is not exist']);
        $author->books()->delete();
        $author->delete();

        return redirect()
            ->route('authors.all')
            ->with(['success' => 'Author and his books has been deleted successfully']);
    }
}
