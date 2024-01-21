<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormController extends Controller
{
    public function showForm()
    {
        return view('form');
    }

    public function submitForm(Request $request)
    {
        // Validate form data
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:30',
            'lastName' => 'required|string|max:30',
            'email' => 'required|email',
            'phone-number'=> 'required|string|max:10',
            'summary' => 'required|string|max:255',
            'title' => 'required|string|max:50',

        ]);

        /*ssuming the form submission is successful
        // You can add your logic for saving data to the database here
        $yourModel = new ();
        $yourModel->field1 = $validatedData['field1'];
        $yourModel->field2 = $validatedData['field2'];
        // Set other fields accordingly

        $yourModel->save();*/

        return redirect('/joinusnow.blade.php')->with('success', 'Content submitted successfully!');
    }
}
