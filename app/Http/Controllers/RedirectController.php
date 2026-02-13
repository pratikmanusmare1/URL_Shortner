<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

class RedirectController extends Controller
{
    public function show($shortCode)
    {
        $url = Url::where('short_code', $shortCode)->firstOrFail();


        $url->increment('clicks');

        return redirect()->away($url->original_url);
    }
}
