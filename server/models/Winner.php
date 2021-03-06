<?php
	namespace Models;

	use Lib\Database;
	use Lib\Hash;
	use Models\Item;
	use Models\Participant;
	use Illuminate\Database\Eloquent\Model as Eloquent;

	class Winner extends Eloquent {	
		/**
	     * The database table used by the model.
	     *
	     * @var string
	     */
	    protected $table = 'winners';
	    /**
	     * The database table primary key used by the model.
	     *
	     * @var string
	     */
	    public $primaryKey  = 'id';
	    /**
	     * Tells Eloquent that our database table doesn't have timestamps
	     *
	     * @var boolean
	     */
	    public $timestamps = false;
		protected $database;
		//Eloquent constructor//
		function __construct() {
			$this->database = Database::getInstance();
		}
		//pick the prize winner for an item//
		public function pickWinner($itemid, $participantid) {
			$winner = new Winner();
			$winner->itemid = $itemid;
			$winner->participantid = $participantid;
			$won = Winner::where('itemid',$itemid)->first();
			//verify that item has not been won already//
			if ($won == null) {
				//insert//
				$saved = $winner->save();
				//verify insertion//
				if($saved) {
					$array = array('success' => true,
						'winner' => $winner);
					return json_encode($array);
				} else {
					$array = array('success' => false,
						'winner' => 'Failed to pick winner');
					return json_encode($array);	
				}
			}
			else {
				$array = array('success' => false,
						'winner' => 'Winner already chosen');
				return json_encode($array);	
			}
		}
		//look up the winner for an item//
		public function lookupWinner($itemid) {
			$winner = Winner::where('itemid', $itemid)->first();
			if ($winner != null) {
				$luckyWinner = Participant::where('id', $winner->participantid)->first();
				$array = array('success' => true,
						'winner' => $luckyWinner);
				return json_encode($array);	
			}
			else {
				$array = array('success' => false,
						'message' => "NO WINNER CHOSEN");
				return json_encode($array);	
			}
		}
	}
?>