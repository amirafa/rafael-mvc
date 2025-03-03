<?php

class PostController extends Controller {
    private $postModel;

    public function __construct() {
        $this->postModel = new Post();
    }

    public function index() {
        $posts = $this->postModel->getAll();
        $this->jsonResponse($posts);
    }

    public function show($id) {
        $post = $this->postModel->findById($id);
        if ($post) {
            $this->jsonResponse($post);
        } else {
            $this->jsonResponse(['message' => 'Post not found'], 404);
        }
    }

    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['title']) || empty($input['body'])) {
            $this->jsonResponse(['message' => 'Title and body are required'], 400);
        }

        if ($this->postModel->create($input)) {
            $this->jsonResponse(['message' => 'Post created successfully']);
        } else {
            $this->jsonResponse(['message' => 'Failed to create post'], 500);
        }
    }

    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['title']) || empty($input['body'])) {
            $this->jsonResponse(['message' => 'Title and body are required'], 400);
        }

        if ($this->postModel->update($id, $input)) {
            $this->jsonResponse(['message' => 'Post updated successfully']);
        } else {
            $this->jsonResponse(['message' => 'Failed to update post'], 500);
        }
    }

    public function delete($id) {
        if ($this->postModel->delete($id)) {
            $this->jsonResponse(['message' => 'Post deleted successfully']);
        } else {
            $this->jsonResponse(['message' => 'Failed to delete post'], 500);
        }
    }
}
