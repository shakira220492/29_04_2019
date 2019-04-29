<?php

namespace GoogleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


use Google\autoload;
class GoogleController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Google/Default/index.html.twig');
    }
    
    public function googleFormAction()
    {
        // step1: enter your google account credentials
        $client = new \Google_Client();
        // $client->setAuthConfig('/path/to/client_credentials.json');
        $client->setClientId("471191626527-vktdd7p2abd8bjhu8igb9arlge6mi1df.apps.googleusercontent.com");
        $client->setClientSecret("pIbQbvTMNLO5lDK1Bxy-DEfL");
        $client->setRedirectUri("http://localhost:8000/y_Google/successLoginGoogle");
        $client->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
        
        // step2: create the url
        $auth_url = $client->createAuthUrl();
//        echo "<a href='$auth_url'>Login Through Google: $auth_url</a>";
        
        return $this->redirect($auth_url);
    }
    
    public function successLoginGoogleFormAction()
    {
        // step1: enter your google account credentials
        $client = new \Google_Client();
        // $client->setAuthConfig('/path/to/client_credentials.json');
        $client->setClientId("471191626527-vktdd7p2abd8bjhu8igb9arlge6mi1df.apps.googleusercontent.com");
        $client->setClientSecret("pIbQbvTMNLO5lDK1Bxy-DEfL");
        $client->setRedirectUri("http://localhost:8000/y_Google/successLoginGoogle");
        $client->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
        
        
        // step3: get the authorization code
        $code = isset($_GET['code']) ? $_GET['code'] : NULL;
        
        // step4: get access token
        if (isset($code)) {
            try {
                $token = $client->fetchAccessTokenWithAuthCode($code);
                $client->setAccessToken($token);
//                echo '<pre>';
//                var_dump($token);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            
            try {
                $pay_load = $client->verifyIdToken();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            $pay_load = null;
        }
        echo $pay_load['email'];
        
        
        $oAuth = new \Google_Service_Oauth2($client);
        $userData = $oAuth->userinfo_v2_me->get();
        
//        echo '<pre>';
//        var_dump($userData);
        
        $id = $userData['id'];
        $email = $userData['email'];
        $familyName = $userData['familyName'];
        $givenName = $userData['givenName'];
        $name = $userData['name'];
        $picture = $userData['picture'];
        
        return new Response("success google login " . 
                "<br>id: " . $id . 
                "<br>email: " . $email . 
                "<br>familyName: " . $familyName . 
                "<br>givenName: " . $givenName . 
                "<br>name: " . $name . 
                "<br>picture: " . $picture);
    }
    
}