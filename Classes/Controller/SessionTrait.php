<?php
namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Session\SessionInterface;

/**
 * Class SessionTrait
 * Provides session handling for controllers
 *
 * @package DWenzel\T3events\Controller
 */
trait SessionTrait
{
    /**
     * @var \DWenzel\T3events\Session\SessionInterface
     */
    protected $session;

    /**
     * namespace
     *
     * @var string
     */
    protected $namespace;

    /**
     * @param \DWenzel\T3events\Session\SessionInterface $session
     */
    public function injectSession(SessionInterface $session)
    {
        $session->setNamespace($this->namespace);
        $this->session = $session;
    }
}
