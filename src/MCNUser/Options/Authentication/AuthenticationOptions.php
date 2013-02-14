<?php
/**
 * @author Antoine Hedgecock <antoine@pmg.se>
 * @author Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright PMG Media Group AB
 */

namespace MCNUser\Options\Authentication;

use Zend\Stdlib\AbstractOptions;

/**
 * Class AuthenticationOptions
 * @package MCNUser\Options
 */
class AuthenticationOptions extends AbstractOptions
{
    /**
     * Enable redirection on login
     *
     * If a param by the name of "redirect" exists and the login attempt is successful
     * the user will be redirected to that page
     *
     * @var bool
     */
    protected $enable_redirection = false;

    /**
     * The route to redirect the user to on a successful login
     *
     * @var string|null
     */
    protected $successful_login_route = null;

    /**
     * @var string
     */
    protected $user_service_sl_key = 'mcn.service.user';

    /**
     * @var array
     */
    protected $plugins = array();

    /**
     * @return string
     */
    public function getUserServiceSlKey()
    {
        return $this->user_service_sl_key;
    }

    /**
     * @param string $user_service_sl_key
     */
    public function setUserServiceSlKey($user_service_sl_key)
    {
        $this->user_service_sl_key = $user_service_sl_key;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

    /**
     * @param array $plugins
     *
     * @return $this
     */
    public function setPlugins(array $plugins)
    {
        foreach ($plugins as $name => $options) {

            if (is_array($options) || $options instanceof \Traversable) {

                $options = new $name($options);
            }

            $this->addPlugin($options);
        }

        return $this;
    }

    /**
     * Add a plugin to the authentication process
     *
     * @param Plugin\AbstractPluginOptions $plugin
     * @param bool                          $overwrite
     *
     * @throws \LogicException if a plugin with that alias already exists
     *
     * @return $this
     */
    public function addPlugin(Plugin\AbstractPluginOptions $plugin, $overwrite = false)
    {
        if (isset($this->plugins[$plugin->getAlias()]) && !$overwrite) {

            throw new \LogicException(
                sprintf('Plugin with the alias %s already exists and overwrite was disabled', $plugin->getAlias())
            );
        }

        $this->plugins[$plugin->getAlias()] = $plugin;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnableRedirection()
    {
        return $this->enable_redirection;
    }

    /**
     * @param boolean $enable_redirection
     */
    public function setEnableRedirection($enable_redirection)
    {
        $this->enable_redirection = (bool) $enable_redirection;
    }

    /**
     * @return null|string
     */
    public function getSuccessfulLoginRoute()
    {
        return $this->successful_login_route;
    }

    /**
     * @param null|string $successful_login_route
     */
    public function setSuccessfulLoginRoute($successful_login_route)
    {
        $this->successful_login_route = $successful_login_route;
    }
}