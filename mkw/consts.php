<?php

namespace mkw;

class consts
{

    const ASZFPDFName = 'aszf.pdf';

    const Uitheme = 'uitheme';

    const Tulajcrc = 'tulajcrc';

    const DBVersion = 'dbversion';

    const Valutanem = 'valutanem';
    const WebshopValutanem = 'webshopvalutanem';
    const WebshopValutanem2 = 'webshopvalutanem2';
    const WebshopValutanem3 = 'webshopvalutanem3';
    const WebshopValutanem4 = 'webshopvalutanem4';
    const WebshopValutanem5 = 'webshopvalutanem5';
    const KezdoTermekKategoria = 'kezdotermekkategoria';
    const KezdoTermekKategoria2 = 'kezdotermekkategoria2';
    const KezdoTermekKategoria3 = 'kezdotermekkategoria3';
    const KezdoTermekKategoria4 = 'kezdotermekkategoria4';
    const KezdoTermekKategoria5 = 'kezdotermekkategoria5';
    const Raktar = 'raktar';
    const Fizmod = 'fizmod';
    const UtanvetFizmod = 'utanvetfizmod';
    const KeszpenzFizmod = 'keszpenzfizmod';
    // az automatikusan képzett pénztárbizonylat pénztára és jogcíme
    // (a funkciót bizonylattípusonként a bizonylattipus.autopenztarbizonylat mező kapcsolja be)
    const AutoPenztarbizonylatPenztar = 'autopenztarbizonylatpenztar';
    const AutoPenztarbizonylatJogcim = 'autopenztarbizonylatjogcim';
    // a banktranzakciókból automatikusan képzett bankbizonylat tételeinek jogcíme
    // (banktranzakcioController::generateBankbizonylat)
    const AutoBankbizonylatJogcim = 'autobankbizonylatjogcim';
    // a GLS utánvétekből képzett bankbizonylat saját bankszámlája – ide utal a futárszolgálat
    // (glsutanvetController::generateBankbizonylat)
    const UtanvetBankszamla = 'utanvetbankszamla';
    // a banki tranzakció-import legutóbb választott formátuma – a feltöltő képernyő
    // ezt kínálja fel alapértelmezésként (banktranzakcioController::IMPORTFORMATUMOK kulcsa)
    const LastBankiFormatum = 'lastbankiformatum';
    const Szallitasimod = 'szallitasimod';
    const Arsav = 'arsav';
    const ShowTermekArsav = 'showtermekarsav';
    const ShowTermekArsavValutanem = 'showtermekarsavvalutanem';
    const Esedekessegalap = 'esedekessegalap';
    const Autologoutmin = 'autologoutmin';
    const PagecacheRoutes = 'pagecacheroutes';
    const ImportNewKatId = 'importnewkatid';
    const Logo = 'logo';
    const Locale = 'locale';
    const Locale2 = 'locale2';
    const Locale3 = 'locale3';
    const Locale4 = 'locale4';
    const Locale5 = 'locale5';
    const NullasAfa = 'nullasafa';
    const KiskerCimke = 'kiskercimke';
    const NagykerCimke = 'nagykercimke';
    const FelvetelAlattCimke = 'felvetelalattcimke';
    const FelvetelAlattTipus = 'felvetelalatttipus';
    const SpanyolCimke = 'spanyolcimke';
    const Spanyolorszag = 'spanyolorszag';
    const TeljesitmenyKezdoEv = 'teljesitmenykezdoev';
    const BelsoUzletkoto = 'belsouzletkoto';
    const Off = 'off';
    const Off2 = 'off2';
    const Off3 = 'off3';
    const Off4 = 'off4';
    const Off5 = 'off5';
    const Orszag = 'orszag';
    const Magyarorszag = 'magyarorszag';
    const Watermark = 'watermark';

