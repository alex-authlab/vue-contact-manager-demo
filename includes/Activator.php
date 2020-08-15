<?php

namespace VueContactManager;

class Activator
{
    public function migrateDatabases($network_wide = false)
    {
        global $wpdb;
        if ($network_wide) {
            $site_ids = get_sites(array('fields' => 'ids', 'network_id' => get_current_network_id()));
            // Install the plugin for all these sites.
            foreach ($site_ids as $site_id) {
                switch_to_blog($site_id);
                $this->migrate();
                restore_current_blog();
            }
        } else {
            $this->migrate();
        }
    }

    public function migrate()
    {
        $this->createContactTable();
    }

    public function createContactTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $tableName = $wpdb->prefix . 'vcm_contacts';

        $sql = "CREATE TABLE $tableName (
				id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				email varchar(255),
				first_name varchar(255),
				last_name varchar(255),
				address_line_1 varchar(255),
				address_line_2 varchar(255),
				city varchar(255),
				state varchar(255),
				zip varchar(255),
				country varchar(255),
				status varchar(20),
				created_at timestamp NULL,
				updated_at timestamp NULL
			) $charset_collate;";

        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}
