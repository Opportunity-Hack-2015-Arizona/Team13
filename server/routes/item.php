<?php
	use Models\Item;
	use Models\Winner;
	use Models\Event;
	use Lib\OAuth2\OAuth2;

	$app->group('/item', function() use($app, $authorize, $resourceServer) {
		$app->post('/', $authorize(), function() use ($app, $resourceServer) {
			$item = new Item();
			$eventid = $app->request->post('eventid');
			$name = $app->request->post('name');
			$description = $app->request->post('description');
			$pathtopic = $app->request->post('pathtopic');
			$storeprice = $app->request->post('storeprice');
			
			$event = new Event();
			$userid = $resourceServer->getAccessToken()->getSession()->getOwnerId();

			if ($event->verifyHost($eventid,$userid)) {
				$json = $item->addItem($eventid, $name, $description, $pathtopic, $storeprice);
				echo $json;
			}
			else {
				echo "WRONG HOST!";
			}

		});

		$app->get('/:eventid/', $authorize(), function($eventid) use ($app, $resourceServer) {
			$item = new Item();
			$event = new Event();
			$userid = $resourceServer->getAccessToken()->getSession()->getOwnerId();

			if ($event->verifyHost($eventid,$userid)) {
				$json = $item->getItems($eventid);
				echo $json;
			}
			else {
				echo "WRONG HOST!";
			}
			
		});

		$app->post('/winner/', $authorize(), function() use ($app, $resourceServer) {
			$winner = new Winner();
			$itemid = $app->request->post('itemid');
			
			$json = $winner->pickWinner($itemid);

			echo $json;
		});

	});
?>