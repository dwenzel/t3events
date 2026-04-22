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
     * @var SessionInterface
     */
    protected $session;

    /**
     * namespace
     *
     * @var string
     */
    protected $namespace;

    public function injectSession(SessionInterface $session): void
    {
        $session->setNamespace($this->namespace);
        $this->session = $session;
    }
}
