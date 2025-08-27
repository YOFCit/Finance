<?php

namespace App\Http\Controllers;

use App\Models\ApprovalPerson;
use Illuminate\Http\Request;

class ApprovalPersonController extends Controller
{
  /**
   * Display the modal view with the current ApprovalPerson record (if exists).
   * This ensures that only one record is ever managed.
   */
  public function showModal()
  {
    $approvalPerson = ApprovalPerson::first();
    return view('approval_person.modal', compact('approvalPerson'));
  }

  /**
   * Store a new ApprovalPerson record.
   * Validation ensures proper email and name format.
   * If a record already exists, we prevent creating another one.
   */
  public function store(Request $request)
  {
    // Check if a record already exists
    if (ApprovalPerson::exists()) {
      // Prevent duplicate record since there must only be one
      return back()->with('error', 'An approver already exists. You can only edit it.');
    }
    $request->validate([
      'approved_by' => 'required|string|max:255',
      'mail' => 'required|email',
    ]);
    ApprovalPerson::create($request->only('approved_by', 'mail'));
    return back()->with('success', 'Approver created successfully.');
  }

  /**
   * Update the existing ApprovalPerson record.
   * Ensures input validation and updates the single approver data.
   */
  public function update(Request $request, $id)
  {
    $approvalPerson = ApprovalPerson::findOrFail($id);
    $request->validate([
      'approved_by' => 'required|string|max:255',
      'mail' => 'required|email',
    ]);
    $approvalPerson->update($request->only('approved_by', 'mail'));
    return back()->with('success', 'Approver updated successfully.');
  }
}
