<?php

namespace Laravolt\Comma\Tests;

use Laravolt\Comma\Models\Category;
use Laravolt\Comma\Models\Post;
use Laravolt\Comma\Models\Tag;

class TaggableTest extends TestCase
{
    protected $post;

    protected function setUp()
    {
        parent::setUp();

        $author = $this->createUser();

        $this->post = app('laravolt.comma')->makePost($author, 'New Post', 'Hello world', 'category 1');
    }

    /**
     * @test
     */
    public function it_can_add_single_tag()
    {
        $this->post->tag('new tag');

        $this->assertDatabaseHas(app(Tag::class)->getTable(), [
            'name' => 'new tag',
            'slug' => 'new-tag',
        ]);
    }

    /**
     * @test
     */
    public function it_can_add_multiple_tag()
    {
        $this->post->tag(['tag 1', 'tag 2']);

        $this->assertDatabaseHas(app(Tag::class)->getTable(), [
            'name' => 'tag 1'
        ]);

        $this->assertDatabaseHas(app(Tag::class)->getTable(), [
            'name' => 'tag 2'
        ]);

        $this->assertCount(2, $this->post->tags);
    }

    /**
     * @test
     */
    public function it_can_retag()
    {
        $this->post->tag(['tag 1', 'tag 2']);
        $this->post->retag(['tag 3', 'tag 4', 'tag 5']);

        $this->assertCount(3, $this->post->tags);

    }

    /**
     * @test
     */
    public function it_can_untag()
    {
        $this->post->tag(['tag 1', 'tag 2']);

        // untag array
        $this->post->untag(['tag 1']);
        $this->assertCount(1, $this->post->tags);

        // untag string
        $this->post->untag('tag 2');
        $this->assertCount(0, $this->post->tags);

    }

}
