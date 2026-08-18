<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contact;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(){

        $contacts = Contact::where('user_id', Auth::id())->get();

        return Inertia::render("Contacts/Index", [
            "contacts" => $contacts
        ]);

    }

    public function store(Request $request){
        $request->validate([
            'email'=> 'required|email|max:255',
            'phone'=> 'required|string|max:20',
            'type'=> 'required|in:cliente,fornecedor',
        ]);
        $contact = Contact::create([
            'user_id'=>Auth::id(),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'type' => $request->input('type'),
        ]);

        return redirect('/contatos');
    }

    public function destroy(Contact $contact){
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        $contact->delete();

        return redirect('/contatos');
    }

    public function update(Request $request, Contact $contact){

        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|in:cliente,fornecedor',
        ]);

        $contact->update([
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'type' => $request->input('type'),
        ]);

        return redirect('/contatos');
    }
}
