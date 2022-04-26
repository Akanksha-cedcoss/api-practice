<?php

use Phalcon\Mvc\Controller;


class WebhooksController extends Controller
{
    public function addNewAction()
    {
        $webhooks = new Webhooks;
        if ($_POST) {
            $webhooks->addNewWebhook($_POST);
            $this->flash->success('New webhook created.');
        }
    }
}
