<?php
/**
 * Впервые пишу тесты, прошу не кидаться тапками:)
 */

namespace UnitTest;


use ExampleComApiClient\Client;
use ExampleComApiClient\Comment;
use ExampleComApiClient\CommentCollection;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private ?Client $client;
    private ?Comment $comment;
    private ?CommentCollection $commentCollection;

    protected function setUp(): void
    {
        $this->client = new Client();
        $this->comment = new Comment();
        $this->commentCollection = new CommentCollection();
    }

    protected function tearDown(): void
    {
        $this->client = null;
        $this->comment = null;
        $this->commentCollection = null;
    }

    public function testGetComments()
    {
        $comments = $this->client->getComments();
        $this->assertIsArray($comments);
        $this->assertInstanceOf(Comment::class, $comments[0]);
    }

    public function testCreateComment()
    {
        $this->comment
            ->setName('Test name')
            ->setText('Test text');

        $resultComment = $this->client->createComment('Test name', 'Test text');
        $this->assertInstanceOf(Comment::class, $resultComment);
        $this->assertEquals($this->comment->getName(), $resultComment->getName());
        $this->assertEquals($this->comment->getText(), $resultComment->getText());
    }

    public function testUpdateComment()
    {
        $this->comment
            ->setName('Test name')
            ->setText('Test text');

        $resultComment = $this->client->updateComment(1, 'Test name', 'Test text');
        $this->assertInstanceOf(Comment::class, $resultComment);
        $this->assertEquals($this->comment->getName(), $resultComment->getName());
        $this->assertEquals($this->comment->getText(), $resultComment->getText());
    }
}