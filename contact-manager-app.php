<?php
/*
Plugin Name: Contact Manager  (Demo)
Description: Contact Manager Demo Project with Vue & WP Rest API. Available on github: https://github.com/techjewel/vue-contact-manager-demo
Version: 1.0
Author: Jewel
Author URI: https://wpmanageninja.com
Plugin URI: https://github.com/techjewel/vue-contact-manager-demo
License: GPLv2 or later
Text Domain: vue-contact
Domain Path: /resources/languages
*/

defined('ABSPATH') or die;

defined('VUE_CONTACT_MANAGER') or define('VUE_CONTACT_MANAGER', '1.0');
define('VUE_CONTACT_MANAGER_DIR', plugin_dir_path(__FILE__));
define('VUE_CONTACT_MANAGER_URL', plugin_dir_url(__FILE__));

class VueContactManager
{
    public function boot()
    {
        add_action('rest_api_init', array($this, 'registerResetEndpoints'));
        (new \VueContactManager\AdminApp())->register();
    }

    public function registerResetEndpoints()
    {
        $contactController = new \VueContactManager\Controllers\ContactController();

        $path = 'vue-contacts/v1';
        register_rest_route( $path, '/contacts', [
            array(
                'methods' => 'GET',
                'permission_callback' => array( $this, 'restPermissionCheck' ),
                'callback' => [$contactController, 'getContacts']
            ),
            array(
                'methods' => 'POST',
                'permission_callback' => array( $this, 'restPermissionCheck' ),
                'callback' => [$contactController, 'createContact']
            )
        ]);
        register_rest_route( $path, '/contacts/(?P<id>\d+)', [
            array(
                'methods' => 'GET',
                'permission_callback' => array( $this, 'restPermissionCheck' ),
                'callback' => [$contactController, 'getContact'],
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                )
            ),
            array(
                'methods' => 'PUT',
                'permission_callback' => array( $this, 'restPermissionCheck' ),
                'callback' => [$contactController, 'updateContact'],
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                )
            ),
            array(
                'methods' => 'DELETE',
                'permission_callback' => array( $this, 'restPermissionCheck' ),
                'callback' => [$contactController, 'deleteContact'],
                'args' => array(
                    'id' => array(
                        'validate_callback' => function($param, $request, $key) {
                            return is_numeric( $param );
                        }
                    ),
                )
            )
        ]);
    }

    public function restPermissionCheck($request)
    {
        return current_user_can('manage_options');
    }
}

add_action('plugins_loaded', function () {
    require_once(VUE_CONTACT_MANAGER_DIR . 'includes/autoload.php');
    (new VueContactManager())->boot();
});

/*
 * Plugin Activation Hook
 */
register_activation_hook(__FILE__, function ($newWorkWide) {
    require_once(VUE_CONTACT_MANAGER_DIR . 'includes/Activator.php');
    $activator = new \VueContactManager\Activator();
    $activator->migrateDatabases($newWorkWide);
});

