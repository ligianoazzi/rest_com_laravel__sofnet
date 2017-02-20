<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BanksController extends Controller
{
  public function index(Request $request)
  {
    $request['limit'] ? $limit = $request['limit'] : $limit = 15;
    $request['order'] ? $order = $request['order'] : $order = null;

    if ($order != null){
      $order = explode(',', $order);
    }

    //$order[0] = $order ?? 'id';
    $order[0] ? $order[0] : 'id'; // if do not receive position 0, then assume as id
    $order[1] ? $order[1] : 'asc'; // if do not receive position 1, then assume as asc

    $result = \App\Bank::orderBy($order[0], $order[1])
      ->paginate($limit);

    return response()->json($result);

  }

}