    const Tulajnev = 'tulajnev';
    const Tulajirszam = 'tulajirszam';
    const Tulajvaros = 'tulajvaros';
    const Tulajutca = 'tulajutca';
    const Tulajadoszam = 'tulajadoszam';
    const Tulajeuadoszam = 'tulajeuadoszam';
    const Tulajeorinr = 'tulajeorinr';
    const Tulajkisadozo = 'tulajkisadozo';
    // alanyi adómentes tulajdonos: a kimenő bizonylatokon csak AAM nav típusú ÁFA kulcs lehet
    const Tulajalanyiafamentes = 'tulajalanyiafamentes';
    const Tulajegyenivallalkozo = 'tulajegyenivallalkozo';
    const Tulajevnev = 'tulajevnev';
    const Tulajevnyilvszam = 'tulajevnyilvszam';
    const Tulajjovengszam = 'tulajjovengszam';
    const Tulajpartner = 'tulajpartner';
    const TulajKontaktNev = 'tulajkontaktnev';
    const TulajKontaktEmail = 'tulajkontaktemail';
    const TulajKontaktTelefon = 'tulajkontakttelefon';
    const ProgramNev = 'programnev';

    const EmailFrom = 'emailfrom';
    const EmailReplyTo = 'emailreplyto';
    const EmailBcc = 'emailbcc';
    const EmailStatuszValtas = 'emailstatuszvaltas';

    const Oldalcim = 'oldalcim';
    const Seodescription = 'seodescription';
    const Feedhirtitle = 'feedhirtitle';
    const Feedhirdescription = 'feedhirdescription';
    const Feedblogtitle = 'feedblogtitle';
    const Feedblogdescription = 'feedblogdescription';
    const Feedtermektitle = 'feedtermektitle';
    const Feedtermekdescription = 'feedtermekdescription';
    const Feedhirdb = 'feedhirdb';
    const Feedtermekdb = 'feedtermekdb';
    const Feedblogdb = 'feedblogdb';

    const Jpgquality = 'jpgquality';
    const Pngquality = 'pngquality';
    const Korhintaimagesize = 'korhintaimagesize';
    const Miniimgpost = 'miniimgpost';
    const Smallimgpost = 'smallimgpost';
    const Mediumimgpost = 'mediumimgpost';
    const Bigimgpost = 'bigimgpost';
    const I2000imgpost = 'i2000imgpost';
    const I400imgpost = 'i400imgpost';
    const Miniimagesize = 'miniimagesize';
    const Smallimagesize = 'smallimagesize';
    const Mediumimagesize = 'mediumimagesize';
    const Bigimagesize = 'bigimagesize';

    const Fooldalchangefreq = 'fooldalchangefreq';
    const Fooldalprior = 'fooldalprior';
    const Kategoriachangefreq = 'kategoriachangefreq';
    const Kategoriaprior = 'kategoriaprior';
    const Termekchangefreq = 'termekchangefreq';
    const Termekprior = 'termekprior';
    const Statlapchangefreq = 'statlapchangefreq';
    const Statlapprior = 'statlapprior';
    const Blogposztchangefreq = 'blogposztchangefreq';
    const Blogposztprior = 'blogposztprior';
    const Fooldalajanlotttermekdb = 'fooldalajanlotttermekdb';
    const Fooldalnepszerutermekdb = 'fooldalnepszerutermekdb';
    const Fooldalakciostermekdb = 'fooldalakciostermekdb';
    const Termeklapnepszerutermekdb = 'termeklapnepszerutermekdb';
    const Kiemelttermekdb = 'kiemelttermekdb';
    const Hasonlotermekdb = 'hasonlotermekdb';
    const Hozzavasarolttermekdb = 'hozzavasarolttermekdb';
    const Hasonlotermekarkulonbseg = 'hasonlotermekarkulonbseg';

    const ErtekelesKeroSablon = 'ertekeleskerosablon';
    const ErtekelesErtesitoSablon = 'ertekelesertesitosablon';
    const ElallasElismervenySablon = 'elallaselismervenysablon';
    const SzallitasiFeltetelSablon = 'szallfeltsablon';
    const SzamlalevelSablon = 'szamlalevelsablon';
    const KonyvelolevelSablon = 'konyvelolevelsablon';
    const KonyveloEmail = 'konyveloemail';

    const Arfilterstep = 'arfilterstep';
    const Fooldalhirdb = 'fooldalhirdb';
    const Termeklistatermekdb = 'termeklistatermekdb';
    const Termekoldalcim = 'termekoldalcim';
    const Termekseodescription = 'termekseodescription';
    const Katoldalcim = 'katoldalcim';
    const Katseodescription = 'katseodescription';
    const Markaoldalcim = 'markaoldalcim';
    const Markaseodescription = 'markaseodescription';
    const Hirekoldalcim = 'hirekoldalcim';
    const Hirekseodescription = 'hirekseodescription';
    const Blogoldalcim = 'blogoldalcim';
    const Blogseodescription = 'blogseodescription';
    const Blogposztdb = 'blogposztdb';
    const BlogposztTermeklapdb = 'blogposzttermeklapdb';
    const BlogposztKategoriadb = 'blogposztkategoriadb';
    const GAFollow = 'gafollow';
    const GMapsApiKey = 'gmapsapikey';
    const FBAppId = 'fbappid';

