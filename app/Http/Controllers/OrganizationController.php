<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function edit()
    {
        $org = Organization::first();

        return view('pages.organization.edit', compact('org'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'url' => 'required',
            'api_key' => 'required'
        ]);

        $org = Organization::first();

        $org->update($validatedData);

        return redirect()->back()->with('success', 'Organization Updated Successfully');
    }
}
