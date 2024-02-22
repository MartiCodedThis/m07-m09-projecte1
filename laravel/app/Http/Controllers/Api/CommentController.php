<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index($post_id){
        $comments = Comment::where('post_id', $post_id);
        if($comments){
            return response()->json([
                "success"=> true,
                "data"=> $comments 
            ], 200);
        }
        else{
            return response ()->json([
                'success'=>false,
                'message'=>'Couldnt find the specified id'
            ], 404);
        }
    }
    public function store(Request $request, $post_id){
        $validatedData = $request->validate([
            'post_id' => 'required',
            'author_id' => 'required',
            'body'=> 'required'
         ]);
        if($validatedData){
            $comment = Comment::create([
                'body'=>$request->input('body'),
                'post_id'=>$post_id,
                'author_id'=>$request->user()->id, 
            ]);
            return response ()->json([
                'success'=> true,
                'data'=> $comment
            ]);
        }
        else{
            return response()->json([
                'success'=>false,
                'message'=> 'Invalid data'
            ],500);
        }
    }
    public function show($id){
        $comment = Comment::find('post_id', $id);
        if($comment){
            return response()->json([
                "success"=> true,
                "data"=> $comment 
            ], 200);
        }
        else{
            return response ()->json([
                'success'=>false,
                'message'=>'Couldnt find the specified id'
            ], 404);
        }
    }
    public function update(Request $request, $id){}
    public function destroy($id){}
    
}
