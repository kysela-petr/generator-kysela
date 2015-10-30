<?php
/**
 * Zavislosti (jsou pribalene):
 *
 * Nette/Utils/Strings.php
 * Nette/common/exceptions.php
 * Nette/common/Object.php
 * Nette/common/Image.php


 *
 * Povinne parametry volani:
 *
 * file=path         Fyzicke umisteni souboru
 *                   min.php nema dovoleno se divat do nadrazeneho adresare
 *                   Nebo se muze jednat o cestu do webu http://..., https://
 *
 * Nepovinne parametry:
 *
 * [width|w]=[0-9]   Miniatura bude mit nejvyse zadanou sirku (px)
 * [height|h]=[0-9]  Miniatura bude mit nejvyse zadanou vysku (px)
 * exact             Miniatura bude mit presne zadane rozmery.
 *                   Vyzaduje "width" i "height"
 *                   Fotka se nezdeformuje, ale je vyrezana z prostred
 *                   Pokud jsou rozmery vetsi nez fotka, je fotka zvetsena
 * topcut            Uplatnuje se pouze pri pouziti "exact", vyrez neni
 *                   z prostred, ale z vrchu fotky (kde je vetsinou hlava)


 *
 * Kombinace parametru:
 *
 *                   Pokud je zadana sirka i vyska, obrazek se zmensuje
 *                   proporcialne. Maximalne do zadanych rozmeru
 *
 *                   Pokud je zadana sirka bez vysky nebo naopak,
 *                   druhy rozmer je proporcionalne dopocitan.
 *
 *                   Pokud je nektery z parametru vetsi nez rozmer fotky
 *                   a neni pouzito "exact", fotka se nezvetsuje
 *
 *                   Pokud pri pouziti "exact" chybi sirka nebo vyska,
 *                   vraci se 404


 *
 * Priklady:
 * min.php?file=/photos/28/bau10.jpg
 *  vrati se originalni fotka
 *
 * min.php?file=/photos/28/bau10.jpg&h=444
 *  fotka bude mit vysku 444, sirka se dopocita. Mensi fotka se nezvetsuje
 *
 * min.php?file=/photos/28/bau10.jpg&w=100
 *  fotka bude mit sirku 100, vyska se dopocita. Mensi fotka se nezvetsuje
 *
 * min.php?file=/photos/28/bau10.jpg&w=900&h=100
 *  fotka bude mit maximalni rozmery 900x100, ale zachovava si proporce
 *
 * min.php?exact&file=/photos/12/MSJ-Tomas-Portyk-zavod-2.jpg&w=940&h=415
 *  vysledna miniatura bude mit presne rozmery 940x415
 *
 * min.php?exact&topcut&file=/photos/26/Bauer-Lukas-stribro-29-.JPG&w=120&h=80
 *  vysledna fotka je delana presne pro fotogalerii od Toma
 *  ma rozmery 120x80, vzdy, a je orezavana z vrchu, takze miniatury z fotek
 *  na stojaka maji vetsinou hlavu (vetsinou)
 *
 * min.php?file=http://4.bp.blogspot.com/-yKveRwsRtr0/TvN2DSnnx5I/AAAAAAAABPg/RdODIHP3TD0/s1600/tits.jpg&w=200
 *  stahne ze zadane URL fotku a tu zmensi na maximalni sirku 200px
 *  fotka je z netu stahovana jenom jednou, pak uz se zmensuje z lokalni slozky
 *


 *
 * WTF efekty:
 *
 * Vsechno, co se zmensilo je ukladano do RESIZED_DIR a NIKDY to neni automaticky mazano.
 * V nazvu cache souboru se nicmene uklada cas posledni modifikace souboru,
 * takze je vracen aktualni obsah.
 *
 * Fotky z netu jsou stahovany pri kazdem NOVEM pozadavku na zmenseni.
 * Co je v tempu, se vrati z tama, pokud v tempu neexistuje, stahne se znovu.
 *
 * min.php nema dovoleno se divat do nadrazeneho adresare.
 * Jen do adresaru stejne urovne.
 *
 * Do slozky temp musi mit script pravo na zapis.
 *
 */

