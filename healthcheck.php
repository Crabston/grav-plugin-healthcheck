<?php
namespace Grav\Plugin;

use Grav\Common\Page\Collection;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Common\Uri;

use Grav\Common\Taxonomy;
/**
 * Class HealthcheckPlugin
 * @package Grav\Plugin
 */
class HealthcheckPlugin extends Plugin
{

	/**
	 * @var
	 */
	protected $uri;
	protected $healthConfig;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0],
            ]
        ];
    }

    ///**
    // * Composer autoload
    // *
    // * @return ClassLoader
    // */
    //public function autoload(): ClassLoader
    //{
    //    return require __DIR__ . '/vendor/autoload.php';
    //}

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

	    $uri = $this->grav['uri'];
	    $config = $this->config();

	    $route = $config['route'] ?? null;
	    if ($route && $route == $uri->path()) {
		    $this->enable([
			    'onPageInitialized' => ['onPageInitialized', 0]
		    ]);
	    }

    }

	/**
	 * Send user to a random page
	 */
	public function onPageInitialized(): void
	{
		$config = $this->config();

		$this->grav['page']->template($config['template'] ?? 'healthcheck');

	}
}
