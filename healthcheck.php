<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;

/**
 * Class HealthcheckPlugin
 * @package Grav\Plugin
 */
class HealthcheckPlugin extends Plugin {

	/**
	 * get the subscribed events
	 * @return array
	 */
	public static function getSubscribedEvents(): array {
		return [
			'onPluginsInitialized' => ['onPluginsInitialized', 0],
		];
	}

	/**
	 * Initialize the plugin
	 */
	public function onPluginsInitialized(): void {
		// Don't proceed if we are in the admin plugin
		if ($this->isAdmin()) {
			return;
		}

		// Get the current URI
		$uri = $this->grav['uri'];
		$config = $this->config();
		$route = $config['route'] ?? null;

		// Check if the route is set and matches the current path
		if ($route && $route == $uri->path()) {
			$this->enable([
				'onPageInitialized' => ['onPageInitialized', 0],
				'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
				'onOutputGenerated' => ['onOutputGenerated', 0],
			]);
		}
	}

	/**
	 * Handle the healthcheck request
	 */
	public function onPageInitialized(): void {
		// get the config of this plugin
		$config = $this->config();
		$payload = [];

		// loop through the info array and set the values
		foreach ($config['info'] as $key => $value) {
			// set the default value for the key
			if ($key !== 'custom' && $value) $payload[$key] = $this->getDefaultPayloadValue($key);
			// loop through the custom array and set the custom values
			elseif ($key === 'custom' && $value) $payload = array_merge($payload, $this->loopThroughConfig($value));
		}

		// get the custom config values
		if ($config['config']) $payload['config'] = $this->loopThroughConfig($config['config'], true);

		// encode the payload to json and echo it
		$json = json_encode($payload);
		echo $json;
	}

	/**
	 * Loop through the config array and get the values from the config
	 * @param array $config
	 * @param bool $getConfig
	 * @return array|null
	 */
	private function loopThroughConfig($config, bool $getConfig = false): ?array {
		if (!$config) return null;

		$payload = [];

		foreach ($config as $key => $value) {
			$keys = explode(".", $key);
			$lastKey = array_pop($keys);
			$ref = &$payload;

			// recursively create the array structure
			foreach ($keys as $k) {
				if (!isset($ref[$k])) {
					$ref[$k] = [];
				}
				$ref = &$ref[$k];
			}

			// if getConfig is true, get the value from the Grav config
			if ($getConfig) $value = $this->getGravConfig($value);
			$ref[$lastKey] = $value;
		}
		return $payload;
	}

	/**
	 * Get the config value from the Grav config
	 * @param string $key
	 * @return mixed
	 */
	private function getGravConfig(string $key): mixed {
		return $this->grav['config']->get($key);
	}

	/**
	 * Get the default payload value
	 * @param string $key
	 * @return mixed
	 */
	private function getDefaultPayloadValue(string $key): mixed {
		$defaultPayload = [
			'status_code' => 200,
			'status_message' => 'OK',
			'timestamp' => date('Y-m-d H:i:s'),
			'environment' => $this->grav['config']->get('environment'),
			'grav_version' => GRAV_VERSION,
			'php_version' => PHP_VERSION,
		];
		return $defaultPayload[$key];
	}

	/**
	 * Add the plugin templates path
	 */
	public function onTwigTemplatePaths() {
		array_unshift($this->grav['twig']->twig_paths, __DIR__ . '/templates');
	}

	/**
	 * Set the response code to 200
	 */
	public function onOutputGenerated() {
		$this->grav['page']->modifyHeader('http_response_code', 200);
	}
}