    const MarkaCs = 'markacs';
    const DENCs = 'dencs';
    const EpitoelemszamCs = 'epitoelemszamcs';
    const CsomagoltmeretCs = 'csomagoltmeretcs';
    const AjanlottkorosztalyCs = 'ajanlottkorosztalycs';
    const KuponElotag = 'kuponelotag';
    const VasarlasiUtalvanyTermek = 'vasarlasiutalvanytermek';
    const AdminRole = 'adminrole';
    const TermekfeltoltoRole = 'termekfeltoltorole';

    const UjtermekJelolo = 'ujtermekjelolo';
    const Top10Jelolo = 'top10jelolo';
    const AkcioJelolo = 'akciojelolo';
    const IngyenszallitasJelolo = 'ingyenszallitasjelolo';

    const SzallitasiKtg1Tol = 'szallitasiktg1tol';
    const SzallitasiKtg1Ig = 'szallitasiktg1ig';
    const SzallitasiKtg1Ertek = 'szallitasiktg1ertek';
    const SzallitasiKtg2Tol = 'szallitasiktg2tol';
    const SzallitasiKtg2Ig = 'szallitasiktg2ig';
    const SzallitasiKtg2Ertek = 'szallitasiktg2ertek';
    const SzallitasiKtg3Tol = 'szallitasiktg3tol';
    const SzallitasiKtg3Ig = 'szallitasiktg3ig';
    const SzallitasiKtg3Ertek = 'szallitasiktg3ertek';
    const SzallitasiKtgTermek = 'szallitasiktgtermek';
    const UtanvetKtgTermek = 'utanvetktgtermek';
    const KoltsegTermek = 'koltsegtermek';
    /** a NAV-ból utoljára importált bejövő számla időszak vége (innen indul a következő import) */
    const KoltsegszamlaImportDatum = 'koltsegszamlaimportdatum';

    const BizonylatStatuszFuggoben = 'bizonylatstatuszfuggoben';
    const BizonylatStatuszTeljesitheto = 'bizonylatstatuszteljesitheto';
    const BizonylatStatuszBackorder = 'bizonylatstatuszbackorder';
    const BackorderStock = 'backorderstock';
    const MegrendelesFilterStatuszCsoport = 'megrendelesfilterstatuszcsoport';

    const RLBUtolsoSzamlaszam = 'rlbutolsoszamlaszam';
    const RLBCSVUtolsoSzamlaszam = 'rlbcsvutolsoszamlaszam';
    const PDFUtolsoSzamlaszam = 'pdfutolsoszamlaszam';
    const PDFUtolsoEsetiSzamlaszam = 'pdfutolsoesetiszamlaszam';
    const XMLUtolsoSzamlaszam = 'xmlutolsoszamlaszam';
    const XMLUtolsoEsetiSzamlaszam = 'xmlutolsoesetiszamlaszam';

    const AKTrustedShopApiKey = 'aktrustedshopapikey';

    const ValtozatTipusSzin = 'valtozattipusszin';
    const ValtozatTipusMeret = 'valtozattipusmeret';

    const FoxpostSzallitasiMod = 'foxpostszallitasimod';
    const FoxpostApiURL = 'foxpostapiurl';
    const FoxpostUsername = 'foxpostusername';
    const FoxpostPassword = 'foxpostpassword';
    const FoxpostApiVersion = 'foxpostapiversion';
    const Foxpostv2ApiURL = 'foxpostv2apiurl';
    const Foxpostv2Username = 'foxpostv2username';
    const Foxpostv2Password = 'foxpostv2password';
    const Foxpostv2ApiKey = 'foxpostv2apikey';

    const GLSApiURL = 'glsapiurl';
    const GLSClientNumber = 'glsclientnumber';
    const GLSUsername = 'glsusername';
    const GLSPassword = 'glspassword';
    const GLSTerminalURL = 'glsterminalurl';
    const GLSParcelLabelDir = 'glsparcellabeldir';
    const GLSSM2 = 'glssm2';