const MIN_CONFIG_FILE = 'min.config.php';

if (is_file(MIN_CONFIG_FILE)) {
	require_once(MIN_CONFIG_FILE);
}

/**
 * Nadefinuje vychozi hodnotu konstanty
 * @param string $name
 * @param mixed $value
 */
function defineDefault($name, $value) {
	if (!defined($name)) {
		define($name, $value);
	}
}

defineDefault('NETTE_DIR', './../vendor/nette'); //pokud mame Nette, muzem navazat na originalni zdroj
defineDefault('RESIZED_DIR', __DIR__ . '/cache'); //slozka, kde se ukladaji miniatury
defineDefault('DIR_FOR_REMOTE', './remote'); //slozka, kde se ukladaji vzdalene soubory, bude podslozkou v RESIZED_DIR
defineDefault('MAX_WIDTH', 2000);
defineDefault('MAX_HEIGHT', 2000);

require_once(NETTE_DIR . '/utils/src/Utils/Strings.php');
require_once(NETTE_DIR . '/utils/src/Utils/Callback.php');

umask(0);

/**
 * Includuje tridu pro obrazky
 */
function requireImageClass() {
	require_once(NETTE_DIR . '/utils/src/Utils/exceptions.php');
	require_once(NETTE_DIR . '/utils/src/Utils/Object.php');
	require_once(NETTE_DIR . '/utils/src/Utils/Image.php');
}

/**
 * Odesila 404 a konci script
 */
function send404() {
	header("HTTP/1.0 404 Not Found", null, 404);
	die();
}

/**
 * Odesle hotovy souboru
 * @param string $file
 */
function sendFile($file) {
	if (!is_file($file))
		send404();

	$fileModTime = max(filemtime($file), filectime($file));

	$headers = getRequestHeaders();

	if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $fileModTime)) {
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileModTime) . ' GMT', true, 304);
	} else {
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $fileModTime) . ' GMT', true, 200);
		header('Content-type: image/jpeg');
		header('Content-transfer-encoding: binary');
		header('Content-length: ' . filesize($file));
		readfile($file);
	}
	exit;
}

/**
 * Vrati globalni promennou
 * @param string|array $keys Muze byt i pole - zkusi vsechny klice a az ptom hodi chybu
 * @param bool $endScript Ukoncit script pri nenalezeni
 * @return string
 */
function getGlobal($keys, $endScript = true) {
	if (!is_array($keys))
		$keys = array($keys);
	foreach ($keys as $key) {
		if (isset($_GET[$key]))
			return $_GET[$key];
	}
	if ($endScript)
		send404();
	return null;
}

/**
 * Odseka mezery a lomitka
 * @param string $s
 * @return string
 */
function slashTrim($s) {
	return trim($s, ". \t\n\r\0\x0B\xC2\xA0/\\");
}

/**
 * Srovna nazev souboru
 * @param string $filename
 * @return type
 */
function normalizeFilename($filename) {
	$step1 = preg_replace('~\\.+~', '.', $filename);
	$step2 = preg_replace('~\?.*$~', null, $step1);
	$step3 = slashTrim($step2);
	if (!isLocalFile($step3))
		return $step3;
	return './' . $step3;
}

/**
 * Zkontroluje, zda je dotaz na soubor, ktery nemuze skodit
 * @param string $filename
 */
function checkFilename($filename) {
	//musi byt podslozka
	if (strpos($filename, '/') === false && strpos($filename, '\\') == false)
		send404();
	//nesmi byt PHP soubor
	if (preg_match('~\\.php$~', $filename))
		send404();
}

/**
 * vraci cestu k souboru
 * @return string
 */
function getFile() {
	return normalizeFilename(getGlobal('file'));
}

/**
 * Vrati sirku
 * @return int|null
 */
