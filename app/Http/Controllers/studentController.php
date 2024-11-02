<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;

class studentController extends Controller
{
    public function index()
    {
        $students = Students::all();
        return view('home')->with('students', $students);
    }

    public function students()
    {
        $students = Students::all(); // Fetch all students
        return response()->json($students); // Return JSON response
    }

    public function student($id)
    {
        $student = Students::findOrFail($id);
        return response()->json($student); // Return JSON response
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $requestVal = $request->validate([
            'index' => 'required|unique:students,index',
            'name' => 'required',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|unique:students,phone',
            'address' => 'required',
        ]);

        // Create a new student instance and save it to the database
        $student = new Students();
        $student->index = $requestVal['index'];
        $student->email = $requestVal['email'];
        $student->name = $requestVal['name'];
        $student->phone = $requestVal['phone'];
        $student->address = $requestVal['address'];
        $student->save();

        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Student added successfully'
        ]);
    }

    public function show($id)
    {
        $student = Students::findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {

        $student = Students::findOrFail($id);

        // Validation (add any specific rules you need here)
        $request->validate([
            'index' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string'
        ]);

        // Update the student record
        $student->index = $request->input('index');
        $student->name = $request->input('name');
        $student->email = $request->input('email');
        $student->phone = $request->input('phone');
        $student->address = $request->input('address');
        $student->save();

        return response()->json(['status' => 'success', 'message' => 'Student updated successfully.']);

    }



    public function destroy($id)
    {
        $student = Students::findOrFail($id);
        $student->delete();
        return response()->json(['status' => 'success', 'message' => 'Student deleted successfully']);
    }

}
