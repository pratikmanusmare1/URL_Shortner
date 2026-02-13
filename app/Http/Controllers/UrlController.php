<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Url;
use App\Models\Company;
use App\Models\User;

class UrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        // dd($user->role);

        if ($user->role ===User::ROLE_SUPERADMIN) {
            $companyId = $request->query('company_id');
            $urls = Url::when($companyId, function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->orderBy('created_at', 'desc')->get();

            $companies = Company::all();
// dd($urls);
            return view('urls.index', compact('urls', 'companies'));
        }

        $companyId = session('company_id');
        if (! $companyId) {
            return redirect()->route('home')->with('error', 'Please select a company from the navbar.');
        }

        $isAdmin = $user->companies()->where('company_id', $companyId)->wherePivot('role', 'admin')->exists();
        // dd($isAdmin, $user->id);

        if ($isAdmin) {
            $urls = Url::where('company_id', $companyId)->orderBy('created_at', 'desc')->get();
        } else {
            $urls = Url::where('company_id', $companyId)->where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        }

        return view('urls.index', compact('urls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $user = auth()->user();
        if ($user->role === User::ROLE_SUPERADMIN) {
            return back()->with('error', 'SuperAdmin cannot create short URLs.');
        }

        $companyId = session('company_id');
        if (! $companyId) {
            return back()->with('error', 'Please select a company first.');
        }

        $tempShortCode = Str::random(10);
        
        $url = Url::create([
            'user_id' => $user->id,
            'company_id' => $companyId,
            'original_url' => $request->input('original_url'),
            'short_code' => $tempShortCode,
        ]);
        // dd($url);

        return redirect()->route('urls.index')->with('success', 'Short URL created: ' . url('/s/' . $url->short_code));
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $url = Url::findOrFail($id);

        if ($user->role ===User::ROLE_SUPERADMIN) {
            $url->delete();
            return back()->with('success', 'URL deleted.');
        }

        $companyId = session('company_id');
        if (! $companyId) {
            return back()->with('error', 'Please select a company.');
        }

        $isAdmin = $user->companies()->where('company_id', $companyId)->wherePivot('role', 'admin')->exists();
        if ($isAdmin || $url->user_id == $user->id) {
            $url->delete();
            return back()->with('success', 'URL deleted.');
        }

        return back()->with('error', 'Unauthorized.');
    }
}
