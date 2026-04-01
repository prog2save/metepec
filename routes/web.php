<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CiudadanoController;
use App\Http\Controllers\DireccionMunicipalController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\EstadoTicketController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TicketViewController;
use App\Http\Controllers\MacroController;
use App\Services\GeocodingService;

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
        $role = Auth::user()->role;

        if ($role === 'agente') {
            return redirect()->route('agente.dashboard');
        }

        return app(DashboardController::class)->index();
        
    })->middleware('auth')->name('dashboard');

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

    Route::get('/usuarios/suspendidos', [UserController::class, 'suspendidos'])->name('usuarios.suspendidos');
    Route::patch('/usuarios/{id}/reactivar', [UserController::class, 'reactivar'])->name('usuarios.reactivar');
    Route::resource('usuarios', UserController::class);
    Route::resource('ciudadanos', CiudadanoController::class);
    Route::resource('direcciones', DireccionMunicipalController::class);
    Route::resource('tickets', TicketController::class);
    Route::put('/tickets/{id}/resuelto', [TicketController::class, 'tickethecho'])->name('tickets.tickethecho');
    Route::resource('servicios', ServiciosController::class);
    Route::resource('estados', EstadoTicketController::class);
    Route::resource('macros', MacroController::class);
    Route::patch('macros/{macro}/toggle', [MacroController::class, 'toggleActive'])->name('macros.toggle');
    /*
    Route::get('/test-geocode', function (Request $request, GeocodingService $geo) {

        $direccion = $request->query('direccion', 'Boulevard Héroes de 5 de Mayo 410 Centro Histórico Puebla México');

        $resultado = $geo->getCoordinates($direccion);

        return response()->json([
            'direccion_enviada' => $direccion,
            'resultado' => $resultado
        ]);

    });
    */
    

    Route::prefix('ticket-views')->name('ticket-views.')->group(function () {
            Route::get('/',                  [TicketViewController::class, 'index'])->name('index');
            Route::post('/',                 [TicketViewController::class, 'store'])->name('store');
            Route::get('/create',            [TicketViewController::class, 'create'])->name('create');
            Route::get('/{ticketView}/edit', [TicketViewController::class, 'edit'])->name('edit');
            Route::put('/{ticketView}',      [TicketViewController::class, 'update'])->name('update');
            Route::delete('/{ticketView}',   [TicketViewController::class, 'destroy'])->name('destroy');
            Route::patch('/reorder',              [TicketViewController::class, 'reorder'])->name('reorder');
            Route::patch('/{id}/restore',         [TicketViewController::class, 'restore'])->name('restore');
            Route::patch('/{ticketView}/toggle',  [TicketViewController::class, 'toggle'])->name('toggle');
        });

    Route::prefix('admin')->name('admin.')->group(function () {
        
    });


    Route::get('/archivos/preview', function (Request $request) {
        $path = $request->query('path');
        $mime = $request->query('mime', 'application/octet-stream');

        abort_unless(Storage::disk('private')->exists($path), 404);

        $contenido = Storage::disk('private')->get($path);
        $nombre    = basename($path);

        return response($contenido, 200, [
            'Content-Type'        => $mime,
            'Content-Disposition' => 'inline; filename="' . $nombre . '"',
        ]);
    })->middleware(['auth', 'signed'])->name('archivo.preview');

    // Rutas para agentes
    Route::prefix('agente')->name('agente.')->group(function () {
        Route::get('/dashboard', [AgenteController::class, 'dashboard'])->name('dashboard');
        Route::get('/tickets',   [AgenteController::class, 'tickets'])->name('tickets.index');
        Route::get('/tickets/create', [AgenteController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [AgenteController::class, 'store'])->name('tickets.store');
        Route::put('/tickets/{ticket}/resolver', [AgenteController::class, 'resolver'])->name('tickets.resolver');
        Route::get('/ciudadanos/create', [AgenteController::class, 'ciudadanoCreate'])->name('ciudadanos.create');
        Route::post('/ciudadanos', [AgenteController::class, 'ciudadanoStore'])->name('ciudadanos.store');
        Route::get('tickets/{id}', [AgenteController::class, 'show'])->name('tickets.show');
        Route::patch('tickets/{id}', [AgenteController::class, 'update'])->name('tickets.update');
        Route::post('tickets/{id}/responder', [AgenteController::class, 'responder'])->name('tickets.responder');
        Route::post('/agente/ciudadanos', [AgenteController::class, 'ciudadanoStore'])->name('agente.ciudadanos.store');
        Route::get('/ticket-views', [AgenteController::class, 'indexForAgent'])->name('ticket-views.index');
        Route::get('/ticket-views/{ticketView}', [AgenteController::class, 'showView'])->name('ticket-views.show');
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
