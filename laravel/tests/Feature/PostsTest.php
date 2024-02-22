<?php


namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;

class PostsTest extends TestCase
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


    public function test_post_list()
    {
        Sanctum::actingAs(self::$testUser);
        // List all files using API web service
        $response = $this->getJson("/api/posts");
        // Check OK response
        $this->_test_ok($response);
    }

    public function test_post_create() : object
   {
        Sanctum::actingAs(self::$testUser);
        // Create fake file
        $name  = "avatar.png";
        $size = 500; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        // Upload fake file using API web service
        $response = $this->postJson("/api/posts", [
            "upload" => $upload,
            'body' => 'this is a test post',
            'latitude'=>13,
            'longitude' => 12
        ]);
        // Check OK response
        $this->_test_ok($response, 201);
        // Check validation errors
        $response->assertValid(["upload"]);
        // Check JSON dynamic values
        $response->assertJsonPath("data.id",
            fn ($id) => !empty($id)
        );
        $response->assertJsonPath("data.body",
            fn ($body) => !empty($body)
        );
        $response->assertJsonPath("data.longitude",
            fn ($longitude) => !empty($longitude)
        );
        $response->assertJsonPath("data.latitude",
            fn ($latitude) => !empty($latitude)
        );
        // Read, update and delete dependency!!!
        $json = $response->getData();
        return $json->data;
   }

   public function test_post_create_error()
   {
        Sanctum::actingAs(self::$testUser);
        // Create fake file with invalid max size
        $name  = "avatar.png";
        $size = 5000; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        // Upload fake file using API web service
        $response = $this->postJson("/api/files", [
            "upload" => $upload,
            'body' => 'this is a test post',
            'latitude'=>13,
            'longitude' => 12
        ]);
        // Check ERROR response
        $this->_test_error($response);
   }


    /**
    * @depends test_post_create
    */
   public function test_post_read(object $post)
   {
        Sanctum::actingAs(self::$testUser);
        // Read one file
        $response = $this->getJson("/api/posts/{$post->id}");
        // Check OK response
        $this->_test_ok($response);
        // Check JSON exact values
        $response->assertJsonPath("data.id",
            fn ($id) => !empty($id)
        );
        $response->assertJsonPath("data.body",
            fn ($body) => !empty($body)
        );
        $response->assertJsonPath("data.longitude",
            fn ($longitude) => !empty($longitude)
        );
        $response->assertJsonPath("data.latitude",
            fn ($latitude) => !empty($latitude)
        );
   }
 
   public function test_post_read_notfound()
   {
       $id = "not_exists";
       $response = $this->getJson("/api/posts/{$id}");
       $this->_test_notfound($response);
   }

    /**
    * @depends test_post_create
    */
    public function test_post_update(object $post)
    {
    
        Sanctum::actingAs(self::$testUser);

        // Create fake file
        $name  = "avatar.png";
        $size = 500; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        // Upload fake file using API web service
        $response = $this->postJson("/api/posts/{$post->id}", [
            "upload" => $upload,
            'body' => 'this is an updated test post',
            'latitude'=>13,
            'longitude' => 12,
            'visibility'=>1,
        ]);
        // Check OK response
        $this->_test_ok($response, 201);
        // Check validation errors
        $response->assertValid(["upload"]);
        // Check JSON dynamic values
        $response->assertJsonPath("data.id",
            fn ($id) => !empty($id)
        );
        $response->assertJsonPath("data.body",
            fn ($body) => !empty($body)
        );
        $response->assertJsonPath("data.longitude",
            fn ($longitude) => !empty($longitude)
        );
        $response->assertJsonPath("data.latitude",
            fn ($latitude) => !empty($latitude)
        );
    }

    public function test_post_update_error()
    {
        Sanctum::actingAs(self::$testUser);
        // Create fake file with invalid max size
        $name  = "avatar.png";
        $size = 5000; /*KB*/
        $upload = UploadedFile::fake()->image($name)->size($size);
        // Upload fake file using API web service
        $response = $this->postJson("/api/posts", [
            "upload" => $upload,
            'body' => 'this is a test post',
            'latitude'=>13,
            'longitude' => 12
        ]);
        // Check ERROR response
        $this->_test_error($response);
    }
    public function test_post_update_notfound()
    {
        Sanctum::actingAs(self::$testUser);
        $id = "not_exists";
        $fakeFile = UploadedFile::fake()->create('test-file.jpg');
        $response = $this->putJson("/api/posts/{$id}", [
            'upload'=> $fakeFile,
            'body' => 'this is a test post',
            'latitude'=>13,
            'longitude' => 12
        ]);
        $this->_test_notfound($response);
    }
       /**
    * @depends test_post_create
    */
    public function test_post_like(object $post){
        Sanctum::actingAs(self::$testUser);
        $response = $this->postJson("/api/posts/{$post->id}/like");
        $this->_test_ok($response);
    }
    /**
    * @depends test_post_create
    */
    public function test_post_unlike(object $post){
        Sanctum::actingAs(self::$testUser);
        $response = $this->deleteJson("/api/posts/{$post->id}/like");
        $this->_test_ok($response);
        $response->assertJson([
            "message" => "deleted",
        ]);
    }
    
    /**
    * @depends test_post_create
    */
    public function test_post_delete(object $post)
    {
        Sanctum::actingAs(self::$testUser);
        // Delete one file using API web service
        $response = $this->deleteJson("/api/posts/{$post->id}");
        // Check OK response
        $this->_test_ok($response);
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