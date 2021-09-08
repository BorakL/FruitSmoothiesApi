U konzoli otvori direktorijum aplikacije i unutar njega inicijalizuj Compozer.
(Kompozer prvo mora biti downloadovan instaliran u wamp64).
Komanda:
composer init
 
...


name: "BorakL/imeAplikacije",
type: "project",


Što se tiče dependencies, trebaće nam Twig. Dakle u require odeljku pišemo 
"twig/twig": "^1.42"


Nakon što smo spremili ovaj manifest naše aplikacije, dobijamo composer.json, fajl sa unetim podacima, gde ćemo na kraju dodati još jedan property:
"autoload": {
        "psr-4": {
            "App\\" : ""
        }
    }
Kako bi se potrebni fajlovi automatski učitavali da ne moramo svaki put da ih rikvarujemo. Ovde podrazumevamo da nam je koren logičke staze u namespace reč "App" (ali naravno ne mora tako da se zove), kojeg želimo da mapiramo. 
Autoload neće automatski funkcionisati sve dok ne odradimo nešto što se zove dump autoload, to je naredba kojom on na osnovu trenutne strukture fajlova i foldera u našem projektu, kompozer izanalizira šta sve od klasa imamo, i za te klase napravi tzv. autoloader.

composer dump-autoload

U slučaju da dodamo još neki folder sa još nekim namespace-om koji do tada nije postojao, samo treba da pozovemo composer dump-autoload



////////////////////////////////////////////

//KONFIGURACIJE

.htaccess je konfiguracioni fajl Apache veb servera. Koristi se za redirekciju, omogućava/onemogućava pristup stranicama za određene IP adrese, zabrane/dozvole pristupa određenim fajlovima, itd. Nas zanima da je:
RewriteEngine On
RewriteBase /
(pod pretpostavkom da je direktorijum aplikacije smešten u korenskom direktorijumu www).


Configuration.php
Ovde unosimo vrednosti za parametre konekcije na bazu podataka.



////////////////////////////////////////////


//RUTE 

Routes.php
Fajl koji sadrži rute. Ruta se sastoji od paterna, naziva kontrolera i naziva metoda datog kontrolera koji se ovom rutom aktivira. Svaka ruta predstavlja instancu objekta Route, koji se poziva statičkom metodom koja predstavlja jedan od naziva http metoda (get, post, put...)




//////////////// MVC //////////////////


Models
Prvo pravimo metode. Metodi su interfejsi ka tabelama u bazi. Za svaku tabelu u bazi pravi se model, i nazivamo ga isto kao i data tabela sa rečju Model. 
Svaka tabela u bazi kao prvo polje mora imati primarni ključ koji se zove isto kao i i tabela_id.


Controllers
Kad imamo spremljene modele, možemo praviti kontrolere. Oni predstavljaju poslovnu logiku aplikacije. Svaki kontroler se odnosi na neki deo aplikacije, nrp. MainController, UserController, CategoryController...
Npr. MainController bi trebao da predstavlja glavni kontroler, i on ima metod home(), ovaj metod setuje podatke za početnu stranicu naše aplikacije.
Recimo da na startu želimo da ispišemo nazive svih kategorija proizvoda koje prodajemo na našoj aplikaciji, dakle trebaće nam CategoryModel, pomoću njegovog getAll() metoda fečujemo iz baze sve nazive kategorija i to setujemo kao data rezultat ovog show() metoda MainControllera.
Pa npr. CategoryController bi mogao da ima show() metod za prikaz jedne date kategorije itd.


Views
Svaki kontroler ima zadatak da spremi podatke za prikaz (view). Ovi podaci idu u odgovarajući view, zato unutar foldera View svaki kontroler mora da ima istoimeni folder, a unutar njega, svaki metod, kontrolera koji se poziva iz rute mora da ima istoimeni fajl. To su html fajlovi, koji se kao rezultat renderuju kod korisnika na ekranu. Podatke iz PHP-a nećemo unositi u html preko php tagova i naredbe echo unutar njih, jer tako pravimo špageti kod, već ćemo to raditi pomoću tvig templejta, kako bi bilo preglednije.