Hej Michael<br /><br /><div class="col-6-12 eg0">
Jeg har så vidt muligt prøvet at holde mig indenfor MVC design princippet. Selve ”frameworket” (hvis man kan kalde det det) består af en default mappe i roden indeholdende filer med klasserne Model, View og Controller samt findAppParts.php. Disse filer indeholder ikke applikations specifikke funktioner, men kun muligheden for at hente data fra Model ind i Controlleren og vise disse ved at loade et view/template med View klassen. View og Model snakker ikke sammen på noget tidspunkt, alt går igennem Controlleren. I Model klassen ligger bla. database connection delen(PDO).
<br /><br />
findAppParts.php indeholder en funktion der sørger for at finde de applications specifikke klasser ud fra url’en og samle de returnerede views i en content variabel der bliver tilgængelig på index.php siden. F.eks vil url’en http://login.geek911.dk/auth/register kalde en AuthController klasse og en funktion i AuthController klassen der hedder registerAction. Derudover kan man tilføje så mange dele i url som man har lyst til, hvilket bliver lavet om til funktions parametre til registerAction funktionen.
<br /><br />
I roden af min app folder har jeg en AppController klasse der default extender Controller klassen og som i bund og grund står for at strikke head sektionen i en HTML side sammen samt et header og footer view i hhv. toppen og bunden af min side. Med andre ord kan man specificere globale views, som skal gå igen uanset hvilken side/app controller man arbejder med. Her giver jeg desuden mulighed for at definere default javascript/css filer til head sektionen samt tilføje nye og desuden definere en titel på siden (man kunne udvide med metatags af forskellig art og minifie alle filerne til en samlet fil)
<br /><br />
I roden af min app folder har jeg udover en config.php (der bla. definere en default controller klasse til at lave mit ”home” view, som vises når der ikke er defineret nogen Controller fra url’en) tre mapper: models, views og controllers. I controllers mappen ligger AuthControlleren der er selve login systemet. Klassen indeholder funktioner der er navngivet efter det brugeren skal igennem for at oprette en konto, logge ind, rekvirere et nyt password og logge ud mm.  
</div>
<div class="col-6-12">
I Min AuthModel klasse varetager jeg alle database operationerne som sender data/respons tilbage til AuthControlleren. Ideen er at mine views, der er tilknyttet AuthControlleren, ligger i en mappe der hedder det samme som kontrolleren, dvs. auth. Dette giver en logisk opdeling af views mappen der sikkert ender med at blive omfattende og som ellers ville blive bøvlet at finde rundt i.
<br /><br />
Hele siden er som sådan funktionel uden javascript. Men i min AppController Classe har jeg i __construct funktion defineret at der default skal kaldes en javascript fil(auth.js) der ligger i auth  mappen i view i en mappe kaldet _js. Den laver nogle nogle ajax modals/vinduer til login formen samt forget password formen bundet op på css class definitioner (bla. .loginBtn). Ajax kaldende ved login forsøg trækker på de samme funktioner i AuthControlleren der håndterer login forsøg uden ajax kald. Der sendes dog en ekstra parameter til funktionerne, der definere det er et ajax kald, så jeg kan få mit respons i json format. 
<br /><br />
Vedh. er en zip fil med alle filerne og det hele kan ses her, sammen med en MVC-info kasse øverst tv., hvor man kan følge med i hvilken Contoller og Action der er i spil: http://login.geek911.dk/
<br /><br />
Jeg har prøvet at navngive tingende logisk og tror det burde være til at finde rundt i hvad der sker, men hvis i synes jeg skal kommentere min kode så skal jeg lige bruge en weekend mere, men vil selvfølgeligt gerne forklare alt, hvis der er noget i mangler svar på!
<br /><br />
Mvh
Claus
</div>

