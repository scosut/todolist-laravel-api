<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Todo;

class TodosController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $todos = Todo::orderBy('created_at', 'desc')->get();

    if (count($todos) > 0) {
      return response()->json(["success" => true, "data" => $todos]);
    }
    else {
      return response()->json(["success" => false, "data" => []]);
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = json_decode($request->getContent(), true);    
    $data = filter_var_array($data, FILTER_SANITIZE_STRING);

    // validate inputs
    $validator = Validator::make($data,
        [
          "text" => "required",
          "body" => "required",
          "due"  => "required"
        ]
    );

    // if validation fails
    if($validator->fails()) {
      return response()->json(["success" => false, "errors" => $validator->errors(), "message" => "failed validation", "data" => $data]);
    }

    $todo = Todo::create($data);
    
    if(!is_null($todo)) {            
      return response()->json(["success" => true, "errors" => [], "message" => "todo created"]);
    }    
    else {
      return response()->json(["success" => false, "errors" => [], "message" => "todo not created"]);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $todo = Todo::find($id);

    if (!is_null($todo)) {
      return response()->json(["success" => true, "data" => $todo]);
    }
    else {
      return response()->json(["success" => false, "data" => []]);
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $data = json_decode($request->getContent(), true);    
    $data = filter_var_array($data, FILTER_SANITIZE_STRING);

    // validate inputs
    $validator = Validator::make($data,
        [
          "text" => "required",
          "body" => "required",
          "due"  => "required"
        ]
    );

    // if validation fails
    if($validator->fails()) {
      return response()->json(["success" => false, "errors" => $validator->errors(), "message" => "failed validation", "data" => $data]);
    }

    $rows_updated = Todo::where("id", $id)->update($data);
    
    if ($rows_updated == 1) {            
      return response()->json(["success" => true, "errors" => [], "message" => "todo updated"]);
    }    
    else {
      return response()->json(["success" => false, "errors" => [], "message" => "todo not updated"]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $todo = Todo::find($id);
      
    if (!is_null($todo)) {
      $rows_deleted = Todo::where("id", $id)->delete();
      
      if ($rows_deleted == 1) {            
        return response()->json(["success" => true, "message" => "todo deleted"]);
      }    
      else {
        return response()->json(["success" => false, "message" => "todo not deleted"]);
      }
    }
    else {
      return response()->json(["success" => false, "message" => "todo not found"]);
    }
  }
}