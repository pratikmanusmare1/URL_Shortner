<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UrlController;
use App\Http\Controllers\RedirectController;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('role');
Route::get('/superAdmin/home', [HomeController::class, 'superAdminHome'])->name('super.admin.home')->middleware('role');

Route::get('/invitations/send', [InvitationController::class, 'sendForm'])->name('invitations.send-form')->middleware('auth');
Route::post('/invitations/send', [InvitationController::class, 'send'])->name('invitations.send')->middleware('auth');
Route::get('/invitations/admins/list', [InvitationController::class, 'inviteToAdmins'])->name('invitations.invite-to-admins')->middleware('auth');
Route::post('/invitations/admins/invite', [InvitationController::class, 'sendToAdmin'])->name('invitations.send-to-admin')->middleware('auth');
Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations.index')->middleware('auth');
Route::match(['get','post'], '/invitations/{token}/accept', [InvitationController::class, 'accept']);

Route::post('/company/select', [CompanyController::class, 'select'])->name('company.select')->middleware('auth');

// Company Management Routes (SuperAdmin only)
Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
});

Route::get('/urls', [UrlController::class, 'index'])->name('urls.index')->middleware('auth');
Route::post('/urls', [UrlController::class, 'store'])->name('urls.store')->middleware('auth');
Route::delete('/urls/{id}', [UrlController::class, 'destroy'])->name('urls.destroy')->middleware('auth');

Route::get('/s/{shortCode}', [RedirectController::class, 'show'])->name('redirect.show');
