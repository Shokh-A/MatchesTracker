<?php
class MatchesStorage extends Storage{
    public function __construct() {
        parent::__construct(new JsonIO('matches.json'));
    }

    public function getNextFive($from){
        $matches = $this->findAll();
        function date_sort($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        }
        usort($matches, "date_sort");
        return array_slice($matches, (-5*$from)%count($matches), 5);
    }

    public function getTeamMatchesById($id) {
        $allMatches = $this->findAll();
        $matches = [];
        foreach ($allMatches as $match) {
            if ($match['home']['id'] == $id || $match['away']['id'] == $id) array_push($matches, $match);
        }
        return $matches;
    }

    public function modifyDataById($id, $newDate, $score1, $score2) {
        $match = $this->findOne(['id' => $id]);
        $match['date'] = $newDate;
        $match['home']['score'] = $score1;
        $match['away']['score'] = $score2;
        return $this->update($id, $match);
    }
}
?>