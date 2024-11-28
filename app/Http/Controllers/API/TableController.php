<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::select('id', 'number', 'status')->get();

        return response()->json([
            'success' => true,
            'data' => $tables
        ]);
    }

   
}
