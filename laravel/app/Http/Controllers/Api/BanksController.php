<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BanksController extends Controller
{
  public function index(Request $request)
  {
    /*
          examples of urls
          http://localhost:8000/api/banks?where[id]=13&order=id,asc
          http://localhost:8000/api/banks?where[id]=13
          http://localhost:8000/api/banks?order=id,asc
          http://localhost:8000/api/banks?like=title,Caixa

    */

    $request['limit'] ? $limit = $request['limit'] : $limit = 15;
    $request['order'] ? $order = $request['order'] : $order = null;

    if ($order){
      $order = explode(',', $order);
    }else{
      $order[0] = 'id';
      $order[1] = 'asc';
    }

    if ( $request['where']){
      $where = $request->all()['where'];
    }else{
      $where = [];
    }


    if($request['like']){
      $like = $request['like'];
    }else{
      $like = null;
    }

    if($like){
      $like = explode(',', $like);
      $like[1] = '%' . $like[1] . '%';
    }

    $result = \App\Bank::orderBy($order[0], $order[1])
      ->where(function($query) use($like){
        if($like){
          return $query->where($like[0], 'like', $like[1]);
        }
        return $query;
      })
      ->where($where)
      ->paginate($limit);

    return response()->json($result);

  }

  public function show($id)
  {
    $result = \App\Bank::findOrFail($id);
    return response()->json($result);
  }

  public function store(Request $request)
  {
    $result = \App\Bank::create($request->all());
    return response()->json($result);
  }

}
