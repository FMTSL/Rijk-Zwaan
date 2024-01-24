<?php

namespace Source\Classe;

use Aura\Session\SessionFactory;


class Sessao
{
    private $sessao;

    /**
     *
     * Metodo Construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->sessao = new SessionFactory();
    }

    public function getUser()
    {
        $session = $this->sessao->newInstance($_COOKIE);
        $segment = $session->getSegment('Vendor\Aura\Segment');

        return $segment->get('id_usuario');
    }

    public function getRoles()
    {
        $session = $this->sessao->newInstance($_COOKIE);
        $segment = $session->getSegment('Vendor\Aura\Segment');

        return $segment->get('nivel');
    }

    public function add(string $id, string $email, int $nivel)
    {
        session_start();
        $session = $this->sessao->newInstance($_COOKIE);
        $session->setCookieParams(array('lifetime' => '2592000'));
        $session->setCookieParams(array('path' => ROOT . '/cache/session'));
        $session->setCookieParams(array('domain' => ROOT ));

        return $_SESSION = array(
            'Vendor\Aura\Segment' => array(
                'id_usuario' => $id,
                'usuario' => $email,
                'nivel' => $nivel,
            ),
        );
    }

    public function logout()
    {
        $session = $this->sessao->newInstance($_COOKIE);
        $segment = $session->getSegment('Vendor\Aura\Segment');

        $session->destroy();
        redirect(ROOT);
    }
}