    const FedexApiURL = 'fedexapiurl';
    const FedexDocApiURL = 'fedexdocapiurl';
    const FedexApiKey = 'fedexapikey';
    const FedexSecretKey = 'fedexsecretkey';
    const FedexAccountNumber = 'fedexaccountnumber';
    const FedexParcelLabelDir = 'fedexparcellabeldir';
    const FedexServiceType = 'fedexservicetype';
    const FedexPackagingType = 'fedexpackagingtype';
    const FedexPickupType = 'fedexpickuptype';
    const FedexLabelStockType = 'fedexlabelstocktype';
    const FedexDefaultSuly = 'fedexdefaultsuly';
    const FedexDutiesPaymentType = 'fedexdutiespaymenttype';
    const FedexToken = 'fedextoken';

    const TOFSzallitasiMod = 'tofszallitasimod';
    const GLSSzallitasiMod = 'glsszallitasimod';
    const GLSFutarSzallitasmod = 'glsfutarszallitasimod';
    const FedexSzallitasiMod = 'fedexszallitasimod';

    const ArukeresoExportSzallmod = 'arukeresoexportszallmod';

    const ValtozatSorrend = 'valtozatsorrend';
    const RendezendoValtozat = 'rendezendovaltozat';

    const BizonylatMennyiseg = 'bizonylatmennyiseg';

    const GyartoKreativ = 'gyartokreativ';
    const GyartoDelton = 'gyartodelton';
    const GyartoReintex = 'gyartoreintex';
    const GyartoTutisport = 'gyartotutisport';
    const GyartoMaxutov = 'gyartomaxutov';
    const GyartoLegavenue = 'gyartolegavenue';
    const GyartoNomad = 'gyartonomad';
    const GyartoNika = 'gyartonika';
    const GyartoHaffner24 = 'gyartohaffner24';
    const GyartoEvona = 'gyartoevona';
    const GyartoEvonaXML = 'gyartoevonaxml';
    const GyartoGulf = 'gyartogulf';
    const GyartoSmileebike = 'gyartosmileebike';
    const GyartoCopydepo = 'gyartocopydepo';

    const PathKreativ = 'pathkreativ';
    const PathDelton = 'pathdelton';
    const PathReintex = 'pathreintex';
    const PathTutisport = 'pathtutisport';
    const PathMaxutov = 'pathmaxutov';
    const PathSilko = 'pathsilko';
    const PathBtech = 'pathbtech';
    const PathKress = 'pathkress';
    const PathLegavenue = 'pathlegavenue';
    const PathNomad = 'pathnomad';
    const PathNika = 'pathnika';
    const PathHaffner24 = 'pathhaffner24';
    const PathEvona = 'pathevona';
    const PathNetpresso = 'pathnetpresso';
    const PathSmileebike = 'pathsmileebike';
    const PathCopydepo = 'pathcopydepo';

    const UrlKreativ = 'urlkreativ';
    const UrlKreativImages = 'urlkreativimages';
    const UrlDelton = 'urldelton';
    const UrlNomad = 'urlnomad';
    const UrlNika = 'urlnika';
    const UrlMaxutov = 'urlmaxutov';
    const UrlLegavenue = 'urllegavenue';
    const UrlHaffner24 = 'urlhaffner24';
    const UrlReintex = 'urlreintex';
    const UrlNetpresso = 'urlnetpresso';
    const UrlEvonaXML = 'urlevonaxml';
    const UrlSmileebike = 'urlsmileebike';
    const UrlCopydepoTermek = 'urlcopydepotermek';
    const UrlCopydepoKeszlet = 'urlcopydepokeszlet';

    const KepUrlEvona = 'kepurlevona';

    const ExcludeReintex = 'excludereintex';

    const RunningKreativImport = 'runningkreativimport';
    const RunningDeltonImport = 'runningdeltonimport';
    const RunningReintexImport = 'runningreinteximport';
    const RunningTutisportImport = 'runningtutisportimport';
    const RunningMaxutovImport = 'runningmaxutovimport';
    const RunningLegavenueImport = 'runninglegavenueimport';
    const RunningNomadImport = 'runningnomadimport';
    const RunningNikaImport = 'runningnikaimport';
    const RunningHaffner24Import = 'runninghaffner24import';
    const RunningEvonaImport = 'runningevonaimport';
    const RunningEvonaXMLImport = 'runningevonaxmlimport';
    const RunningGulfImport = 'runninggulfimport';
    const RunningSmileebikeImport = 'runningsmileebikeimport';
    const RunningCopydepoTermekImport = 'runningcopydepotermekimport';
    const RunningCopydepoKeszletImport = 'runningcopydepokeszletimport';

