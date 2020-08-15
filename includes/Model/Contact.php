<?php

namespace VueContactManager\Model;

class Contact
{
    private $db;

    private $table;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table = $wpdb->prefix . 'vcm_contacts';
    }

    /*
     * get All Contacts from the database
     * @return $contacts Array containing all contact object
     */
    public function getAll()
    {
        return $this->db->get_results("SELECT * FROM {$this->table}");
    }

    /*
     * Get the total count of contacts
     * @return int
     */
    public function getTotal()
    {
        return $this->db->get_var("SELECT COUNT(*) FROM {$this->table}");
    }

    /**
     * Get Contacts by paginated data
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $contacts = $this->db->get_results(
            $this->db->prepare( 'SELECT * FROM '.$this->table.' LIMIT %1$d OFFSET %2$d',
                $perPage,
                $offset
            )
        );

        $totalContactCount = $this->getTotal();

        return [
            'current_page' => $page,
            'per_page' => $perPage,
            'from' => $offset + 1,
            'to' => $offset + count($contacts),
            'last_page' => ceil($totalContactCount / $perPage),
            'total' => $totalContactCount,
            'data' => $contacts,
        ];
    }

    /**
     * Insert a contact data
     * @param $data array
     * @return int insert id of the contact
     */
    public function insert($data)
    {
        $data['created_at'] = current_time('mysql');
        $data['updated_at'] = current_time('mysql');

        $this->db->insert($this->table, $data);
        return $this->db->insert_id;
    }

    /**
     * @param $id int
     * @return object
     */
    public function getOne($id)
    {
        return $this->db->get_row( $this->db->prepare(
            'SELECT * FROM '.$this->table.' WHERE id = %d LIMIT 1',
            $id
        ) );
    }

    /**
     * @param $id int id of the contact
     * @param $data array contact data as an array
     * @return boolean
     */
    public function update($id, $data)
    {
        $data['updated_at'] = current_time('mysql');

        return $this->db->update(
            $this->table,
            $data,
            array( 'id' => $id )
        );
    }

    /**
     * @param $id int contact id
     * @return boolean
     */
    public function delete($id)
    {
        return $this->db->delete( $this->table, ['id' => $id], array( '%d' ) );
    }
}
