<?php

namespace VueContactManager;

class AdminApp
{
    public function register()
    {
        add_action( 'admin_menu', array($this, 'addAdminMenu'));
        add_action('render_vue_contact_admin_app', array($this, 'bootUi'));
        add_action('admin_enqueue_scripts', array($this, 'registerAssets'));
    }

    public function addAdminMenu()
    {
        add_menu_page(
            __( 'Contacts', 'vue-contact' ),
            __( 'Contacts', 'vue-contact' ),
            'manage_options',
            'vue-contacts',
            array($this, 'renderAppCallBack'),
            '',
            6
        );
    }

    public function renderAppCallBack()
    {
        do_action('render_vue_contact_admin_app');
    }

    public function bootUi()
    {
        ?>
            <div class="vue_contact_app_wrapper" id="vue_contact_app">Loading...</div>
        <?php
    }

    public function registerAssets()
    {
        if(!empty($_GET['page']) && $_GET['page'] == 'vue-contacts') {

            wp_enqueue_script('vue_contact_admin_js', VUE_CONTACT_MANAGER_URL.'dist/app.js', ['jquery'], VUE_CONTACT_MANAGER, true);
            wp_enqueue_style('vue_contact_admin_css', VUE_CONTACT_MANAGER_URL.'dist/app.css', [], VUE_CONTACT_MANAGER);

            wp_localize_script('vue_contact_admin_js', 'vue_contact_vars', [
                'version' => VUE_CONTACT_MANAGER,
                'api_url' => get_rest_url(null, 'vue-contacts/v1'),
                'nonce' => wp_create_nonce( 'wp_rest' )
            ]);
        }
    }
}