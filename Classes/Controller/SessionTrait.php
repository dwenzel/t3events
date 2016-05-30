<?php
namespace Webfox\T3events\Controller;

use Webfox\T3events\Session\SessionInterface;

/**
 * Class SessionTrait
 * Provides session handling for controllers
 *
 * @package Webfox\T3events\Controller
 */
trait SessionTrait
{
    /**
     * @var \Webfox\T3events\Session\SessionInterface
     */
    protected $session;

    /**
     * namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * @param SessionInterface $session
     */
    public function injectSession(SessionInterface $session)
    {
        $session->setNamespace($this->namespace);
        $this->session = $session;
    }
}