function getWidth() {
	$val = getGlobal(array('w', 'width'), false);
	$sanitized = $val === null ? null : (int) $val;
	if ($sanitized > MAX_WIDTH) {
		$sanitized = MAX_WIDTH;
	}
	return $sanitized;
}

/**
 * Vrati vysku
 * @return int|null
 */
function getHeight() {
	$val = getGlobal(array('h', 'height'), false);
	$sanitized = $val === null ? null : (int) $val;
	if ($sanitized > MAX_HEIGHT) {
		$sanitized = MAX_HEIGHT;
	}
	return $sanitized;
}

/**
 * Ma mit vysledek presne rozmery?
 * @return bool
 */
function getExact() {
	return array_key_exists('exact', $_GET);
}

/**
 * Ma se vysledek orezat pouze z vrchu?
 * @return bool
 */
function getTopCut() {
	return array_key_exists('topcut', $_GET);
}

/**
 * Existuje soubor?
 * @param string $file
 * @param bool $endScript
 * @return boolean
 */
function existsFile($file, $endScript = true) {
	if (is_file($file))
		return true;
	if ($endScript)
		send404();
	return false;
}

/**
 * Vraci casove razitko souboru
 * @param string $file
 * @return int
 */
function fileTimestamp($file) {
	return filectime($file) . '_' . filemtime($file);
}

/**
 * Vrati nazev zmenseneho souboru pro libovolny soubor
 * @param type $file
 * @param type $height
 * @param type $width
 * @param type $exact
 * @param bool $topCut
 * @return type
 */
function getBaseResizedFilename($file, $height, $width, $exact, $topCut) {
	$dir = RESIZED_DIR . '/' . slashTrim(dirname($file));
	if (!is_dir($dir))
		mkdir($dir, 0777, true);
	$filename = slashTrim(basename($file));
	$flag = ($exact ? 'e_' : '') . ($topCut ? 'tc_' : '');
	$timestamp = existsFile($file, false) ? fileTimestamp($file) . '_' : '';
	return $dir . '/' . $timestamp . $flag . $height . '_' . $width . '_' . $filename;
}

/**
 * Vrati nazev zmenseneho lokalniho souboru
 * @param string $file
 * @param int $height
 * @param int $width
 * @param bool $exact
 * @param bool $topCut
 * @return string
 */
function getResizedFilename($file, $height, $width, $exact, $topCut) {
	if ($height === null && $width === null)
		return $file;
	return getBaseResizedFilename($file, $height, $width, $exact, $topCut);
}

/**
 * Vrati nazev zmenseneho souboru stazeneho z ciziho serveru
 * @param string $file
 * @param int $height
 * @param int $width
 * @param bool $exact
 * @param bool $topCut
 * @return string
 */
function getRemoteResizedFilename($file, $height, $width, $exact, $topCut) {
	$file = DIR_FOR_REMOTE . '/' . Nette\Utils\Strings::webalize($file, '.');
	return getBaseResizedFilename($file, $height, $width, $exact, $topCut);
}

/**
 * Ma se vratit originalni soubor?
 * @param string $file
 * @param int $height
 * @param int $width
 * @param int $flags
 * @return bool
 */
function shouldSendOriginal($file, $height, $width, $flags) {
	if ($flags)
		return false;

	if ($height === null && $width === null)
		return true;

	$sizes = getimagesize($file);
	if (!$sizes || !is_array($sizes))
		return false;

	$imageWidth = (int) $sizes[0];
	$imageHeight = (int) $sizes[1];

	return $width >= $imageWidth && $height >= $imageHeight;
}

/**
 * Je soubor lokalni?
 * @param string $filename
 * @return bool
 */
function isLocalFile($filename) {
	return !preg_match("~https?://~i", $filename);
}

/**
 * Vrati hlavicky
 * @return array
 */
function getRequestHeaders() {
	if (function_exists("apache_request_headers")) {
		if ($headers = apache_request_headers()) {
			return $headers;
		}
	}
	$headers = array();

	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		$headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
	}

	return $headers;
}

