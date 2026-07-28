<?php
namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\Discussion;
use App\Models\Comment;

class DiscussionController {
    public function index(Request $request): void {
        $category = $request->getParam('category');
        $discussions = Discussion::getAll($category);

        if ($request->isAjax()) {
            Response::json($discussions);
        } else {
            Response::render('discussions/index', ['discussions' => $discussions, 'currentCategory' => $category ?? 'All'], 'Civic Discussions - Kenyans Decision');
        }
    }

    public function show(Request $request, array $params): void {
        $id = $params['id'] ?? $request->getParam('id');
        $discussion = Discussion::findById($id);

        if (!$discussion) {
            Response::error('Discussion thread not found', 404);
        }

        $comments = Comment::getByDiscussionId($id);

        if ($request->isAjax()) {
            Response::json([
                'discussion' => $discussion,
                'comments' => $comments
            ]);
        } else {
            Response::render('discussions/show', [
                'discussion' => $discussion,
                'comments' => $comments
            ], $discussion['title'] . ' - Kenyans Decision');
        }
    }

    public function create(Request $request): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            Response::json(['error' => 'Please log in or register to publish a discussion topic.'], 401);
        }

        $title = $request->getParam('title');
        $content = $request->getParam('content');
        $category = $request->getParam('category', 'General Kenya');

        if (empty($title) || empty($content)) {
            Response::json(['error' => 'Discussion title and content body are required.'], 400);
        }

        try {
            $post = Discussion::create($title, $content, $category, $user);
            Response::json(['success' => true, 'post' => $post]);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function addComment(Request $request, array $params): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            Response::json(['error' => 'Authentication required to reply.'], 401);
        }

        $discussionId = $params['id'] ?? $request->getParam('discussionId');
        $content = $request->getParam('content');

        if (empty($discussionId) || empty($content)) {
            Response::json(['error' => 'Comment body cannot be blank.'], 400);
        }

        try {
            $comment = Comment::create($discussionId, $content, $user);
            Response::json(['success' => true, 'comment' => $comment]);
        } catch (\Exception $e) {
            Response::json(['error' => $e->getMessage()], 500);
        }
    }

    public function like(Request $request, array $params): void {
        $id = $params['id'] ?? $request->getParam('id');
        if (empty($id)) {
            Response::json(['error' => 'Discussion ID required'], 400);
        }

        $newLikes = Discussion::like($id);
        Response::json(['likesCount' => $newLikes]);
    }
}
