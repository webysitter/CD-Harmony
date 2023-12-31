<?php
    namespace Services;

      class Validator {
        
    // reCAPTCHA validation
    // $recaptcha is the response from the reCAPTCHA service and $action indicated the form that was submitted
    public function validateRecaptchaResponse($captcha, $action) {
        $captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = RECAPTCHA_SECRET_KEY;
        // call curl to POST request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $captcha_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_secret, 'response' => $captcha)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);
 
        var_dump($arrResponse);
        // verify the response
        if($arrResponse["success"] == true && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
        // valid submission
            return true;
            echo "I'm after the recaptch validation to true";
        
        } else {
        // spam submission
        echo "I'm after the reCAPTCHA validation is false";
        var_dump($arrResponse);
        echo $arrResponse["success"];
        echo $arrResponse["action"];
        echo $arrResponse["score"];
        return false;
        }
    }

            public function validateEmail($email){
            
                $regexp = "/^[^0-9][A-z0-9_-]+([.][A-z0-9_]+)*[@][A-z0-9_]+([.][A-z0-9_-]+)*[.][A-z]{2,4}$/";

                //if not email was entered
                if (empty($email)) 
                    return "Please write your email.";
                //if the email is not in a valid format
                elseif (!preg_match($regexp,$email))
                    return "This is an invalid Email.";
                // Passed all checks
                else
                return null;
            }

            public function validatePassword($password) {
                // Minimum length requirement
                $minLength = 8;
                if (strlen($password) < $minLength) {
                    return "Password must be at least 8 characters long.";
                }

                // Contains at least one uppercase letter
                if (!preg_match('/[A-Z]/', $password)) {
                    return "Password must contain at least one uppercase letter.";
                }

                // Contains at least one lowercase letter
                if (!preg_match('/[a-z]/', $password)) {
                    return "Password must contain at least one lowercase letter.";
                }

                // Contains at least one digit
                if (!preg_match('/\d/', $password)) {
                    return "Password must contain at least one digit.";
                }

                // Contains at least one special character (e.g., !@#$%^&*)
                if (!preg_match('/[!@#\$%\^&\*\(\)_\+\-=\[\]\{\};:\'",<>\.\?~`]/', $password)) {
                    return "Password must contain at least one special character.";
                }

                // Passed all checks
                return null;
            }

            public function validateName($name) {
                if (strlen($name) > 100) {
                    // Handle text length exceeds 100 characters
                    return "Your name is too long. Please enter a name up to 100 characters.";
                }

                // Passed the check
                return null;
            }

            public function validateMessage($message) {
                if (strlen($message) > 3000) {
                    // Handle text length exceeds 3000 characters
                    return "The message you've entered is too long. Please enter a message up to 3000 characters.";
                } elseif (strlen($message) <1) {
                    // Handle text length does not exceed 3000 characters
                    return "Please write your message";
                }else {
                    // Passed the check
                    return null;
                }
                
            }
        }
