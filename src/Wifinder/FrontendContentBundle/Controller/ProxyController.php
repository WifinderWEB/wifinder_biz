<?php

namespace Wifinder\FrontendContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProxyController extends Controller {

    private $query;
    private $params;

    private function ParseRequestParams() {
        $post = array();
        $form = $this->getRequest()->get('form');
        
        $formValue = $this->getRequest()->get($form);
        foreach($formValue as $i => $one){
            if(is_array($one)){
                foreach($one as $j => $field){
                    if($field)
                        $post[$form.'['.$i.']['.$j.']'] = $field;
                }
            }
            else
                if($one)
                    $post[$form.'['.$i.']'] = $one;
        }
        $this->query = $formValue['query'];


        foreach ($this->getRequest()->files as $one) {
            foreach($one as $i => $file){
                foreach($file as $j => $tmp ){
                    $post[$form.'['.$i.']['.$j.']'] = "@" . $tmp->getRealPath();
                    $post[$form.'['.$i.']['.$j.'][name]'] = $tmp->getClientOriginalName();
                    $post[$form.'['.$i.']['.$j.'][extension]'] = $tmp->getClientOriginalExtension();
                }
            }
        }
        unset($post[$form.'[query]']);
        
        $this->params = $post;
    }

    private function Route() {
        $this->ParseRequestParams();
        if ($this->query)
            $this->GetData($this->query);
        else
            throw new Exception("Wrong url");
    }

    public function submitFormAction() {
        $this->Route();
        $response = new Response();
        $response->setContent($this->responseData);

        return $response;
    }

    private function GetData($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->responseData = curl_exec($ch);
        curl_close($ch);
    }
}
