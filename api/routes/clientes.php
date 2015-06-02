<?php 
	date_default_timezone_set('UTC');
	$app->group('/clientes', function () use ($app)	{

		// =============================================================
		//
		//  GET /
		//   
		// =============================================================
		$app->get('/', function () use ($app) {

			$clientes = ORM::for_table('clientes')->find_many();
			
			$response = array();
			foreach ($clientes as $key => $value) {
				$response[] = array(
								'id' => $value->id,
								'nombre' => $value->nombre,
								'telefono' => $value->telefono
							);
				
			}		

			$app->response->setStatus(200);
			$app->response->setBody(json_encode($response));

				
		});
		
		$app->options('/', function () use ($app) {
			$app->response->setStatus(204);
			$app->response->setBody(json_encode(array('message' => 'ok')));
		});
		// =============================================================
		//
		//  GET /:id
		//   
		// =============================================================
		$app->get('/:nombre', function ($nombre) use ($app) {

			$cliente = ORM::for_table('clientes')
				->select('clientes.*')
				->where('nombre', $nombre)
				->find_one();

			$response = array(
					'id' => $cliente->id,
					'nombre' => $cliente->nombre,
					'telefono' => $cliente->telefono				
				);
			$app->response->setStatus(200);
			$app->response->setBody(json_encode($response));
				
		});
		
		$app->options('/', function () use ($app) {
			$app->response->setStatus(204);
			$app->response->setBody(json_encode(array('message' => 'ok')));
		});
		// =============================================================
		//
		//  PUT /:id
		//	
		// 
		// =============================================================
			
		// $app->put('/:no_orden', function ($no_orden) use ($app) {

		// 	// validate params
		// 	$params = array();
		// 	$pars = json_decode($app->request->getBody());
		// 	foreach ($pars as $key => $value) {
		// 	 	$params[$key] = $value;
		// 		$params['no_orden'] = $no_orden;
		// 	}
			
		// 	$rules = array(
		// 		'VoBo' => array(true, "string",0 , 19),
		// 		'no_orden' => array(true, "string",0 , 9)
		// 	);
			
		// 	$v = new Validator(json_encode($params), $rules);
		// 	$params = $v->validate();	

		// 	if(count($v->getErrors()) > 0){
		// 		$app->response->setStatus($v->getCode());
		// 		$app->response->setBody(json_encode($v->getErrors()));
		// 		$app->stop();
		// 	}

		// 	// Verificar si existe el número de orden.
		// 	$orden = ORM::for_table('compras')
		// 		->select('compras.*')
		// 		->where('no_orden', $params['no_orden'])
		// 		->find_one();

		// 	if (!$orden) {
		// 		$error = array(
		// 			'succes' => false,
		// 			'message' => 'No existe el no_orden:'. $params['no_orden']
		// 		);
		// 		$app->response->setStatus(400);
		// 		$app->response->setBody(json_encode($error));
		// 		$app->stop();
		// 	}

		// 	$orden->set('status', true);
		// 	$orden->set('VoBo', $params['VoBo']);

		// 	if($orden->save()){
		// 		$response = array(
		// 			'succes' => true,
		// 			'message' => 'Status actualizado'
		// 		);
		// 		$app->response->setStatus(200);
		// 		$app->response->setBody(json_encode($response));
		// 	}
								
		// });

		// $app->options('/:nombre', function () use ($app){
		// 	$app->response->setStatus(204);
		// 	$app->response->setBody(json_encode($response));
		// });

		// =============================================================
		//
		//  POST / crear una nueva venta 
		//  
		// =============================================================
		$app->post('/', function () use($app){

			// validate params
			$rules = array(
				'nombre' => array(true, "string",1,99),
				'telefono' => array(true, "string",1 ,99)				
			);
			
			$v = new Validator($app->request->getBody(), $rules);
			$params = $v->validate();	

			if(count($v->getErrors()) > 0){
				$app->response->setStatus($v->getCode());
				$app->response->setBody(json_encode($v->getErrors()));
				$app->stop();
			}

			$cliente = ORM::for_table('clientes')
				->select('clientes.*')
				->where('nombre', $params['nombre'])
				->find_one();

			if ($cliente) {
				$error = array(
					'succes' => false,
					'message' => 'Ya está registrado el cliente '.$params['nombre']
				);
				
				$app->response->setStatus(400);
				$app->response->setBody(json_encode($error));
				$app->stop();
			}

			$cliente = ORM::for_table('clientes')->create();
		
			$cliente->nombre = $params['nombre'];
			$cliente->telefono = $params['telefono'];

			$cliente->save();

			$response = array(
				'succes' => true,
				'message' => $cliente
			);

			$app->response->setStatus(201);
			$response = $params;
			$app->response->setBody(json_encode($response));
			$app->stop();
						
		});
			
		$app->options('/', function () use ($app){
			$app->response->setStatus(204);
			$app->response->setBody(json_encode(array('message' => 'ok')));
		});	
	 
	});
 ?>

