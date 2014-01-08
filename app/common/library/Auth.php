<?php

/**
 * Auth Library
 *
 * @package     base-app
 * @category    Library
 * @version     2.0
 */

namespace Baseapp\Library;

use \Baseapp\Models\Users,
    \Baseapp\Models\Roles,
    \Baseapp\Models\RolesUsers,
    \Baseapp\Models\Tokens;

class Auth
{

    private $_config = array(
        'hash_method' => 'sha256',
        'hash_key' => 'secret_key',
        'lifetime' => 1209600,
        'session_key' => 'auth_user',
        'session_roles' => TRUE,
    );
    private static $_instance;
    private $_cookies;
    private $_session;

    /**
     * Singleton pattern
     *
     * @return Auth
     */
    public static function instance()
    {
        if (empty(self::$_instance))
            self::$_instance = new Auth;

        return self::$_instance;
    }

    private function __construct()
    {
        // Overwrite _config from config.ini
        if ($_config = \Phalcon\DI::getDefault()->getShared('config')->auth)
            foreach ($_config as $key => $value)
                $this->_config[$key] = $value;

        $this->_cookies = \Phalcon\DI::getDefault()->getShared('cookies');
        $this->_session = \Phalcon\DI::getDefault()->getShared('session');
    }

    private function __clone()
    {

    }

    /**
     * Checks if a session is active.
     *
     * @param   mixed    $role Role name string
     * @return  boolean
     */
    public function logged_in($role = NULL)
    {
        // Get the user from the session
        $user = $this->get_user();
        if (!$user)
            return FALSE;

        // If user exists in session
        if ($user) {
            // If we don't have a roll no further checking is needed
            if (!$role)
                return TRUE;

            // Check if user have the role
            if ($this->_config['session_roles']) {
                // Check in session
                $role = property_exists($user->roles, $role) ? $user->roles->$role : NULL;
            } else {
                // Check in db
                $role = Roles::findFirst(array('name=:role:', 'bind' => array('role' => $role)));
                $role = RolesUsers::findFirst(array('user_id=:user: AND role_id=:role:', 'bind' => array('user' => $user->id, 'role' => $role->id)));
            }

            // Return true if user has role
            return $role ? TRUE : FALSE;
        }
    }

    /**
     * Gets the roles of user.
     *
     * @return  array
     */
    public function get_roles($user)
    {
        $roles = array();

        if ($user) {
            // Find related records for a particular user
            foreach ($user->getRelated('Baseapp\Models\RolesUsers') as $roleuser) {
                // Get related role
                $role = $roleuser->getRelated('Baseapp\Models\Roles')->toArray();
                $roles [$role['name']] = $role['id'];
            }
        }

        return $roles;
    }

    /**
     * Gets the currently logged in user from the session.
     * Returns NULL if no user is currently logged in.
     *
     * @return  mixed
     */
    public function get_user()
    {
        $user = $this->_session->get($this->_config['session_key']);

        // Check for "remembered" login
        if (!$user)
            $user = $this->auto_login();

        return $user;
    }

    /**
     * Refresh user data stored in the session from the database.
     * Returns NULL if no user is currently logged in.
     *
     * @return  mixed
     */
    public function refresh_user()
    {
        $user = $this->_session->get($this->_config['session_key']);

        if (!$user)
            return NULL;
        else {
            // Get user's data from db
            $user = Users::findFirst($user->id);
            $roles = $this->get_roles($user);

            // Regenerate session_id
            session_regenerate_id();

            // Store user in session
            $user = json_decode(json_encode(array_merge(get_object_vars($user), array('roles' => $roles))));
            $this->_session->set($this->_config['session_key'], $user);

            return $user;
        }
    }

    /**
     * Complete the login for a user by incrementing the logins and saving login timestamp
     *
     * @return void
     */
    private function complete_login($user)
    {
        // Update the number of logins
        $user->logins = $user->logins + 1;

        // Set the last login date
        $user->last_login = time();

        // Save the user
        $user->update();
    }

