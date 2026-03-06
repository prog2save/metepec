<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CiudadanoController;
use App\Http\Controllers\DireccionMunicipalController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AgenteController;

// Rutas publicas de autenticacion (solo para no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login',     [App\Http\Controllers\Auth\LoginController::class,    'showForm'])->name('login');
    Route::post('/login',    [App\Http\Controllers\Auth\LoginController::class,    'login']);
    //Route::get('/register',  [App\Http\Controllers\Auth\RegisterController::class, 'showForm'])->name('register');
    //Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
});

// Rutas protegidas (requieren sesion iniciada)
Route::middleware('auth')->group(function () {

    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return view('pages.dashboard.ecommerce', ['title' => 'E-commerce Dashboard']);
    })->name('dashboard');

    Route::get('/calendar', function () {
        return view('pages.calender', ['title' => 'Calendar']);
    })->name('calendar');

    Route::get('/profile', function () {
        return view('pages.profile', ['title' => 'Profile']);
    })->name('profile');

    Route::get('/form-elements', function () {
        return view('pages.form.form-elements', ['title' => 'Form Elements']);
    })->name('form-elements');

    Route::get('/basic-tables', function () {
        return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
    })->name('basic-tables');

    Route::get('/blank', function () {
        return view('pages.blank', ['title' => 'Blank']);
    })->name('blank');

    Route::get('/line-chart', function () {
        return view('pages.chart.line-chart', ['title' => 'Line Chart']);
    })->name('line-chart');

    Route::get('/bar-chart', function () {
        return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
    })->name('bar-chart');

    Route::get('/alerts', function () {
        return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
    })->name('alerts');

    Route::get('/avatars', function () {
        return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
    })->name('avatars');

    Route::get('/badge', function () {
        return view('pages.ui-elements.badges', ['title' => 'Badges']);
    })->name('badges');

    Route::get('/buttons', function () {
        return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
    })->name('buttons');

    Route::get('/image', function () {
        return view('pages.ui-elements.images', ['title' => 'Images']);
    })->name('images');

    Route::get('/videos', function () {
        return view('pages.ui-elements.videos', ['title' => 'Videos']);
    })->name('videos');

    Route::resource('usuarios', UserController::class);
    Route::resource('ciudadanos', CiudadanoController::class);
    Route::resource('direcciones', DireccionMunicipalController::class);
    Route::resource('tickets', TicketController::class);
    Route::put('/tickets/{id}/resuelto', [TicketController::class, 'tickethecho'])->name('tickets.tickethecho');
    Route::resource('servicios', ServiciosController::class);

    // Rutas para agentes
    Route::prefix('agente')->name('agente.')->group(function () {
        Route::get('/dashboard', [AgenteController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets',   [AgenteController::class, 'tickets'])->name('tickets.index');
        Route::put('/tickets/{ticket}/resolver', [AgenteController::class, 'resolver'])->name('tickets.resolver');
    });
});

// Paginas publicas
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// Vistas estaticas de TailAdmin 
Route::get('/signin', function () {
    return view('pages.auth.signin', ['title' => 'Sign In']);
})->name('signin');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');