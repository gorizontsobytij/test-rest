<?php

namespace Src;

class DotEnv
{
	/**
	 * The directory where the .env file must locate.
	 *
	 * @var string
	 */
	private string $path;

	/**
	 * @param string $path
	 */
	public function __construct(string $path)
	{
		if(!file_exists($path)) {
			throw new \InvalidArgumentException(sprintf("Can't load .env file. Path %s does not exist", $path));
		}
		$this->path = $path;
	}

	/**
	 * Let's load file && put to environment.
	 */
	public function load() :void
	{
		if (!is_readable($this->path)) {
			throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
		}

		$lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {
			if (strpos(trim($line), '#') === 0) {
				continue;
			}

			[$name, $value] = explode('=', $line, 2);

			$name = trim($name);
			$value = trim($value);

			if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
				putenv(sprintf('%s=%s', $name, $value));
				$_ENV[$name] = $value;
				$_SERVER[$name] = $value;
			}
		}
	}

}
