<?php

namespace App\Http\Controllers;

use App\Models\LibraryBook;
use App\Models\LibraryIssue;
use App\Models\LibraryMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LibraryController extends Controller
{
    public function bookIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $books = LibraryBook::where('school_id', $schoolId)->get();

        return Inertia::render('Accountant/Library/Books', ['books' => $books]);
    }

    public function storeBook(Request $request)
    {
        $request->validate(['title' => 'required|string', 'author' => 'required|string']);
        LibraryBook::create(array_merge($request->all(), [
            'school_id' => Auth::user()->getScopedSchoolId(),
        ]));

        return redirect()->back()->with('success', 'Book added to catalog.');
    }

    public function memberIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $members = LibraryMember::where('school_id', $schoolId)->with('user')->get();
        $availableUsers = User::where('school_id', $schoolId)
            ->whereDoesntHave('libraryMember')
            ->get(['id', 'name', 'email']);

        return Inertia::render('Accountant/Library/Members', [
            'members' => $members,
            'availableUsers' => $availableUsers,
        ]);
    }

    public function storeMember(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id', 'library_card_number' => 'required|unique:library_members,library_card_number']);
        LibraryMember::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'user_id' => $request->user_id,
            'library_card_number' => $request->library_card_number,
        ]);

        return redirect()->back()->with('success', 'Library membership activated.');
    }

    public function issueIndex()
    {
        $schoolId = Auth::user()->getScopedSchoolId();
        $issues = LibraryIssue::where('school_id', $schoolId)
            ->with(['book', 'member.user'])
            ->where('status', 'issued')
            ->get();

        return Inertia::render('Accountant/Library/Issue', [
            'issues' => $issues,
            'books' => LibraryBook::where('school_id', $schoolId)->where('quantity', '>', 0)->get(),
            'members' => LibraryMember::where('school_id', $schoolId)->with('user')->get(),
        ]);
    }

    public function storeIssue(Request $request)
    {
        $request->validate([
            'library_book_id' => 'required|exists:library_books,id',
            'library_member_id' => 'required|exists:library_members,id',
            'due_date' => 'required|date',
        ]);

        $book = LibraryBook::find($request->library_book_id);
        if ($book->quantity <= 0) {
            return redirect()->back()->withErrors(['library_book_id' => 'Book out of stock.']);
        }

        LibraryIssue::create([
            'school_id' => Auth::user()->getScopedSchoolId(),
            'library_book_id' => $request->library_book_id,
            'library_member_id' => $request->library_member_id,
            'issue_date' => now(),
            'due_date' => $request->due_date,
            'status' => 'issued',
        ]);

        $book->decrement('quantity');

        return redirect()->back()->with('success', 'Book issued successfully.');
    }

    public function returnBook(LibraryIssue $issue)
    {
        $issue->update([
            'return_date' => now(),
            'status' => 'returned',
        ]);

        $issue->book()->increment('quantity');

        return redirect()->back()->with('success', 'Book returned.');
    }
}
