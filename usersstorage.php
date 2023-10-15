<?php
class UsersStorage extends Storage{
    public function __construct() {
        parent::__construct(new JsonIO('users.json'));
    }

    public function getUserNameById($id) {
        $user = $this->findOne(['id' => $id]);
        return $user;
    }
}
?>