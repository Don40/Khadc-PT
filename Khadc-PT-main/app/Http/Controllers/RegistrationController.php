<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Registration; // Make sure this matches your model


class RegistrationController extends Controller
{
    public function showForm()
    {
        return view('register');
    }

   public function submitForm(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:255'],
        'mobile' => ['required', 'regex:/^[0-9]{10,15}$/'],
        'email' => ['required', 'email', 'max:255'],
        'file' => ['required', 'file', 'max:2048'],
        'monthly_salary' => ['required', 'numeric'],
    ]);

    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('uploads', 'public');

        $tenPercent = $validated['monthly_salary'] * 0.10;

        Registration::create([
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'file_path' => $path,
            'monthly_salary' => $validated['monthly_salary'],
            'ten_percent' => $tenPercent,
            'user_id' => auth()->id(), // Assuming user is authenticated
        ]);

        return redirect()->route('register.success')->with('success', 'Saved');
    }
    
        return back()->withErrors(['file' => 'File upload failed']);
    }
    
    public function showAll(Request $request)
{
    $search = $request->input('search');

    $query = \App\Models\Registration::query();

    if ($search) {
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%");
    }

    $registrations = $query->latest()->paginate(10); // 10 per page

    return view('registrations', compact('registrations', 'search'));
}

        public function delete($id)
        {
            $record = \App\Models\Registration::findOrFail($id);

            // Optionally delete the file from storage
            if ($record->file_path) {
                Storage::disk('public')->delete($record->file_path);
            }

            $record->delete();

            return redirect()->route('registrations.viewown')->with('success', 'Registration deleted successfully.');
        }
           
        // public function viewOnly(Request $request)
        // {
        //     $search = $request->input('search');
        
        //     $registrations = Registration::when($search, function ($query, $search) {
        //             return $query->where('name', 'like', "%$search%")
        //                          ->orWhere('mobile', 'like', "%$search%");
        //         })
        //         ->orderByDesc('created_at')
        //         ->paginate(10);
        
        //     return view('registrations_without_action', compact('registrations', 'search'));
        // }

                      
        public function viewOwn(Request $request)
        {
            $search = $request->input('search');
            $userId = auth()->id();

            $registrations = Registration::where('user_id', $userId)
                ->when($search, function ($query, $search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('mobile', 'like', "%$search%");
                })
                ->orderByDesc('created_at')
                ->paginate(10);

            return view('registrations-viewown', compact('registrations', 'search'));
            }
        }
