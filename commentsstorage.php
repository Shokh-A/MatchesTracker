<?php
class CommentsStorage extends Storage{
    public function __construct() {
        parent::__construct(new JsonIO('comments.json'));
    }

    public function getTeamCommentsById($id) {
        $comments = $this->findAll(['teamid' => $id]);
        return $comments;
    }

    public function addComment($author, $text, $teamid) {
        $comment = [
            'author'  => $author,
            'text'    => $text,
            'teamid'  => $teamid
        ];
        return $this->add($comment);
    }

    public function deleteCommentById($id) {
        return $this->delete($id);
    }
}
?>