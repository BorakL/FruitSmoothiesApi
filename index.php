<?php
require_once 'Configuration.php';
require_once 'vendor/autoload.php';

header('Content-type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');       
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



ob_start();
 
/*
Instanciramo konfiguraciju baze podataka i konekciju na bazu
*/
$databaseConfiguration = new App\Core\DatabaseConfiguration(
    Configuration::DATABASE_HOST,
    Configuration::DATABASE_USER,
    Configuration::DATABASE_PASS,
    Configuration::DATABASE_NAME 
);
$databaseConnection = new App\Core\DatabaseConnection($databaseConfiguration);


/*
Pomoću filter_input() funkcije uzimamo url i http metod
*/
$url = strval(filter_input(INPUT_GET, 'URL'));
$httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
 

/*
Instanciramo ruter i rikvarujemo rute. Potom sve rute dodajemo u ruter, i među njima nalazimo onu koja odgovara zadatom http metodu i url-u. Nakon toga ekstraktujemo argumente (ako su eventualno) prosleđene preko url-a.
*/
$router = new App\Core\Router();
$routes = require_once 'Routes.php';
foreach($routes as $route){
    $router->add($route);
}
$route = $router->find($httpMethod,$url);
$arguments = $route->extractArguments($url);
 

/*
Nakon što smo izvukli rutu, vadimo ime kontrolera (iz te rute). Svaki u 'App\Controllers' direktiroiumu i imenskom stablu. I njegov naziv mora da se završava sa 'Controller', tako da te vrednosti hardkodujemo.
*/
$fullControllerName = '\\App\\Controllers\\' . $route->getControllerName() . 'Controller';
$controller = new $fullControllerName($databaseConnection);


/*
Instanciramo sessionStorage (mehenizam za rad sa sesijama), nakon čega možemo da instanciramo sesiju. Potom u kontroleru setujemo našu sesiju, jer sesijama se može manipulisati isključivo iz kontrolera, zato svaki kontroler treba da ima sesiju.
*/
// $sessionStorageClassName = Configuration::SESSION_STORAGE;
// $sessionStorageConstructorArguments = Configuration::SESSION_STORAGE_DATA;
// $sessionStorage = new $sessionStorageClassName(...$sessionStorageConstructorArguments);
// $session = new \App\Core\Session\Session($sessionStorage, Configuration::SESSION_LIFETIME);
// $controller->setSession($session);
// $controller->getSession()->reload();


/*
Pre nego što se pozove metod kontrolera iz date rute, mora prvo da se pozove metod __pre(). On pripada svakom kontroleru, jer je definisan u Core controller, ali je prazan, tako da se neće ništa desiti. Međutim, oni delovi sajta, tj. oni kontroleri koji zahtevaju da se prethodno obavi login, ne ekstenduju 
CoreController direktno već prvo nasleđuju neki od Role kontrolera, a imaju definisan __pre() metod (znači precrtavaju onaj prazan __pre() metod iz CoreControllera), i unutar njega vrši se neka vrsta autorizacije korisnika, proverom sesije, ili proverom validacije jwt tokena. Ako korisnik nije validan, (nije prethodno registrovan i/ili logovan) biće redirektovan na stranicu za login.  
*/
$controller->__pre();


/*
POZIVAMO DATI METOD, DATOG KONTROLERA, IZ ODABRANE RUTE, I PROSLEĐUJEMO ARGUMENTE (UKOLIKO POSTOJE)
*/
call_user_func_array([$controller, $route->getMethodName()], $arguments);



/*
Metod kontrolera koji se upravo izvršio je možda nešto radio sa sesijom, nešto je obrisao, ili je getovao unutar data objekta sesije, koji trenutno postoji samo u memoriji koja će se u sledećem momentu obrisati nakon izvršanja metoda, a svrha sesije jeste da neke promene mogu da se zapamte na json fajlu, na serveru. Zato moramo izvršiti saveovanje data objekta na json fajl sesije, i to radi metoda save(), sesije koju smo instancirali u osnovnom kontroleru. To je mnogo praktičnije nego da unutar pojedinačnih metoda kontrolera koji rade sa sesijama svaki put pozivamo save().
*/
//$controller->getSession()->save();



/*
Kontroleri sadrže poslovnu logiku aplikacije. Sva svrha pozvanog metoda kontrolera jeste da pripremi neke podatke za prikaz (view). Dobijene podatke setovaće unutar asocijativnog niza, kojeg moramo da getujemo (getData() metodom Core kontrolera).  
*/
$data = $controller->getData();
 
 
 
/*
U slučaju da je prethodno pozvani kontroler bio neki od ApiKontrolera, a to ćemo da proverimo tako što pitamo da li je kontroler instanca od ApiControllera (jer kao i kod privatrnih kontrolera i Api kontroleri ne nasleđuje CoreController direktno). U slučaju da jeste neće biti nikakvog prikaza (nema view komponente), već samo ehujemo json podatak, i u headeru odgovora trebamo da naglasimo da je odgovor tipa application/json, i ovde se index.php fajl završava (komandom exit).
*/
if($controller instanceof \App\Core\ApiController){
    ob_clean();
    echo json_encode($data);
    exit;
}



/*
U slučaju da nije pozvan api već običan kontroler, instaciraće se twig loader, kojem prosleđujemo informaciju da se prikazi nalaze u folderu views. I instancira se twig environment, kome prosleđujemo loader. Na kraju ehujemo twig metodu render() kojoj prosleđujemo html fajl za dati prikaz (pozvani metod kontrolera), kao i podatke za prikaz (koje smo prethodno getovali iz pozvanog kontrolera). Kako ne bi pogrešili u prosleđivanju view-a (html fajla), bitno je da se držimo konvencije da sva view komponenta (view folder) bude podeljena po kontrolerima (za svaki kontroler da se napravi po jedan istoimeni podfolderu u folderu view), a da se svaki konkretan view (html fajl) odnosi na pozvani metod datog kontrolera. 
*/
// $loader = new Twig_Loader_Filesystem("./views");
// $twig = new Twig_Environment($loader, [
//     //"cache" => "./twig-cache", 
//     "cache"=>false,
//     "auto_reload"=>true
// ]);
// echo $twig->render(
//             $route->getControllerName(). '/'. $route->getMethodName(). '.html',
//             $data
//         );