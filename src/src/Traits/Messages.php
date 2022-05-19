<?php

namespace App\Traits;

trait Messages {

    /**
     * Simple custom method for backoffice user display message
     *
     * @param $type
     * @param $content
     * @param $path
     * @return mixed
     */
    public function message($type, $content, $path) {
        $message = $this->get('session')->getFlashBag()->set($type, $content);
        return $this->redirect($path);
    }

    /**
     * Simple custom method for backoffice user display message with translation
     *
     * @param $translator
     * @param $type
     * @param $params
     * @param $domain
     * @param $key
     * @param $path
     * @return mixed
     */
    public function transmessage($translator, $type, $params, $domain, $key, $path) {
        // TODO: implementare la dependency injection globale del translator
        //$message = $this->get('session')->getFlashBag()->set($type, $this->translator->trans($key, $params, $domain));
        $message = $this->get('session')->getFlashBag()->set($type, $translator->trans($key, $params, $domain));
        return $this->redirectToRoute($path);
    }

    /**
     * Simple custom method for backoffice user display message with translation
     *
     * @param $translator
     * @param $type
     * @param $params
     * @param $domain
     * @param $key
     * @param $path
     * @return mixed
     */
    public function transmessageWithId($translator, $type, $params, $domain, $key, $path, $id) {
        // TODO: implementare la dependency injection globale del translator
        $message = $this->get('session')->getFlashBag()->set($type, $translator->trans($key, $params, $domain));
        return $this->redirectToRoute($path, ['id' => $id]);
    }

    /**
     * Simple custom method for backoffice user display message with translation
     *
     * @param $translator
     * @param $type
     * @param $params
     * @param $domain
     * @param $key
     * @param $path
     * @param $request
     *
     * @return mixed
     */
    public function transmessageRedirectBack($translator, $type, $params, $request,  $domain, $key) {
        // TODO: implementare la dependency injection globale del translator
        $message = $this->get('session')->getFlashBag()->set($type, $translator->trans($key, $params, $domain));
        return $this->redirect($request->headers->get('Referer'));
    }

}
