<?php

namespace Tests\Feature\tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\Comment;
use Laravel\Sanctum\Sanctum;

class CommentsTest extends TestCase
{
    public static User $testUser;   
    public static array $testUserData = [];
    public static array $validData = [];
    public static array $invalidData = [];
    public static function setUpBeforeClass() : void
   {
       parent::setUpBeforeClass();
       // Creem usuari/a de prova
       $name = "test_" . time();
       self::$testUserData = [
           "name"      => "{$name}",
           "email"     => "{$name}@mailinator.com",
           "password"  => "12345678"
       ];
       // TODO Omplir amb dades vàlides
       self::$validData = [];
       // TODO Omplir amb dades incorrectes
       self::$invalidData = [];
   }


   public function test_post_first()
   {
       // Desem l'usuari al primer test
       self::$testUser = new User(self::$testUserData);
       self::$testUser->save();
       // Comprovem que s'ha creat
       $this->assertDatabaseHas('users', [
           'email' => self::$testUserData['email'],
       ]);
   }

   public function test_comment_create() : object
   {
        Sanctum::actingAs(self::$testUser);
        $user_id = self::$testUser->id;
        $upload = UploadedFile::fake()->image('avatar.png')->size(500);
        $response = $this->postJson("/api/files", [
            "upload" => $upload,
        ]);
        $jsonResponse = $response->json();
        $filePath = $jsonResponse["filepath"];
        $post = Post::create([
            'body'=>'temporal test post',
            'file_id'=>$filePath,
            'latitude'=>'13',
            'longitude'=>'12',
            'visibility_id'=>1,
            'author_id'=>$user_id,     
        ]);
       // List all files using API web service
       $response = $this->postJson("/api/posts/{$post->id}/comments".[
        'body'=> 'test comment',
       ]);
       // Check OK response
       $this->_test_ok($response);

       $json = $response->getData();
       return $json->data;
   }

   

    /**
    * @depends test_comment_create
    */

   public function test_comment_list(object $comment)
   {
    Sanctum::actingAs(self::$testUser);
    $response = $this->getJson("/api/posts/{}/comments");
   }


   protected function _test_ok($response, $status = 200)
   {

       // Check JSON response
       $response->assertStatus($status);
       // Check JSON properties
       $response->assertJson([
           "success" => true,
       ]);
       // Check JSON dynamic values
       $response->assertJsonPath("data",
           fn ($data) => is_array($data)
       );
   }


   protected function _test_error($response)
   {
       // Check response
       $response->assertStatus(422);
       // Check validation errors
       $response->assertInvalid(["upload"]);
       // Check JSON properties
       $response->assertJson([
           "message" => true, // any value
           "errors"  => true, // any value
       ]);       
       // Check JSON dynamic values
       $response->assertJsonPath("message",
           fn ($message) => !empty($message) && is_string($message)
       );
       $response->assertJsonPath("errors",
           fn ($errors) => is_array($errors)
       );
   }
  
   protected function _test_notfound($response)
   {
       // Check JSON response
       $response->assertStatus(404);
       // Check JSON properties
       $response->assertJson([
           "success" => false,
           "message" => true // any value
       ]);
       // Check JSON dynamic values
       $response->assertJsonPath("message",
           fn ($message) => !empty($message) && is_string($message)
       );       
   }
}
