# Healthcheck Plugin

The **Healthcheck** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav). Create a Healthcheck endpoint for your Grav site to monitor the status of your site

## Installation

Installing the Healthcheck plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](https://learn.getgrav.org/cli-console/grav-cli-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install healthcheck

This will install the Healthcheck plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/healthcheck`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `healthcheck`. You can find these files on [GitHub](https://github.com/chraebsli/grav-plugin-healthcheck) or via [GetGrav.org](https://getgrav.org/downloads/plugins).

You should now have all the plugin files under

    /your/site/grav/user/plugins/healthcheck
	
> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/chraebsli/grav-plugin-healthcheck/blob/main/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/healthcheck/healthcheck.yaml` to `user/config/plugins/healthcheck.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true   # Enable the plugin
route: /health  # The route to the healthcheck endpoint
```

Note that if you use the Admin Plugin, a file with your configuration named healthcheck.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

The Healthcheck plugin adds a new route to your Grav site. By default, the route is `/health`. You can change the route in the plugin configuration.

The healthcheck endpoint returns a JSON response with the following information:

```json
{
    "status": 200,
    "message": "OK"
}
```

With this information, you can monitor the status of your Grav site.

## To Do

- [ ] Add more information to the healthcheck response
- [ ] Add more configuration options
