<?php

declare(strict_types=1);

use Phalcon\Mvc\Controller;

final class WebhooksController extends Controller
{
    /**
     * initializing Webhook collection object
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->webhooks = new Webhooks();
    }
    /**
     * add new webhook in the Webhook collection
     *
     * @return void
     */
    public function addNewAction(): void
    {
        if ($this->request->isPost()) {
            $this->webhooks->addNewWebhook($this->request->getPost());
            $this->flash->success('New webhook created.');
        }
    }
}
