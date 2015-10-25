<?php

require('../vendor/autoload.php');
include 'Firebase.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$app = new Silex\Application();
$app['debug'] = true;

$app['FireBase'] = new Firebase();

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => 'php://stderr',
));

//add
//edit
//delete
//update

//
//$dbopts = parse_url(getenv('DATABASE_URL'));
//$app->register(new Herrera\Pdo\PdoServiceProvider(),
//    array(
//        'pdo.dsn' => 'pgsql:dbname=' . ltrim($dbopts["path"], '/') . ';host=' . $dbopts["host"],
//        'pdo.port' => $dbopts["port"],
//        'pdo.username' => $dbopts["user"],
//        'pdo.password' => $dbopts["pass"]
//    )
//);

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Our web handlers

$app->get('/', function () use ($app) {

    $entries = $app['FireBase']->getEntries();

    $app['monolog']->addDebug('logging output.');

    return $app['twig']->render('all.twig', array(
        'entries' => $entries,
        'firebaseURL' => getenv('DEFAULT_URL'),
        'fields' => $app['FireBase']->getFields()
    ));
});


$app->get('/fest/{id}', function ($id) use ($app) {

    $festy = $app['FireBase']->getEntry($id);

    if (!$festy) {
        $error = array('message' => 'data not found.');

        return $app->json($error, 404);
    }

    return $app['twig']->render('edit.twig', array(
        'festy' => $festy,'firebaseURL' => getenv('DEFAULT_URL')
    ));
});

$app->get('/festadd/', function () use ($app) {

    $fields = $app['FireBase']->getEditFields();
    return $app['twig']->render('add.twig', array(
        'fields' => $fields,'firebaseURL' => getenv('DEFAULT_URL')
    ));
});



$app->post('/add', function (Request $request) use($app) {

    $fields = $app['FireBase']->getEditFields();

    $data = array();

    foreach($fields as $field => $dataType){
        $data[$field] = $request->get($field);
    }

    $app['FireBase']->push('',$data);

    return $app->redirect('/');

});


//$app->get('/db/', function() use($app) {
//  $st = $app['pdo']->prepare('SELECT name FROM test_table');
//  $st->execute();
//
//  $names = array();
//  while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
//    $app['monolog']->addDebug('Row ' . $row['name']);
//    $names[] = $row;
//  }
//
//  return $app['twig']->render('database.twig', array(
//    'names' => $names
//  ));
//});


$app->run();
