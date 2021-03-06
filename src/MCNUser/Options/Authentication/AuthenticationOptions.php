<?php
/**
 * Copyright (c) 2011-2013 Antoine Hedgecock.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright   2011-2013 Antoine Hedgecock
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
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
    protected $enabled_redirection = true;

    /**
     * The route to redirect the user to on a successful login
     *
     * @var string|null
     */
    protected $successful_login_route = null;

    /**
     * The route to redirect to if the authentication fails
     *
     * @var string|null
     */
    protected $failed_login_route = null;

    /**
     * Get the route to redirect to after the users logs out
     *
     * @var string|null
     */
    protected $logout_route = null;



    /**
     * @var string
     */
    protected $user_service_sl_key = 'mcn.service.user';

    /**
     * @var array
     */
    protected $plugins = array();

    /**
     * @var array
     */
    protected $listeners = array();



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
        if (isset($this->plugins[$plugin->getPluginManagerAlias()]) && !$overwrite) {

            throw new \LogicException(
                sprintf('Plugin with the alias %s already exists and overwrite was disabled', $plugin->getPluginManagerAlias())
            );
        }

        $this->plugins[$plugin->getPluginManagerAlias()] = $plugin;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnabledRedirection()
    {
        return $this->enabled_redirection;
    }

    /**
     * @param boolean $enable_redirection
     */
    public function setEnabledRedirection($enable_redirection)
    {
        $this->enabled_redirection = (bool) $enable_redirection;
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

    /**
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @param array $listeners
     * @return $this
     */
    public function setListeners(array $listeners)
    {
        foreach ($listeners as $listener) {

            $this->addListener($listener);
        }

        return $this;
    }

    /**
     * @param string $listener
     * @return $this
     */
    public function addListener($listener)
    {
        if (! in_array($listener, $this->listeners)) {

            $this->listeners[] = $listener;
        }

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLogoutRoute()
    {
        return $this->logout_route;
    }

    /**
     * @param null|string $logout_route
     */
    public function setLogoutRoute($logout_route)
    {
        $this->logout_route = $logout_route;
    }

    /**
     * @return null|string
     */
    public function getFailedLoginRoute()
    {
        return $this->failed_login_route;
    }

    /**
     * @param null|string $failed_login_route
     */
    public function setFailedLoginRoute($failed_login_route)
    {
        $this->failed_login_route = $failed_login_route;
    }
}