    const MugenraceFooldalKep = 'mugenracefooldalkep';
    const MugenraceFejlecKep = 'mugenracefejleckep';
    const MugenraceFooldalSzoveg = 'mugenracefooldalszoveg';
    const MugenraceLogo = 'mugenracelogo';
    const MugenraceFooterLogo = 'mugenracefooterlogo';
    const MugenraceKatId = 'mugenracekatid';

    const ASZFUrl = 'aszfurl';

    const Webshop1Name = 'webshop1name';
    const Webshop2Name = 'webshop2name';
    const Webshop3Name = 'webshop3name';
    const Webshop4Name = 'webshop4name';
    const Webshop5Name = 'webshop5name';
    const Webshop6Name = 'webshop6name';
    const Webshop7Name = 'webshop7name';
    const Webshop8Name = 'webshop8name';
    const Webshop9Name = 'webshop9name';
    const Webshop10Name = 'webshop10name';
    const Webshop11Name = 'webshop11name';
    const Webshop12Name = 'webshop12name';
    const Webshop13Name = 'webshop13name';
    const Webshop14Name = 'webshop14name';
    const Webshop15Name = 'webshop15name';

    const Webshop2Price = 'Webshop2Price';
    const Webshop2Discount = 'Webshop2Discount';
    const Webshop3Price = 'Webshop3Price';
    const Webshop3Discount = 'Webshop3Discount';
    const Webshop4Price = 'Webshop4Price';
    const Webshop4Discount = 'Webshop4Discount';
    const Web4DefaKatId = 'Web4DefaKatId';
    const Webshop5Price = 'Webshop5Price';
    const Webshop5Discount = 'Webshop5Discount';

    const SZEPFizmod = 'szepkartyafizmod';
    const SportkartyaFizmod = 'sportkartyafizmod';
    const AYCMFizmod = 'aycmfizmod';

    const PenztarZarva = 'penztarzarva';

    const MunkaJelenlet = 'munkajelenlet';

    const JogaJutalek = 'jogajutalek';
    const JogaUresTeremJutalek = 'jogauresteremjutalek';
    const JogaAYCMJutalek = 'jogaaycmjutalek';
    const JogaTanarelszamolasSablon = 'jogatanarelszamolassablon';
    const JogaNemjonsenkiSablon = 'joganemjonsenkisablon';
    const JogaNemjelenteztekelegenTanarnakSablon = 'joganemjelentkeztekelegentanarnaksablon';
    const JogaNemjelentkeztekelegenGyakorlonakSablon = 'joganemjelentkeztekelegengyakorlonaksablon';
    const JogaElegenjelentkeztekTanarnakSablon = 'jogaelegenjelentkeztektanarnaksablon';
    const JogaBejelentkezesKoszonoSablon = 'jogabejelentkezeskoszonosablon';
    const JogaBejelentkezesErtesitoSablon = 'jogabejelentkezesertesitosablon';
    const JogaLemondasKoszonoSablon = 'jogalemondaskoszonosablon';
    const JogaLemondasErtesitoSablon = 'jogalemondasertesitosablon';
    const JogaElmaradasErtesitoSablon = 'jogaelmaradasertesitosablon';
    const JogaElmaradasKonyvelonekSablon = 'jogaelmaradaskonyveloneksablon';
    const JogaOrajegyTermek = 'jogaorajegytermek';
    const JogaBerlet4Termek = 'jogaberlet4termek';
    const JogaBerlet10Termek = 'jogaberlet10termek';
    const JogaBerletKoszonoSablon = 'jogaberletkoszonosablon';
    const JogaBerletFelszolitoSablon = 'jogaberletfelszolitosablon';
    const JogaBerletSzamlazvaSablon = 'jogaberletszamlazvasablon';
    const JogaBerletLefogjarniSablon = 'jogaberletlefogjarnisablon';
    const JogaBerletLejartSablon = 'jogaberletlejartsablon';
    const JogaBerletDatumLejartSablon = 'jogaberletdatumlejartsablon';
    const JogaAllapotfelmeresTipus = 'jogaallapotfelmerestipus';

