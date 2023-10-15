<?php 
class TeamsStorage extends Storage{
    public function __construct() {
        parent::__construct(new JsonIO('teams.json'));
    }

    public function getTeamById($id) {
        $team = $this->findOne(['id' => $id]);
        return $team;
    }
}
?>