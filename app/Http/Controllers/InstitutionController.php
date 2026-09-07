<?php

namespace App\Http\Controllers;
use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
   public function index()
   {
      $institutions = Institution::all();
      return view('institutions.index', compact('institutions'));
   }

   public function create()
   {
      return view('institutions.create');

   }

   public function store(Request $request)
   {
      $request->validate([
         'name' => 'required|unique:institutions,name',
         'address' => 'required',
         'contact_person' => 'required',
         'contact_number' => 'required|max:13',
      ]);
      $institution = new Institution();
      $institution->name = $request->name;
      $institution->address = $request->address;
      $institution->contact_person = $request->contact_person;
      $institution->contact_phone = $request->contact_number;
      $institution->save();
      return redirect()->route('institutions.index');
   }

   public function update(Request $request)
   {
      $request->validate([
         'name' => 'required|unique:institutions,name,'.$request->id,
         'address' => 'required',
         'contact_person' => 'required',
         'contact_number' => 'required|max:13',
      ]);
      $institution = Institution::find($request->id);
      $institution->name = $request->name;
      $institution->address = $request->address;
      $institution->contact_person = $request->contact_person;
      $institution->contact_phone = $request->contact_number;
      $institution->save();
      return redirect()->route('institutions.index');
   }
   public function delete(Request $request)
   {
      $institution = Institution::find($request->id);
      $institution->delete();
      return redirect()->route('institutions.index');
   }

   public function show(Institution $institution)
   {
      return view('institutions.show', compact('institution'));
   }

}

