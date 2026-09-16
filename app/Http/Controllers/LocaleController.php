<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function setLocale($lang)
    {
        if (in_array($lang, ['en', 'id'])) {
            session(['locale' => $lang]);
        }
        
        return back();
    }
}
