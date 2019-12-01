<?php 
namespace App\Tests\Controller;
use App\Entity\Post;

use PHPUnit\Framework\TestCase;

class PostTest extends TestCase {
    // Testing getters and setters or accessors and mutators.

    public function testSetTitle(){
        $post = new Post();
        $this->assertSame(null, $post->getTitle());
        $post->setTitle('Proton');
        $this->assertSame('Proton', $post->getTitle(),'Your test fails');
    }
    public function testDirectoryExists(){
        
        $path ='/var/www/html/sicro/tests/Controller';
        $this->assertDirectoryExists($path,'It not exists');
    }
}