<?php
/**
 * バックドア検出用プログラム
 *
 * @version 0.1
 */

class Sonar
{
	const SUSPECT_SUSPICIOUS_FILENAME = 'suspicious filename';
	const SUSPECT_CAMOUFLAGED = 'camouflaged mime type';
	const SUSPECT_FAILED_TO_OPEN = 'failed to open';
	const SUSPECT_TOO_LONG_LINE = 'too long line';
	const SUSPECT_BASE64_ENCODED = 'base64 encoded';
	const SUSPECT_SUSPICIOUS_PHRASE = 'suspicious phrase';

	private $in_console = false;
	//走査済ディレクトリ [[パス, 内容物の数]]
	private $scanned_dirs = [];
	//疑わしいファイル [[パス, 理由, 詳細]]
	private $suspects = [];

	function __construct()
	{
		if (php_sapi_name() === 'cli') {
			$this->in_console = true;
		}

		$this->seek();

		$this->out();
	}

	private function seek($dir = null)
	{
		if ($dir === null) {
			$dir = './';
		} else {
			$dir = $dir . '/';
		}

		$paths = glob($dir . '*');
		foreach ($paths as $path) {
			if (is_dir($path)) {
				$this->seek($path);
			} else {
				$this->inspect($path);
			}
		}

		//htaccess をチェック
		$htaccess_path = $dir . '.htaccess';
		if (file_exists($htaccess_path)) {
			$this->suspects[] = [$htaccess_path, self::SUSPECT_SUSPICIOUS_FILENAME, null];
		}

		$this->scanned_dirs[] = [$dir, count($paths)];
	}

	private function inspect($path)
	{
		$mime = mime_content_type($path);
		$mime_types = explode('/', $mime);
		$ext = substr($path, strrpos($path, '.') + 1);

		if ($mime_types[0] !== 'text') {
			//バイナリは無視
			return true;
		}

		//無条件で危ないファイル
		if (basename($path) === 'php.ini') {
			$this->suspects[] = [$path, self::SUSPECT_SUSPICIOUS_FILENAME, null];
			return false;
		}

		//拡張子偽装されていたら、即ダウト
		if (!in_array($ext, ['html', 'htm', 'shtml', 'css', 'scss', 'js', 'xml', 'json', 'yaml', 'php', 'pl', 'cgi', 'txt', 'md', 'po', 'pot'])) {
			$this->suspects[] = [$path, self::SUSPECT_CAMOUFLAGED, $mime];
			return false;
		}

		//最初のNバイトをチェック
		$content = @file_get_contents($path, false, null, 0, 10000);
		if (!$content) {
			$this->suspects[] = [$path, self::SUSPECT_FAILED_TO_OPEN, null];
			return false;
		}

		//PHPコードがなければ無視
		if (strpos($content, '<?php') === false) {
			return true;
		}

		//無条件で疑わしいコード
		$suspicious_phrases = [
			"error_reporting(0)",
			"error_reporting(0)",
			"http_response_code(404)",
			"eval(",
			"exec(",
			"max_execution_time",
			"set_time_limit",
			"ignore_user_abort",
		];
		foreach ($suspicious_phrases as $phrase) {
			if (strpos($content, $phrase)) {
				$this->suspects[] = [$path, self::SUSPECT_SUSPICIOUS_PHRASE, $phrase];
				return false;
			}
		}

		//1行でNバイト以上の行があればダウト
		$content = str_replace(["\r\n", "\r"], "\n", $content);
		$lines = explode("\n", $content);
		foreach ($lines as $row => $line) {
			if (strlen($line) >= 500) {
				$this->suspects[] = [$path, self::SUSPECT_TOO_LONG_LINE, ($row + 1)];
				return false;
			}
		}

		//N文字以上連続するBase64文字があればダウト
		if (preg_match('/[0-9a-zA-Z\+\/]{40,}/', $content, $matches)) {
			$encoded = mb_strimwidth($matches[0], 0, 50, '...');
			$this->suspects[] = [$path, self::SUSPECT_BASE64_ENCODED, $encoded];
			return false;
		}

		return true;
	}

	private function out()
	{
		if ($this->in_console) {
			echo join("\n", array_map(function ($suspect) {
				array_unshift($suspect, date('Y-m-d H:i', filemtime($suspect[0])));
				return join(" : ", $suspect);
			}, $this->suspects));

		} else {
			echo '<h1>SUSPICIOUS FILES</h1>';
			foreach ($this->suspects as $suspect) {
				echo sprintf(
					"%s <a href='ad5-sonar.php?path=%s' target='preview'>%s</a> : %s : %s <br>",
					date('Y-m-d H:i', filemtime($suspect[0])),
					$suspect[0],
					$suspect[0],
					$suspect[1],
					$suspect[2],
				);
			}

			echo '<h1>SCANNED DIRS</h1>';
			echo join("<br>", array_map(function ($scanned_dirs) {
				return join(" : ", $scanned_dirs);
			}, $this->scanned_dirs));
		}
	}

}

if (empty($_GET['path'])) {
	error_reporting(1);
	set_time_limit(3600);
	new Sonar();
} else {
	header('Content-Type: text/plain');
	echo file_get_contents($_GET['path']);
}
