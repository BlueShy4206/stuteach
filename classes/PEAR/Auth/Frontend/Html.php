<?php
echo '</div>';

/**
  * Standard Html Login form
  * 
  */
class Auth_Frontend_Html {

    /**
      * Displays the login form
      *
      * @param object The calling auth instance
      * @return void
      */
    function render(&$caller, $username = '') {
        $loginOnClick = 'return true;';
        // Try To Use Challene responce
        // TODO javascript might need some improvement for work on other browsers
        if($caller->advancedsecurity && $caller->storage->supportsChallengeResponce() ) {
            // Init the secret cookie
            $caller->session['loginchallenege'] = md5(microtime());
            //$caller->session['loginchallenege'] = '1';
            #print 'Using Challenge Responce '.$caller->session['loginchallenege'].'<br/>';
            print "\n";
            print '<script language="JavaScript">'."\n";
            // This is ugly, better sugestions send them to me
            include(dirname(__FILE__).'/md5.js');
            print "\n";
            print ' function securePassword() { '."\n";
            print '   var pass = document.getElementById(\''.$caller->getPostPasswordField().'\');'."\n";
            print '   var secret = document.getElementById(\'authsecret\')'."\n";
            #print '   alert(pass);alert(secret); '."\n";
            // If using md5 for password storage md5 the password before 
            // we hash it with the secret
            #print '   alert(pass.value);';
            if ($caller->storage->getCryptType() == 'md5' ) {
                print '   pass.value = hex_md5(pass.value); '."\n";
                #print '   alert(pass.value);';
            }
            print '   pass.value = hex_md5(pass.value+\''.$caller->session['loginchallenege'].'\'); '."\n";
            #print '   alert(pass.value);';
            print '   secret.value = 1;'."\n";
            print '   var doLogin = document.getElementById(\'doLogin\')'."\n";
            print '   doLogin.disabled = true;'."\n";
            print '   return true;';
            print ' } '."\n";
            print '</script>'."\n";;
            print "\n";
            $loginOnClick = ' return securePassword(); ';
        }

        $status = '';
        if (!empty($caller->status) && $caller->status == AUTH_EXPIRED) {
            $status = '<i>您的session已經過期，請重新登入！</i>'."\n";
        } else if (!empty($caller->status) && $caller->status == AUTH_IDLED) {
            $status = '<i>您閒置過久了，請重新登入！</i>'."\n";
        } else if (!empty ($caller->status) && $caller->status == AUTH_WRONG_LOGIN) {
            $status = '<b>帳號或密碼有誤！</b>'."\n";
        } else if (!empty ($caller->status) && $caller->status == AUTH_SECURITY_BREACH) {
            $status = '<i>您的登入不符合本系統的安全規定！</i>'."\n";
        }
        #PEAR::raiseError('You are using the built-in login screen of PEAR::Auth.<br />See the <a href="http://pear.php.net/manual/">manual</a> for details on how to create your own login function.', null);

//        echo '<form method="post" action="'.$caller->server['PHP_SELF'].'" onSubmit="'.$loginOnClick.'">'."\n";
		echo '<form method="post" action="index.php" onSubmit="'.$loginOnClick.'">'."\n";
        echo '<table WIDTH="300" HEIGHT="80" border="0" cellpadding="2" cellspacing="0" summary="login form" align="left">'."\n";
        echo '<tr>'."\n";
        echo '    <td colspan="2" >'.$status.'</td>'."\n";
        echo '</tr>'."\n";
        echo '<tr>'."\n";
        echo '    <td align="right">帳號：</td>'."\n";
        echo '    <td><input type="text" id="'.$caller->getPostUsernameField().'" name="'.$caller->getPostUsernameField().'" value="' . $username . '" /></td>'."\n";
        echo '</tr>'."\n";
        echo '<tr>'."\n";
        echo '    <td align="right">密碼：</td>'."\n";
        echo '    <td><input type="password" id="'.$caller->getPostPasswordField().'" name="'.$caller->getPostPasswordField().'" /></td>'."\n";
        echo '</tr>'."\n";
        echo '<tr>'."\n";
        
        //onClick=" '.$loginOnClick.' "
        echo '    <td colspan="2" align="center"><input value="登入" id="doLogin" name="doLogin" type="submit" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;【<a href="javascript:close();">關閉視窗</a>】</td>'."\n";
        echo '</tr>'."\n";
        echo '</table>'."\n";
        // Might be a good idea to make the variable name variable 
        echo '<input type="hidden" id="authsecret" name="authsecret" value="">';
        echo '</form>'."\n";
    }
    
}

?>
