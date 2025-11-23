<?php

use Illuminate\Support\Facades\Route;
use \App\Livewire\HomePage;
/* Route::get('/', function () {
    return view('livewire.home-page');
});  */

Route::get('/', HomePage::class);
