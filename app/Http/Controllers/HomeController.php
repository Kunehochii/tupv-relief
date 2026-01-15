<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }

    public function drivePreview(Drive $drive): View
    {
        return view('public.drive-preview', compact('drive'));
    }
}
