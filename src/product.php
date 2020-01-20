<?php 
require_once __DIR__.'/./vendor/autoload.php';
require_once __DIR__.'/./dao_product.php';
use symfony\component\HttpFoundation\Request;

$app = new Silex\Application();
function toJson($resultat,$httpCode=200){
	global $app;
	$retour["products"]= $resultat;
	return $app->json($retour,$httpCode);
}

$app->get('/produits',function(){
	$resultat = Product::findAll();
	return toJson($resultat);
});

$app->get('/produits/{id}',function($id){
	$resultat = Product::find($id);
	return toJson($resultat);
});

$app->post('/produits',function(Request $request){
	if(0 === strpos($request->headers->get('Content-type'),'application/json')){
		$data = json_decode($request->getContent(),true);
		$request->request->replace(is_array($data) ? $data : array());
		$newUser = Product::add($data);
		return toJson($newUser,201);

	}
});


$app->put('/produits/{id}',function(Request $request,$id){
	if(0 === strpos($request->headers->get('Content-type'),'application/json')){
		$data = json_decode($request->getContent(),true);
		$request->request->replace(is_array($data) ? $data : array());
		$resultat = Product::update($data,$id);
		return toJson($resultat);

	}
});

$app->delete('/produits/{id}',function($id){
	$resultat = Product::delete($id);
	return $resultat;
});

$app->get('/fournisseurs',function(){
	$resultat = Product::Fournisseurs();
	return toJson($resultat);
});

$app->run();