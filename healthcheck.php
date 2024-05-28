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

		// set values for the default payload
		$payload = [];
		$defaultPayload = [
			'status_code' => 200,
			'status_message' => 'OK',
			'grav_version' => GRAV_VERSION,
			'php_version' => PHP_VERSION,
			'environment' => $this->grav['config']->get('environment'),
			'config' => [
				//'system' => $this->grav['config']->get('system'),
				//'site' => $this->grav['config']->get('site'),
				//'backups' => $this->grav['config']->get('backups'),
				//'theme' => $this->grav['config']->get('theme'),
			],
		];

		// loop through the info array and set the default values
		foreach ($config['info'] as $key => $value) {
			if ($value && $key != 'custom') {
				// set the default value for the key
				$payload[$key] = $defaultPayload[$key];
			} elseif ($key == 'custom') {
				// loop through the custom array and set the custom values
				foreach ($value as $customKey => $customValue) {
					$payload[$customKey] = $customValue;
				}
			}
		}

		// loop through the config array and get the values from the config
		foreach ($config['config'] as $key => $value) {
			foreach ($value as $customKey => $customValue) {
				$payload['config'][$customKey] = $this->grav['config']->get($customValue);
			}
		}

		$json = json_encode($payload);
		echo $json;
	}

	private function getConfig(){

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
