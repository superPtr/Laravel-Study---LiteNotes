<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $userId = Auth::user()->id;
        // $notes = Note::where('user_id', $userId) ->get();
        //$notes = Note::where('user_id', Auth::id())->latest('updated_at')->paginate(5);
        // $notes = Auth::user()->notes()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);
        return view('notes.index')->with('notes', $notes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'title'=>'required|max:120',
            'text'=>'required'
        ]);

        // $note = new Note([
        //     'user_id' => Auth::id(),
        //     'title' => $request->title,
        //     'text' => $request->text
        // ]);
        // $note->save();

        Auth::user()->notes()->create([
            'uuid' => Str::uuid(),
            //'user_id' => Auth::id(),
            'title' => $request->title,
            'text' => $request->text
        ]);
        return to_route('notes.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        // $note = Note::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();
        // if($note->user_id != Auth::id()) {
        //     return abort(403);
        // }
        // another way to do it (compare the primary key of 2 objects belonging to the same model class)
        if(!$note->user->is(Auth::user())){  
            return abort(403);
        }
        // is function in here used to do the comparison
        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    {
        if(!$note->user->is(Auth::user())){  
            return abort(403);
        }
        return view('notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        if(!$note->user->is(Auth::user())){  
            return abort(403);
        }

        $request->validate([
            'title'=>'required|max:120',
            'text'=>'required'
        ]);

        $note->update([
            'title' => $request->title,
            'text' => $request->text
        ]);

        return to_route('notes.show', $note)->with('success', 'Note updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if(!$note->user->is(Auth::user())){  
            return abort(403);
        }
        
        $note->delete();
        return to_route('notes.index')->with('success', 'Note moved to trash');
    }
}
