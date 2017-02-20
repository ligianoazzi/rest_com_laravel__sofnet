<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BanksController extends Controller
{
  function index(){
    $result = \App\Bank::paginate();

    return response()->json($result);
  }

}
