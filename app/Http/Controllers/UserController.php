<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Models\User;

Class UserController extends Controller {
    use ApiResponser;
    private $request;
    public function __construct(Request $request){
        $this->request = $request;
    }
    public function getUsers(){
        $users = User::all();
       
        return response::json($users, 200);
    }
    

    public function index(){
        $users = User::all();
        return $this->successResponse($users);
        
    }

    public function add(Request $request ){
        $rules = [
        'username' => 'required|max:20',
        'password' => 'required|max:20',
        ];
        $this->validate($request,$rules);
        $user = User::create($request->all());
        return $this->successResponse($user, 
       Response::HTTP_CREATED);
    }

    public function show($id)
    {
    //$user = User::findOrFail($id);
    $user = User::where('id', $id)->first();
    if ($user){
        return $this->successResponse($user);
    }
    {
        return $this->errorResponse('User ID Does Not Exists', Response::HTTP_NOT_FOUND);
    }
    }
    public function update(Request $request,$id)
    {
        $rules = [
        'username' => 'max:20',
        'password' => 'max:20',

        ];
        $this->validate($request, $rules);
        $user = User::findOrFail($id);
        
        $user->fill($request->all());
        // if no changes happen
        if ($user->isClean()) {
        return $this->errorResponse('At least one value must 
        change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->save();
        return $this->successResponse($user);
    }
    /**
     * Remove an existing user
     * @return Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->successResponse($user);
    }



}