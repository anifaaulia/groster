<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();

        return view('facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('facilities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:facilities,name',
        ]);

        $facility = new Facility();
        $facility->name = $request->name;
        $facility->save();

        return redirect()->route('facilities.index');
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        return view('facilities.edit', compact('facility'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:facilities,name,' . $id,
        ]);

        $facility = Facility::findOrFail($id);
        $facility->name = $request->name;
        $facility->save();

        return redirect()->route('facilities.index');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return redirect()->route('facilities.index');
    }

    public function show($id)
    {
        $facility = Facility::findOrFail($id);
        return view('facilities.show', compact('facility'));
    }
}