/**
 * Vrati Nette flagy
 * @param bool $exact
 * @return int
 */
function getFlags($exact) {
	$flags = \Nette\Utils\Image::FIT | \Nette\Utils\Image::SHRINK_ONLY;
	if ($exact) {
		$flags = \Nette\Utils\Image::EXACT;
	}
	return $flags;
}

/**
 * Zmensi fotku pomoci Nette a GD
 * @param string $file
 * @param string $newFilename
 * @param int|null $height
 * @param int|null $width
 * @param bool $exact
 * @param bool $topCut
 */
function processNette($file, $newFilename, $height, $width, $exact, $topCut) {
		$image = \Nette\Utils\Image::fromFile($file);

		$flags = getFlags($exact);

		if ($width !== null || $height !== null) {
			if ($topCut && $exact) { // fotka se orezava pouze z vrchu
				$image->resize($width, $height, \Nette\Utils\Image::FILL)->crop('50%', 0, $width, $height);
			} else {
				$image->resize($width, $height, $flags);
			}
		}

		$image->save($newFilename, 100);
}

/**
 * Vyplni obrazek na maximalni sirku a vysku natvrdo zadanou - muze zvetsovat
 * @param \Imagick $image
 * @param int $width
 * @param int $height
 */
function imagickFill(\Imagick $image, $width, $height) {
	list($newWidth, $newHeight) = \Nette\Utils\Image::calculateSize($image->getImageWidth(), $image->getImageHeight(), $width, $height, \Nette\Utils\Image::FILL);
	$image->thumbnailImage($newWidth, $newHeight, true, true);
}

/**
 * Vyrizne cast obrazku
 * @param \Imagick $image
 * @param int|string $left
 * @param int|string $top
 * @param int|string $width
 * @param int|string $height
 */
function imagickCrop(\Imagick $image, $left, $top, $width, $height) {
	list($left, $top, $width, $height) = \Nette\Utils\Image::calculateCutout($image->getImageWidth(), $image->getImageHeight(), $left, $top, $width, $height);
	$image->cropimage($width, $height, $left, $top);
}

/**
 * Zmensi fotku pomoci Imagick
 * @param string $file
 * @param string $newFilename
 * @param int|null $height
 * @param int|null $width
 * @param bool $exact
 * @param bool $topCut
 */
function processImagick($file, $newFilename, $height, $width, $exact, $topCut) {
	$fileRealPath = realpath($file);
	if ($fileRealPath) {
		$file = $fileRealPath;
	}

	$image = new \Imagick($file);

	if ($width !== null || $height !== null) {
		if ($exact) {
			imagickFill($image, $width, $height);
			if ($topCut) {// fotka se orezava pouze z vrchu
				imagickCrop($image, '50%', 0, $width, $height);
			} else {
				imagickCrop($image, '50%', '50%', $width, $height);
			}
		} else {
			$image->thumbnailImage($width, $height, $width && $height);
		}
	}
	$image->writeImage($newFilename);
}

// ------------------------ program -----------------------------------

$file = getFile();

if (isLocalFile($file)) {
	checkFilename($file);
	existsFile($file);
}

$height = getHeight();
$width = getWidth();
$exact = getExact();
$topCut = getTopCut();
if (isLocalFile($file)) {
	$newFilename = getResizedFilename($file, $height, $width, $exact, $topCut);
} else {
	$newFilename = getRemoteResizedFilename($file, $height, $width, $exact, $topCut);
}
if (!existsFile($newFilename, false)) {
	try {


		if (shouldSendOriginal($file, $height, $width, $exact || $topCut)) {
			sendFile($file);
		}

		requireImageClass();
		if (false && isLocalFile($file) && extension_loaded('imagick')) {
			processImagick($file, $newFilename, $height, $width, $exact, $topCut);
		} else {
			processNette($file, $newFilename, $height, $width, $exact, $topCut);
		}

	} catch (\Exception $e) { //silent night
		send404();
	}
}

sendFile($newFilename);