    const BarionPOSKey = 'barionposkey';
    const BarionAPIVersion = 'barionapiversion';
    const BarionEnvironment = 'barionenvironment';
    const BarionPayeeEmail = 'barionpayeeemail';
    const BarionFizmod = 'barionfizmod';
    const BarionFizetveStatusz = 'barionfizetvestatusz';
    const BarionFizetesrevarStatusz = 'barionfizetesrevarstatusz';
    const BarionRefundedStatusz = 'barionrefundedstatusz';
    const BarionRedirectUrl = 'barionredirecturl';
    const BarionCallbackUrl = 'barioncallbackurl';

    const StripeAPIKey = 'stripeapikey';
    const StripePublishableKey = 'stripepublishablekey';
    const StripeFizmod = 'stripefizmod';
    const StripeFizetveStatusz = 'stripefizetvestatusz';
    const StripeFizetesrevarStatusz = 'stripefizetesrevarstatusz';
    const StripeWebhookSecret = 'stripewebhooksecret';

    // UNAS integráció (lásd docs/unas-integracio.md)
    const UnasApiUrl = 'unasapiurl';
    const UnasApiKey = 'unasapikey';
    // a login-nal kapott token cache-e, JSON: {token, expires, cred}
    const UnasToken = 'unastoken';
    // végpontonkénti órás hívásszámláló, JSON: {ora, db:{végpont: darab}}
    const UnasRateLimit = 'unasratelimit';
    // az inkrementális termékimport kurzora (unix ts), csak hibátlan futás után lép
    const UnasTermekImportCursor = 'unastermekimportcursor';
    // a legutóbbi termékadatbázis-letöltés, JSON: {fajl, ido, jelzes}
    const UnasTermekImportLastDownload = 'unastermekimportlastdownload';
    // a folyamatban lévő sorablakos menet fájlja, JSON: {fajl, ido} – ezt a takarítás nem viheti el
    const UnasTermekImportInProgress = 'unastermekimportinprogress';
    const RunningUnasTermekImport = 'runningunastermekimport';
    // hova mentsük a letöltött képeket (a kepek/ alatt), és milyen URL-lel hivatkozzunk rájuk
    const UnasKepPath = 'unaskeppath';
    const UnasKepUrlPrefix = 'unaskepurlprefix';
    // az alap import nyelve (ISO 639-1), és a _l1 mezőkhöz tartozó nyelv
    const UnasNyelv = 'unasnyelv';
    const UnasNyelvL1 = 'unasnyelvl1';

    // UNAS megrendelés-import és visszaírás (lásd docs/unas-megrendeles-integracio.md)
    const UnasRaktar = 'unasraktar';
    const UnasPartnertipus = 'unaspartnertipus';
    // leképezések JSON-ban, {UNAS azonosító: MKW azonosító} – az UNAS státuszai és módjai
    // boltonként szabadon konfigurálhatók, ezért nem lehet belőlük oszlop
    const UnasStatuszMap = 'unasstatuszmap';
    const UnasFizmodMap = 'unasfizmodmap';
    const UnasSzallmodMap = 'unasszallmodmap';
    // amit a leképezés nem fed le: az UNAS StatusType-ja szerinti négy tartalék
    const UnasStatuszOpenNormal = 'unasstatuszopennormal';
    const UnasStatuszOpenPrepare = 'unasstatuszopenprepare';
    const UnasStatuszCloseOk = 'unasstatuszcloseok';
    const UnasStatuszCloseFault = 'unasstatuszclosefault';
    // kifizetett rendelés (Payment.Status = paid) státusza, ez erősebb a fentieknél
    const UnasFizetveStatusz = 'unasfizetvestatusz';
    // fel nem oldott cikkszám, szállítási / kezelési költség és kedvezmény tételek terméke
    const UnasDefaultTermek = 'unasdefaulttermek';
    // üresen a globális consts::SzallitasiKtgTermek marad
    const UnasSzallitasiKtgTermek = 'unasszallitasiktgtermek';
    const UnasKezelesiKtgTermek = 'unaskezelesiktgtermek';
    const UnasKedvezmenyTermek = 'unaskedvezmenytermek';
    // az UNAS-ban utólag módosított rendelés felülírhatja-e a bizonylat tételeit
    const UnasModositasEngedve = 'unasmodositasengedve';
    // a setOrder küldjön-e státuszértesítő levelet a vásárlónak
    const UnasStatuszEmail = 'unasstatuszemail';
    // mit írunk vissza az UNAS-ba
    const UnasVisszairasStatusz = 'unasvisszairasstatusz';
    const UnasVisszairasSzamla = 'unasvisszairasszamla';
    const UnasVisszairasCsomag = 'unasvisszairascsomag';
    // a rendelés-poller kurzora (unix ts), csak hibátlan futás után lép
    const UnasImportCursor = 'unasimportcursor';
    const UnasUtolsoCron = 'unasutolsocron';

