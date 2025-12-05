<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/fake-partner', function(Request $request){
    $mode = $request->query('mode');

    if($mode === 'ok'){
        return response()->json([
            'result' => [
                'status' => 'accepted',
                'token'  => 'abc-123-XYZ'
            ]
        ]);
    }
    if($mode === 'dup'){
        return response()->json([
            'result' => [
                'status' => 'duplicate',
            ]
        ]);
    }
    if($mode === 'empty'){
        return response()->noContent();
    }

});
