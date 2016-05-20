<?php
namespace WebsiteConnect\Framework\Utility;

class FileSystem {

	public static function getFiles($path, $recurse = false, $filter = null){

		$result = array();
		$iterator = $recurse ? new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS) : new \FilesystemIterator($path, \FilesystemIterator::SKIP_DOTS);

		if ($recurse){
			$iterator = new \RecursiveIteratorIterator($iterator);
		}

		if (!is_null($filter)){
			$iterator = new \RegexIterator($iterator, $filter, \RegexIterator::ALL_MATCHES);
		}

		foreach ($iterator as $item){
			if (!$item->isDir()){
				$sep = substr($path, strlen($path) - 1, 1) === DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
				$result[] = $path . $sep . ($recurse ? $iterator->getSubPathName() : $iterator->getFilename());
			}
		}

		return $result;

	}

	public static function getFilesAsJson($path, $recurse = false, $filter = null){

		$result = array();
		$files = \WebsiteConnect\Framework\Utility\FileSystem::getFiles($path, $recurse, $filter);

		foreach ($files as $file){
			$result[] = json_decode(file_get_contents($file), true);
		}

		return $result;

	}

	public static function cloneDirectory($source, $destination, $contentsOnly = false){

		$result = false;

		try {

			if ($contentsOnly){

				foreach (
					$iterator = new \RecursiveIteratorIterator(
						new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
						\RecursiveIteratorIterator::SELF_FIRST) as $item) {
					if ($item->isDir()) {
						$result = self::cloneDirectory($source, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
						if (!$result)
							break;
					} else {
						self::copyFile($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
					}
				}

				return $result;

			}

			@mkdir($destination, 0755);

			foreach (
				$iterator = new \RecursiveIteratorIterator(
					new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
					\RecursiveIteratorIterator::SELF_FIRST) as $item) {
				if ($item->isDir()) {
					@mkdir($destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				} else {
					self::copyFile($item, $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
				}
			}

			$result = true;

		} catch (\Exception $e){}

		return $result;

	}

	/*
	 * If the destination file exists, it will be deleted first.
	 */
	public static function copyFile($source, $destination){

		if (file_exists($destination))
			unlink($destination);

		copy($source, $destination);

	}

	public static function combinePaths($paths, $endWith = true){

		$result = implode(DIRECTORY_SEPARATOR, $paths);

		if ($endWith)
			$result .= DIRECTORY_SEPARATOR;

		return $result;

	}

	public static function deleteDirectory($directory){

		$result = false;

		if (file_exists($directory)){
			self::deleteDirectoryContents($directory);
			rmdir($directory);
			$result = true;
		}

		return $result;

	}

	public static function deleteDirectoryContents($directory, $ignored = array()){

		$result =false;

		if (file_exists($directory)){
			$files = new \RecursiveIteratorIterator(
				new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
				\RecursiveIteratorIterator::CHILD_FIRST
			);

			foreach ($files as $fileinfo) {

				$toIgnore = false;
				foreach ($ignored as $ignore){
					if ($ignore === $fileinfo->getFilename()){
						$toIgnore = true;
						break;
					}
				}
				if ($toIgnore || $fileinfo->getRealPath() === $directory)
					continue;

				$todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
				$todo($fileinfo->getRealPath());
			}
			$result = true;
		}

		return $result;

	}

}