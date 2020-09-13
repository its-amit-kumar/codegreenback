<?php

ob_start();

require dirname(__DIR__).'/vendor/autoload.php';



require_once '/var/www/codegreenback.com/Chat/src/socket.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\OriginCheck;
use Ratchet\App;
//use Ratchet\MyApp;
//use Symfony\Component\HttpFoundation\Session\Storage\Handler;
//use MyApp\Socket;
use Ratchet\Session\SessionProvider;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;

/*
$memcache = new Memcached;
$memcache->addServer('localhost', 11211);

$checkedApp = new OriginCheck(new WsServer(new Socket()), array('localhost'));
$checkedApp->allowedOrigins[] = 'localhost';

//$server = new WsServer($checkedApp);

$session = new SessionProvider(
	new App,
        new Handler\MemcachedSessionHandler($memcache)
    );

//$server = IoServer::factory(
//    new HttpServer(
//        $server1
//    ),
//    2260
//);


    $server = new App('localhost');
    $server->route('/sessDemo', $session);
    $server->run();
*/

        $memcache = new Memcached;
    $memcache->addServer('localhost', 11211);

    $session = new SessionProvider(
        new WsServer(new Socket())
      , new Handler\MemcachedSessionHandler($memcache)
    );

//    $checkedApp = new OriginCheck(new WsServer(new Socket()), array('localhost'));
//$checkedApp->allowedOrigins[] = 'localhost';

//$server = IoServer::factory(
//    new HttpServer(
//        $checkedApp
//    ),
//    2260
//);

//$server->run();

    $server = new App('www.codegreenback.com', 8080 , '0.0.0.0');
    $server->route('/sessDemo', $session);

    $server->run();


?>
<?php

// require dirname(__DIR__) . '/vendor/autoload.php';

// require_once '/var/www/html/php/Chat/src/socket.php';



// use Ratchet\Http\OriginCheck;
// use Ratchet\Server\IoServer;
// use Ratchet\Http\HttpServer;
// use Ratchet\WebSocket\WsServer;


//     // $checkedApp = new OriginCheck(new Socket(), array('localhost'));
//     // $checkedApp->allowedOrigins[] = 'http://127.0.0.1';

// $server = IoServer::factory(
//     new HttpServer(
        
//         new OriginCheck(
//             new WsServer( new Socket()),
//             array('127.0.0.1')
//             )
        
//     ),
//     2260
// );

// $server->run();


?>
