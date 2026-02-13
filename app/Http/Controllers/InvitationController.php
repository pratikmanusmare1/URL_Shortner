<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationMail;
use Carbon\Carbon;

class InvitationController extends Controller
{
    public function sendForm()
    {
        $user = auth()->user();

        if ($user->role === User::ROLE_SUPERADMIN) {
            $companies = Company::all();
        } else {
            $companies = $user->companies;
        }

        return view('invitations.send', compact('companies'));
    }

    
    public function index()
    {
        $user = auth()->user();

        if ($user->role === User::ROLE_SUPERADMIN) {
            $invitations = Invitation::latest()->get();
        } else {
            $invitations = Invitation::where('inviter_id', $user->id)->latest()->get();
        }

        return view('invitations.index', compact('invitations'));
    }

    public function send(Request $request)
    {
        $inviter = auth()->user();
        // dd($inviter);
        
        $roleValidation = 'required|in:admin,member';
        if ($inviter->role === User::ROLE_SUPERADMIN) {
            $roleValidation = 'required|in:admin';
        }
        
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'email' => 'required|email',
            'role' => $roleValidation,
        ]);

        $companyId = $request->input('company_id');

        if ($inviter->role !== User::ROLE_SUPERADMIN) {
            $isCompanyAdmin = $inviter->companies()
                ->where('company_id', $companyId)
                ->wherePivot('role', 'admin')
                ->exists();
            // dd($isCompanyAdmin);

            if (! $isCompanyAdmin) {
                return back()->with('error', 'You must be an admin of the company to send invitations.');
            }
        }

        $existing = Invitation::where('email', $request->email)
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'An active invitation already exists for this email.');
        }

        $inv = Invitation::create([
            'inviter_id' => $inviter->id,
            'company_id' => $companyId,
            'email' => $request->email,
            'role' => $request->role,
            'token' => Invitation::generateToken(),
        ]);

        Mail::to($inv->email)->send(new InvitationMail($inv));

        return back()->with('success', 'Invitation sent (check logs for link in dev).');
    }

    public function accept(Request $request, $token)
    {
        $inv = Invitation::where('token', $token)->firstOrFail();

        if ($inv->status !== 'pending') {
            return redirect('/')->with('error', 'Invitation is not valid.');
        }

        $user = User::where('email', $inv->email)->first();

        if (auth()->check()) {
            $authUser = auth()->user();
            if ($authUser->email !== $inv->email) {
                return redirect('/')->with('error', 'Invitation is for a different email address.');
            }

            $attached = $authUser->companies()->where('company_id', $inv->company_id)->exists();
            if (! $attached) {
                $authUser->companies()->attach($inv->company_id, ['role' => $inv->role]);
            } else {
                $authUser->companies()->updateExistingPivot($inv->company_id, ['role' => $inv->role]);
            }

            $inv->status = 'accepted';
            $inv->accepted_at = Carbon::now();
            $inv->save();

            return redirect('/home')->with('success', 'Invitation accepted!');
        }

        if ($user) {
            session(['invitation_token' => $token]);
            return redirect('/login')->with('info', 'Please log in to accept this invitation.');
        }

        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $newUser = User::create([
                'name' => $data['name'],
                'email' => $inv->email,
                'password' => bcrypt($data['password']),
                'role' => 0, // default role
            ]);

          
            $newUser->companies()->attach($inv->company_id, ['role' => $inv->role]);

            $inv->status = 'accepted';
            $inv->accepted_at = Carbon::now();
            $inv->save();

            auth()->login($newUser);

            return redirect('/home')->with('success', 'Account created and invitation accepted!');
        }

        return view('invitations.accept', ['invitation' => $inv]);
    }

    public function inviteToAdmins()
    {
        $user = auth()->user();

        $myAdminCompanyIds = $user->companies()
            ->wherePivot('role', 'admin')
            ->pluck('company_id')
            ->toArray();

        if (empty($myAdminCompanyIds)) {
            return view('invitations.invite-to-admins', ['users' => collect()]);
        }

        $users = User::whereHas('companies', function ($q) use ($myAdminCompanyIds) {
            $q->where('company_user.role', 'admin')
              ->whereNotIn('company_id', $myAdminCompanyIds);
        })->get();

        $users->each(function ($user) {
            $user->admin_companies = $user->companies()
                ->wherePivot('role', 'admin')
                ->pluck('name')
                ->implode(', ');
        });

        return view('invitations.invite-to-admins', compact('users'));
    }

    public function sendToAdmin(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        $inviter = auth()->user();
        $userId = $request->input('user_id');
        $companyId = $request->input('company_id');

        $isCompanyAdmin = $inviter->companies()
            ->where('company_id', $companyId)
            ->wherePivot('role', 'admin')
            ->exists();

        if (!$isCompanyAdmin) {
            return back()->with('error', 'You must be an admin of the company to send invitations.');
        }

        $invitedUser = User::findOrFail($userId);

        $existing = Invitation::where('email', $invitedUser->email)
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('error', 'An active invitation already exists for this user.');
        }

        $inv = Invitation::create([
            'inviter_id' => $inviter->id,
            'company_id' => $companyId,
            'email' => $invitedUser->email,
            'role' => 'admin',
            'token' => Invitation::generateToken(),
        ]);

        Mail::to($inv->email)->send(new InvitationMail($inv));

        return back()->with('success', 'Invitation sent to ' . $invitedUser->name . '!');
    }
}
