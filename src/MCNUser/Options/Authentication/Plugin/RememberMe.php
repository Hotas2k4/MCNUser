<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNUser\Options\Authentication\Plugin;

use DateInterval;

/**
 * Class RememberMe
 * @package MCNUser\Options\Authentication\Plugin
 */
class RememberMe extends AbstractPluginOptions
{
    /**
     * @var string
     */
    protected $cookie_host;

    /**
     * @var string
     */
    protected $entity_identity_property = 'identity';

    /**
     * @var \DateInterval
     */
    protected $valid_interval;

    /**
     * Class name of representing plugin
     *
     * @return string
     */
    public function getClassName()
    {
        return 'MCNUser\Authentication\Plugin\RememberMe';
    }

    /**
     * Plugin alias
     *
     * @return string
     */
    public function getDefaultAlias()
    {
        return 'remember-me';
    }

    /**
     * @return \DateInterval
     */
    public function getValidInterval()
    {
        return $this->valid_interval;
    }

    /**
     * @param \DateInterval $valid_until
     */
    public function setValidInterval(DateInterval $valid_until)
    {
        $this->valid_interval = $valid_until;
    }

    /**
     * @return string
     */
    public function getCookieHost()
    {
        return $this->cookie_host;
    }

    /**
     * @param string $cookie_host
     */
    public function setCookieHost($cookie_host)
    {
        $this->cookie_host = $cookie_host;
    }

    /**
     * @return string
     */
    public function getEntityIdentityProperty()
    {
        return $this->entity_identity_property;
    }

    /**
     * @param string $entity_identity_property
     */
    public function setEntityIdentityProperty($entity_identity_property)
    {
        $this->entity_identity_property = $entity_identity_property;
    }
}