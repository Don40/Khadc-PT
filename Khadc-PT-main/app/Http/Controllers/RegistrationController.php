<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Models\Registration;


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
        'income_range' => ['required', 'string'],
    ]);

    if ($request->hasFile('file')) {
        $path = $request->file('file')->store('uploads', 'public');


        Registration::create([
            'name' => $validated['name'],
            'mobile' => $validated['mobile'],
            'email' => $validated['email'],
            'file_path' => $path,
            'income_range' => $request->income_range,
            'tax_amount' => $this->calculateTax($request->income_range),
            'user_id' => auth()->id(), 
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
            
            
             
                        private function calculateTax($range)
            {
                switch ($range) {
                    case '0-50000':
                        return 0;
                    case '50001-75000':
                        return 200;
                    case '75001-100000':
                        return 300;
                    case '100001-150000':
                        return 500;
                    case '150001-200000':
                        return 750;
                    case '200001-250000':
                        return 1000;
                    case '250001-300000':
                        return 1250;
                    case '300001-350000':
                        return 1500;
                    case '350001-400000':
                        return 1800;
                    case '400001-450000':
                        return 2100;
                    case '450001-500000':
                        return 2400;
                    case '500001':
                        return 2500;
                    default:
                        return 0;
                }
            }

            
            
     }       
                    
                    
                    
                    
                    
                    
                    