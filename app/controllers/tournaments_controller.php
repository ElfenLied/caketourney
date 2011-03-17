<?php
App::import('Controller', 'KOTournaments');
class TournamentsController extends AppController {

	var $name = 'Tournaments';
	
	function report_match($match_id, $player1_score, $player2_score)
	{
	
		//get corresponding tournament
		$match = $this->Tournament->Round->Match->findById($match_id);
		$round = $this->Tournament->Round->findById($match['Round']['id']);
		$tournament_id = $round['Tournament']['id'];
		$tournament = $this->Tournament->findById($tournament_id);
		
		//pass on to the right controller
		if($tournament['Tournament']['typeAlias']==0)
		{
			$KOTournaments = new KOTournamentsController;
			$KOTournaments->ConstructClasses();
			
			$KOTournaments->report_match($match_id,$player1_score,$player2_score);
		}
	}
	function index() {
		$this->Tournament->recursive = 0;
		$this->set('tournaments', $this->paginate());
	}

	function view($id = null) {
		//redirect to right tourney type

		if ($this->Tournament->field('typeAlias') == 0)
		{
			$this->redirect(array('controller'=> 'KOTournaments','action' => 'view',$id));
		}
		if ($this->Tournament->field('typeAlias') == 1)
		{
			$this->redirect(array('controller'=> 'SwissTournaments','action' => 'view',$id));
		}
		/*if (!$id) {
			$this->Session->setFlash(__('Invalid tournament', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('tournament', $this->Tournament->read(null, $id));*/
	}

	function add() {
		if (!empty($this->data)) {
			$this->Tournament->create();
			if ($this->Tournament->save($this->data)) {
				$this->Session->setFlash(__('The tournament has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tournament could not be saved. Please, try again.', true));
			}
		}
		$users = $this->Tournament->User->find('list');
		$this->set(compact('users'));
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid tournament', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Tournament->save($this->data)) {
				$this->Session->setFlash(__('The tournament has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The tournament could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Tournament->read(null, $id);
		}
		$users = $this->Tournament->User->find('list');
		$this->set(compact('users'));
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for tournament', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Tournament->delete($id)) {
			$this->Session->setFlash(__('Tournament deleted', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Tournament was not deleted', true));
		$this->redirect(array('action' => 'index'));
	}
}
?>