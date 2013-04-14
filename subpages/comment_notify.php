<?php
  function comment_notify_main($params) {
    if (isset($GLOBALS['conf_fb_app_id'])) {
      $users = db_query("SELECT users.fbid FROM users INNER JOIN (SELECT group_id FROM group_permissions WHERE privilege_id = (SELECT privilege_id FROM privileges WHERE name = 'FacebookAdmin')) AS res ON users.group_id = res.group_id WHERE users.fbid <> ''");
      if (!empty($users)) {
        require_once("facebook_sdk/facebook.php");

        $app_id = $GLOBALS['conf_fb_app_id'];
        $app_secret = $GLOBALS['conf_fb_app_secret'];

        $fb_config = array();
        $fb_config['appId'] = $app_id;
        $fb_config['secret'] = $app_secret;
        $fb_config['fileUpload'] = false;
        $facebook = new Facebook($fb_config);

        $token_url = "https://graph.facebook.com/oauth/access_token?" .
                     "client_id=" . $app_id .
                     "&client_secret=" . $app_secret.
                     "&grant_type=client_credentials";
                     
        $app_token = str_replace("access_token=", "", file_get_contents($token_url));
        
        foreach ($users as $user) {
          $data = array(
              'href'=> '/',
              'access_token'=> $app_token,
              'template'=> 'Dodano komentarz na stronie.'
          );
          $sendnotification = $facebook->api("/{$user['fbid']}/notifications", 'post', $data);
        }
      }
    }
    exit();
  }
?>
