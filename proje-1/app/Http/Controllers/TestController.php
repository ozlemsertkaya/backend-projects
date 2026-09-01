<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function showBlade()
    {
        return view('rota2');
    }
    public function showJson()
    {
        return response()->json([
            'status' => 'success',
            'mesaj' => 'Rota 3 çalışıyor, JSON response başarılı!',
            'data' => [
                'id' => 1,
                'proje' => 'Laravel Test'
            ]
        ], 200);
    }
}
