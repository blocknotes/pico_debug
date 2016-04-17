# pico_debug

A debugging tool for Pico CMS

## INSTALL

- Copy zz_pico_debug.php in plugins folder

- Enable the plugin - edit config.php and add the line:

	$config['zz_pico_debug.enabled'] = TRUE;

- (optionally) To enable PHP show errors add to config.php:

	$config['zz_pico_debug']['php_errors'] = TRUE;

## USAGE

Open your Pico site, you should see a box on the bottom showing debugging informations.

It also enable Twig debug option, so in a template you can use: `{{ dump( var_name ) }}`

## NOTES

- Why 'zz_' in the beginning of the filename? It's necessary to let Pico load the plugin in the end