    /**
     * Logs a user in, based on the authautologin cookie.
     *
     * @return  mixed
     */
    private function auto_login()
    {
        if ($this->_cookies->has('authautologin')) {
            $cookie_token = $this->_cookies->get('authautologin')->getValue('string');

            // Load the token
            $token = Tokens::findFirst(array('token=:token:', 'bind' => array('token' => $cookie_token)));

            // If the token exists
            if ($token) {
                // Load the user and his roles
                $user = $token->getRelated('Baseapp\Models\Users');
                $roles = $this->get_roles($user);

                // If user has login role and tokens match, perform a login
                if (isset($roles['login']) && $token->user_agent === sha1(\Phalcon\DI::getDefault()->getShared('request')->getUserAgent())) {
                    // Save the token to create a new unique token
                    $token->token = $this->create_token();
                    $token->save();

                    // Set the new token
                    $this->_cookies->set('authautologin', $token->token, $token->expires);

                    // Finish the login
                    $this->complete_login($user);

                    // Regenerate session_id
                    session_regenerate_id();

                    // Store user in session
                    $user = json_decode(json_encode(array_merge(get_object_vars($user), array('roles' => $roles))));
                    $this->_session->set($this->_config['session_key'], $user);

                    // Automatic login was successful
                    return $user;
                }

                // Token is invalid
                $token->delete();
            } else {
                $this->_cookies->set('authautologin', "", time() - 3600);
                $this->_cookies->delete('authautologin');
            }
        }

        return FALSE;
    }

    /**
     * Attempt to log in a user by using an ORM object and plain-text password.
     *
     * @param   string   user to log in
     * @param   string   password to check against
     * @param   boolean  enable autologin
     * @return  boolean
     */
    public function login($user, $password, $remember = FALSE)
    {
        if (!is_object($user)) {
            $username = $user;

            // Username not specified
            if (!$username)
                return NULL;

            // Load the user
            $user = Users::findFirst(array('username=:username:', 'bind' => array('username' => $username)));
        }

        if ($user) {
            $roles = $this->get_roles($user);

            // Create a hashed password
            if (is_string($password))
                $password = $this->hash($password);

            // If user have login role and the passwords match, perform a login
            if (isset($roles['login']) && $user->password === $password) {
                if ($remember === TRUE) {
                    // Create a new autologin token
                    $token = new Tokens();
                    $token->user_id = $user->id;
                    $token->user_agent = sha1(\Phalcon\DI::getDefault()->getShared('request')->getUserAgent());
                    $token->token = $this->create_token();
                    $token->created = time();
                    $token->expires = time() + $this->_config['lifetime'];
                    $token->create();

                    // Set the autologin cookie
                    $this->_cookies->set('authautologin', $token->token, time() + $this->_config['lifetime']);
                }

                // Finish the login
                $this->complete_login($user);

                // Regenerate session_id
                session_regenerate_id();

                // Store user in session
                $user = json_decode(json_encode(array_merge(get_object_vars($user), array('roles' => $roles))));
                $this->_session->set($this->_config['session_key'], $user);

                return TRUE;
            } else {
                // Login failed
                return FALSE;
            }
        }
        // No user found
        return NULL;
    }

    /**
     * Log out a user by removing the related session variables
     * Remove any autologin cookies.
     *
     * @param   boolean  $destroy     completely destroy the session
     * @param	boolean  $logout_all  remove all tokens for user
     * @return  boolean
     */
    public function logout($destroy = FALSE, $logout_all = FALSE)
    {
        if ($this->_cookies->has('authautologin')) {
            $cookie_token = $this->_cookies->get('authautologin')->getValue('string');

            // Delete the autologin cookie to prevent re-login
            $this->_cookies->set('authautologin', "", time() - 3600);
            $this->_cookies->delete('authautologin');

            // Clear the autologin token from the database
            $token = Tokens::findFirst(array('token=:token:', 'bind' => array('token' => $cookie_token)));

            if ($logout_all) {
                // Delete all user tokens
                foreach (Tokens::find(array('user_id=:user_id:', 'bind' => array('user_id' => $token->user_id))) as $_token) {
                    $_token->delete();
                }
            } else {
                if ($token)
                    $token->delete();
            }
        }

        // Destroy the session completely
        if ($destroy === TRUE)
            $this->_session->destroy();
        else {
            // Remove the user from the session
            $this->_session->remove($this->_config['session_key']);

            // Regenerate session_id
            session_regenerate_id();
        }

        // Double check
        return !$this->logged_in();
    }

    /**
     * Perform a hmac hash, using the configured method.
     *
     * @param   string  string to hash
     * @return  string
     */
    public function hash($str)
    {
        if (!$this->_config['hash_key'])
            throw new \Phalcon\Exception('A valid hash key must be set in your auth config.');

        return hash_hmac($this->_config['hash_method'], $str, $this->_config['hash_key']);
    }

    /**
     * Create auto login token.
     *
     * @return  string
     */
    protected function create_token()
    {
        do {
            $token = sha1(uniqid(\Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 32), TRUE));
        } while (Tokens::findFirst(array('token=:token:', 'bind' => array('token' => $token))));

        return $token;
    }

}