    const SzamlaOrzesAlap = 'szamlaorzesalap';
    const SzamlaOrzesEv = 'szamlaorzesev';

    const RendezvenySablonRegKoszono = 'rendezvenysablonregkoszono';
    const RendezvenySablonDijbekero = 'rendezvenysablondijbekero';
    const RendezvenySablonFizetesKoszono = 'rendezvenysablonfizeteskoszono';
    const RendezvenySablonKezdesEmlekezteto = 'rendezvenysablonkezdesemlekezteto';
    const RendezvenySablonRegErtesito = 'rendezvenysablonregertesito';
    const RendezvenySablonFelszabadultHelyErtesito = 'rendezvenysablonfelszabadulthelyertesito';
    const RendezvenyRegErtesitoEmail = 'rendezvenyregertesitoemail';

    const IdopontfoglalasSablonKoszono = 'idopontfoglalaskoszonoemailsablon';

    const NAVOnlineME1_1Kesz = 'NAVOnlineME1_1Kesz';
    const NAVOnlineVersion = 'NAVOnlineVersion';
    const NAVOnlineEnv = 'NAVOnlineEnv';
    const NAVOnlineErtekhatar = 'NAVOnlineErtekhatar';
    const NAVOnline2_0StartDate = 'NAVOnline2_0StartDate';
    const NAVOnlinePartner3_0Kesz = 'NAVOnlinePartner3_0Kesz';

    const DefaultPartner = 'defaultpartner';
    const DefaultTermek = 'defaulttermek';
    const Boltivevo = 'boltivevo';

    const MPTNGYSzimpoziumTipus = 'mptngyszimpoziumtipus';
    const MPTNGYSzimpoziumEloadasTipus = 'mptngyszimpoziumeloadastipus';
    const MPTNGYKonyvbemutatoTipus = 'mptngykonyvbemutatotipus';
    const MPTNGYDatum1 = 'mptngydatum1';
    const MPTNGYDatum2 = 'mptngydatum2';
    const MPTNGYDatum3 = 'mptngydatum3';

    const MPTNGYSzempont1 = 'mptngyszempont1';
    const MPTNGYSzempont2 = 'mptngyszempont2';
    const MPTNGYSzempont3 = 'mptngyszempont3';
    const MPTNGYSzempont4 = 'mptngyszempont4';
    const MPTNGYSzempont5 = 'mptngyszempont5';

    const MPTNGYRegVisszaigSablon = 'mptngyregvisszaigsablon';
    const MPTNGYJelszoEmlekSablon = 'mptngyjelszoemleksablon';

    const NoMinKeszlet = 'nominkeszlet';
    const NoMinKeszletTermekkat = 'nominkeszlettermekkat';
    const MinKosarErtek = 'minkosarertek';

    const GS1Datasource = 'gs1datasource';
    const GS1DatasourceName = 'gs1datasourcename';

    const Napijelentes2DefaultRaktar = 'napijelentes2defaultraktar';

    const FCMoto = 'fcmoto';
    const MaximoMoto = 'maximomoto';

    public static function getWebshopPriceConst($_webshopNum = null)
    {
        if (is_null($_webshopNum)) {
            $_webshopNum = \mkw\store::getWebshopNum();
        }
        return 'Webshop' . $_webshopNum . 'Price';
    }

    public static function getWebshopDiscountConst($_webshopNum = null)
    {
        if (is_null($_webshopNum)) {
            $_webshopNum = \mkw\store::getWebshopNum();
        }
        return 'Webshop' . $_webshopNum . 'Discount';
    }

}
