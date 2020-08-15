<?php

namespace VueContactManager\Controllers;

use VueContactManager\Model\Contact;

class ContactController
{
    private $model;

    public function __construct()
    {
        $this->model = new Contact();
    }

    public function getContacts(\WP_REST_Request $request)
    {
        $page = $request->get_param('page');
        if(!$page) {
            $page = 1;
        }
        $perPage = $request->get_param('per_page');
        if(!$perPage) {
            $perPage = 10;
        }

        $this->sendResponse($this->model->paginate($page, $perPage));
    }

    public function getContact(\WP_REST_Request $request)
    {
        $contactId = $request->get_param('id');
        $this->sendResponse($this->model->getOne($contactId));
    }

    public function createContact(\WP_REST_Request $request)
    {
        $contact = $request->get_param('contact');
        $id = $this->model->insert($contact);
        $this->sendResponse([
            'message' => 'Contact successfully created',
            'id' => $id
        ]);
    }

    public function updateContact(\WP_REST_Request $request)
    {
        $contactData = $request->get_param('contact');
        $id = $request->get_param('id');

        $this->model->update($id, $contactData);

        $this->sendResponse([
            'message' => 'Contact has been updated'
        ]);
    }

    public function deleteContact(\WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $this->model->delete($id);
        $this->sendResponse([
            'message' => 'Contact has been deleted'
        ]);
    }

    private function sendResponse($data)
    {
        wp_send_json($data, 200);
    }
}