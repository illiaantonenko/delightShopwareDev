<?php

use DelightCallback\Models\Callback;

class Shopware_Controllers_Frontend_DelightFrontendForm extends Enlight_Controller_Action
{

    public function indexAction()
    {
        $this->Request()->setHeader('Content-Type', 'application/json');
        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $params = $this->Request()->getPost();
        $response = [
            'status' => '',
            'message' => ''
        ];
        $callback = new Callback();
        $callback->fromArray([
            'name' => $params['name'],
            'phone' => $params['phone'],
            'createDate' => date('Y-m-d')
        ]);
        try {
            $modelManager = $this->getModelManager();
            $modelManager->persist($callback);
            $modelManager->flush();
            $response['status'] = 200;
            $response['message'] = 'Data saved.';
        } catch (Exception $exception) {
            $response['status'] = 500;
            $response['message'] = $exception->getMessage();
        }
        $this->Response()->setBody(json_encode([
            $response
        ]));
    }

}