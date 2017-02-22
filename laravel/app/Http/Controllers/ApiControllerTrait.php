<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

trait ApiControllerTrait

{
  public function index(Request $request)
  {
    /*
          examples of urls
          http://localhost:8000/api/banks?where[id]=13&order=id,asc
          http://localhost:8000/api/banks?where[id]=13
          http://localhost:8000/api/banks?order=id,asc
          http://localhost:8000/api/banks?like=title,Caixa

          or change 'banks' for the 'accounts' with the result is the same, will show results of table accounts

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

    $result = $this->model->orderBy($order[0], $order[1])
      ->where(function($query) use($like){
        if($like){
          return $query->where($like[0], 'like', $like[1]);
        }
        return $query;
      })
      ->where($where)
      ->with($this->relationships())
      ->paginate($limit);

    return response()->json($result);

  }

  public function show($id)
  {
    //$result = $this->model->findOrFail($id);
      // --> modo simples, sem fazer referencia a nenhuma relação

    //$result = $this->model->with(['bank'])->findOrFail($id);
      // --> assim terei problema quando a função show usar outras models, que não sejam a model accounts
      // --> se o controller acionar a model accounts, ok, ela realmente tem relacao com a tb banks...

        $result = $this->model->with($this->relationships())
        ->findOrFail($id);
        return response()->json($result);
      // --> fazendo referencia a funcao relationships, esta que vai se ebcarregar de ver quais são as relações

  }

  public function store(Request $request)
  {
    $result = $this->model->create($request->all());
    return response()->json($result);
  }

  public function update(Request $request, $id)
  {
    $result = $this->model->findOrFail($id);
    $result->update($request->all());
    return response()->json($result);
  }

  public function destroy($id)
  {
    $result = $this->model->findOrFail($id);
    $result->delete();
    return response()->json($result);
  }

  public function relationships()
  {
    if(isset($this->relationships)) {
      return $this->relationships;
    }
    return [];
  }
}
