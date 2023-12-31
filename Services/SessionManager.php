<?php
namespace Services;

class SessionManager
{   
    //starts a session
    public function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        
        }
    }
    //sets a session variable
    public static function setSessionVariable($key, $value)
    {
     
        $_SESSION[$key] = $value;
    }

    //is variable set
    public static function isVar($key)
    {
        return isset($_SESSION[$key]);
    }
    //returns a session variable
    public static function getSessionVariable($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    //removes a session variable
    public static function unsetSessionVariable($key)
    {
   
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
    }
    //returns true if the user is logged in
    public static function isLoggedIn()
    {
        if (!isset($_SESSION['user']['logged_in'])) {
            return false;
        }
        return self::getSessionVariable('user')['logged_in'];
    }
    //logs out the user
    public static function logout()
    {
        // Check if the user is an admin or not
        if (SessionManager::isAdmin()) {
            self::unsetSessionVariable('user');
            header("Location: " . BASE_URL . "/admin-login");
            SessionManager::setSessionVariable('success_message', 'You have been logged out successfully.');
        }
        // If the user is not an admin, log out as a normal user
        else {
            self::unsetSessionVariable('user');
            header("Location: " . BASE_URL . "/login");
            SessionManager::setSessionVariable('success_message', 'You have been logged out successfully. See you soon!');
        }
    }

    //returns true is the user is customer
    public static function isCustomer()
    {
        if (!isset($_SESSION['user']['role'])) {
            return false;
        }
        return self::getSessionVariable('user')['role'] == 3;
    }

    //returns true if the user is an admin
    public static function isAdmin()
    {
     

        
        if (!isset($_SESSION['user']['role'])) {
            return false;
        }
        
        
        return self::getSessionVariable('user')['role'] == 1;
    }



    public static function getSessionId()
    {
        return session_id();
    }

    //returns the user id of the logged in user
    public static function getLoggedInUserId()
    {
        return self::getSessionVariable('user')['id'];
    }

    // generates a random string of 32 characters which is used as a CSRF token
    public static function generateCSRFToken() {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = [
            'value' => $csrfToken,
            // Set expiration time to 30 minutes (adjust as needed)
            'expiration_time' => time() + 1800, 
        ];
        return $csrfToken;
    }
    // returns the CSRF token
    public static function getCSRFToken() {
        // CSRF tocken assigned if exists or null if not
        $csrfTokenData = $_SESSION['csrf_token'] ?? null;
        //
        if ($csrfTokenData && time() < $csrfTokenData['expiration_time']) {
            return $csrfTokenData['value'];
        }

        // if the the token expired, generate a new one
        return self::generateCSRFToken();
    }
    // validates the CSRF token
    public static function validateCSRFToken($token) {
        $csrfTokenData = SessionManager::getSessionVariable('csrf_token');
    
        // Check if the token exists and is valid (value matched and not expired)
        if ($csrfTokenData && $token === $csrfTokenData['value'] && time() < $csrfTokenData['expiration_time']) {
            return true; // Token is valid
        } elseif ($csrfTokenData && $token !== $csrfTokenData['value']) {
            // Token doesn't match
            SessionManager::setSessionVariable('output_alert', "The form could not be submitted due to a security issue. Please refresh the page and try again.");
        } else {
            // Token expired
            SessionManager::setSessionVariable('output_alert', "Your session has expired, and the form is no longer valid. Please refresh the page and try submitting the form again. If the issue persists, log in again and retry.");
        }
    
        return false; // Validation failed
    }

    
    //Destroys the current session
    public static function destroySession()
    {
        session_destroy();
    }

    
    // Clears all session variables
    public static function clearAll()
    {
        session_unset();
        session_destroy();
    }
}