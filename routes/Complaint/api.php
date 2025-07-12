<?php

use App\Http\Controllers\Api\V1\Complaint\ComplaintController;
use Illuminate\Support\Facades\Route;

/**
 * Complaint API Routes
 *
 * This file defines all API routes specifically related to complaint management.
 * These routes are grouped under the 'complaints' prefix to maintain
 * a clean and organized API structure, and they share a common name prefix
 * for consistent route naming.
 */

// Group all complaint-related API routes under the 'complaints' prefix
// and apply a 'complaints.' name prefix for all routes within this group.
Route::prefix('complaints')->name('complaints.')->group(function () {

    /**
     * POST /api/complaints
     * Route to store a new complaint in the database.
     * This endpoint handles the submission of new complaint data.
     * Full Route Name: 'complaints.store'
     */
    Route::post('/create', [ComplaintController::class, 'store'])->name('store');

    /**
     * GET /api/complaints/configs
     * Route to retrieve configuration data for complaint creation forms (e.g., dropdown options).
     * This endpoint provides necessary lookup data for frontend components.
     * Full Route Name: 'complaints.getConfigs'
     */
    Route::get('/configs', [ComplaintController::class, 'getConfigs'])->name('getConfigs');

    /**
     * GET /api/complaints/{complaint}
     * Route to display the details of a specific complaint.
     * It uses Route Model Binding to automatically resolve the complaint by its unique identifier (ID or UUID) from the URI.
     * Full Route Name: 'complaints.show'
     */
    Route::get('/{complaint}', [ComplaintController::class, 'show'])->name('show');
    Route::post('/search', [ComplaintController::class, 'search'])->name('show');
});
