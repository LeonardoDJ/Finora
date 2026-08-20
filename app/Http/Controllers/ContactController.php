<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Contact;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(){
        $contacts = Contact::where('user_id', Auth::id())->get();

        return Inertia::render("Contacts/Index", [
            "contacts" => $contacts,
            "deleteError" => session('delete_error'),
            "duplicateError" => session('duplicate_error'),
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'email'=> 'required|email|max:255',
            'phone'=> 'required|string|max:20',
            'type'=> 'required|in:cliente,fornecedor',
        ]);

        $existingContact = Contact::where('user_id', Auth::id())
            ->where(function ($query) use ($request) {
                $query->where('email', $request->input('email'))
                    ->orWhere('phone', $request->input('phone'));
            })
            ->exists();

        if ($existingContact) {
            return redirect('/contatos')->with(
                'duplicate_error',
                'Já existe um contato cadastrado com este e-mail ou telefone.'
            );
        }

        Contact::create([
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

        $hasTransactions = Transaction::where('contact_id', $contact->id)
            ->exists();

        if ($hasTransactions) {
            return redirect('/contatos')->with(
                'delete_error',
                'Este contato não pode ser excluído porque possui lançamentos vinculados.'
            );
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

        $existingContact = Contact::where('user_id', Auth::id())
            ->where('id', '!=', $contact->id)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->input('email'))
                    ->orWhere('phone', $request->input('phone'));
            })
            ->exists();

        if ($existingContact) {
            return redirect('/contatos')->with(
                'duplicate_error',
                'Já existe outro contato cadastrado com este e-mail ou telefone.'
            );
        }

        $contact->update([
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'type' => $request->input('type'),
        ]);

        return redirect('/contatos');
    }
}
