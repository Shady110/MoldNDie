<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MigrationController extends Controller
{
    public function migrate()
    {
        Artisan::call('migrate');
        return response()->json(['message' => 'Migrations executed successfully']);
    }

    public function rollback()
    {
        Artisan::call('migrate:rollback');
        return response()->json(['message' => 'Migrations rolled back successfully']);
    }
}
