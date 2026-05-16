<?php

use App\Http\Controllers\AccidentController;
use App\Http\Controllers\Admin\AccidentHotspotController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CongestionController;
use App\Http\Controllers\MapDataController;
use App\Http\Controllers\OfficerAssignmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrafficReportController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/login', [AuthController::class, 'redirectToLogin'])->name('auth.login.redirect');

Route::middleware(['auth'])->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me'])->name('auth.me');

    Route::middleware(['check_role:user'])->group(function () {
        Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
        Route::post('/accident', [AccidentController::class, 'store'])->name('accident.store');
        Route::post('/traffic', [TrafficReportController::class, 'store'])->name('traffic.store');
    });

    Route::get('/map/accidents', [MapDataController::class, 'accidents'])->name('map.accidents');

    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/{type}/{id}', [AlertController::class, 'show'])
        ->whereIn('type', ['accident', 'congestion'])
        ->name('alerts.show');

    Route::get('/congestion', [CongestionController::class, 'index'])->name('congestion.index');
    Route::post('/congestion', [CongestionController::class, 'store'])->name('congestion.store');
    Route::get('/congestion/report/{trafficReport}', [CongestionController::class, 'show'])->name('congestion.show');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['check_role:admin,officer'])->group(function () {
        Route::get('/assignments', [OfficerAssignmentController::class, 'index'])->name('assignments.index');
    });
    Route::middleware(['check_role:officer'])->group(function () {
        Route::patch('/assignments/{accident}/resolve', [OfficerAssignmentController::class, 'resolveByOfficer'])->name('assignments.resolve');
    });

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::post('/hotspots', [AccidentHotspotController::class, 'store'])->name('hotspots.store');
        Route::patch('/hotspots/{hotspot}', [AccidentHotspotController::class, 'update'])->name('hotspots.update');
        Route::delete('/hotspots/{hotspot}', [AccidentHotspotController::class, 'destroy'])->name('hotspots.destroy');
        Route::patch('/accident/{accident}/approve', [AccidentController::class, 'approve'])->name('accident.approve');
        Route::patch('/accident/{accident}/resolve', [AccidentController::class, 'resolve'])->name('accident.resolve');
        Route::patch('/accident/{accident}/assign', [OfficerAssignmentController::class, 'assign'])->name('accident.assign');
        Route::patch('/accident/{accident}/unassign', [OfficerAssignmentController::class, 'unassign'])->name('accident.unassign');
        Route::patch('/traffic/{trafficReport}/approve', [TrafficReportController::class, 'approve'])->name('traffic.approve');
        Route::patch('/traffic/{trafficReport}/resolve', [TrafficReportController::class, 'resolve'])->name('traffic.resolve');
        
        // Alerts routes
        Route::get('/alerts', [\App\Http\Controllers\Admin\AlertsController::class, 'index'])->name('alerts.index');
        Route::get('/alerts/create', [\App\Http\Controllers\Admin\AlertsController::class, 'create'])->name('alerts.create');
        Route::post('/alerts', [\App\Http\Controllers\Admin\AlertsController::class, 'store'])->name('alerts.store');
        Route::patch('/alerts/{type}/{id}/status', [\App\Http\Controllers\Admin\AlertsController::class, 'updateStatus'])->name('alerts.status.update');
        Route::get('/alerts/index1', [\App\Http\Controllers\Admin\AlertsController::class, 'index1'])->name('alerts.index1');
        Route::get('/alerts/index2', [\App\Http\Controllers\Admin\AlertsController::class, 'index2'])->name('alerts.index2');
        Route::get('/alerts/statistics', [\App\Http\Controllers\Admin\AlertsController::class, 'statistics'])->name('alerts.statistics');
        
        // Reports routes
        Route::get('/reports/generate', [\App\Http\Controllers\Admin\ReportsController::class, 'generate'])->name('reports.generate');
        Route::get('/reports/statistics', [\App\Http\Controllers\Admin\ReportsController::class, 'statistics'])->name('reports.statistics');
        Route::get('/reports/{id}/edit', [\App\Http\Controllers\Admin\ReportsController::class, 'edit'])->name('reports.edit');
        Route::patch('/reports/{id}', [\App\Http\Controllers\Admin\ReportsController::class, 'update'])->name('reports.update');
        Route::post('/reports/download-accidents', [\App\Http\Controllers\Admin\ReportsController::class, 'downloadAccidents'])->name('reports.download-accidents');
        Route::post('/reports/download-congestion', [\App\Http\Controllers\Admin\ReportsController::class, 'downloadCongestion'])->name('reports.download-congestion');
        Route::post('/reports/download-user-activity', [\App\Http\Controllers\Admin\ReportsController::class, 'downloadUserActivity'])->name('reports.download-user-activity');
        Route::post('/reports/export-excel', [\App\Http\Controllers\Admin\ReportsController::class, 'exportExcel'])->name('reports.export-excel');
        Route::post('/reports/export-csv', [\App\Http\Controllers\Admin\ReportsController::class, 'exportCsv'])->name('reports.export-csv');
    });
});

require __DIR__.'/auth.php';
