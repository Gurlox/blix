<?php

declare(strict_types=1);

namespace tests\functional\Post;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class PostIntegrationTest extends WebTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        echo "Resetting test database...\n";

        passthru('php bin/console d:s:d --force --env=test');
        passthru('php bin/console d:s:u --force --env=test');
    }

    public function testCreateImageShouldSucceed(): void
    {
        $result = $this->createImage(__DIR__ . '/test_file.jpg');;

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('id', $result);
    }

    public function testCreatePostShouldSucceed(): void
    {
        // given
        $imageId = $this->createImage(__DIR__ . '/test_file.jpg')['id'];
        $title = 'title title';
        $text = 'text text text text 1234567342676666';

        // when then
        $result = $this->createPost($title, $text, $imageId);
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('postId', $result);
    }

    public function testCreatePostWithInvalidDataShouldFail(): void
    {
        // given
        $imageId = $this->createImage(__DIR__ . '/test_file.jpg')['id'];
        $title = 'short';
        $text = 'short';

        // when then
        $result = $this->createPost($title, $text, $imageId);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $this->assertArrayHasKey('message', $result);
    }

    public function testGetPostByIdShouldSucceed(): void
    {
        // given
        $imageId = $this->createImage(__DIR__ . '/test_file.jpg')['id'];
        $title = 'title title';
        $text = 'text text text text 1234567342676666';
        $postId = $this->createPost($title, $text, $imageId)['postId'];

        // when then
        $result = $this->getPost($postId);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($title, $result['title']);
        $this->assertEquals($text, $result['text']);
        $this->assertEquals('test_file.jpg', $result['image']['originalFileName']);
    }

    public function testGetPostsListShouldSucceed(): void
    {
        // given
        $imageId = $this->createImage(__DIR__ . '/test_file.jpg')['id'];
        $title = 'title title';
        $text = 'text text text text 1234567342676666';
        $this->createPost($title, $text, $imageId);
        $this->createPost($title, $text, $imageId);
        $this->createPost($title, $text, $imageId);
        $this->createPost($title, $text, $imageId);
        $this->createPost($title, $text, $imageId);
        $this->createPost($title, $text, $imageId);

        // when then
        $result = $this->getPostsList(1, 4);
        $this->assertCount(4, $result['posts']);

        // when then
        $result = $this->getPostsList(2, 4);
        $this->assertCount(2, $result['posts']);
    }

    private function createImage(string $imagePath): array
    {
        $uploadedFile = new UploadedFile(
            $imagePath,
            'test_file.jpg'
        );
        $this->client->request(
            Request::METHOD_POST,
            '/api/image',
            [],
            [
                'file' => $uploadedFile
            ]
        );

        return json_decode($this->client->getResponse()->getContent(), true);
    }

    private function createPost(string $title, string $text, string $imageId): array
    {
        $this->client->request(
            Request::METHOD_POST,
            '/api/posts',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'title' => $title,
                'text' => $text,
                'imageId' => $imageId,
            ]),
        );

        return json_decode($this->client->getResponse()->getContent(), true);
    }

    private function getPost(string $postId): array
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/posts/' . $postId,
        );

        return json_decode($this->client->getResponse()->getContent(), true);
    }

    private function getPostsList(int $page, int $perPage): array
    {
        $this->client->request(
            Request::METHOD_GET,
            '/api/posts/list',
            [
                'page' => $page,
                'perPage' => $perPage,
            ],
        );

        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
