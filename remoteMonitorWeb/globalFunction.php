<?php

define("IMAGES", "./images/");


function gourl($s, $url)
{
    $xstr = $url == -1 ? "history.go(-1);":"location.href='".$url."';";
    $xs = $s == "" ? "":"alert(\"".$s."\");";
    $str = "<script type='text/javascript'>".$xs.$xstr."</script>"; 
    echo $str;
    exit();
}

/*
  Login:  admin
  Passwd: 123456
*/
function getLogin($csl, $password)
{
    if ($csl == "admin" && $password == "123456") {
      return true;
    } else {
      return false;
    }

}

function login($csl,$password,$goUrl)
{
    global $notInLdapUser,$notInLdapUserPwd;
    $login_success = false;

    if (isset($csl)) {
        $user = trim($csl);
        $password = trim($password);
    } else {
        $user = "";
        $password = "";
        gourl("Please input your user name and password",-1);
    }

    if (isset($_SESSION['REMOTE_USER'])) {
        $login_success = true;
        $user = $_SESSION['REMOTE_USER'];
    } else if (isset($user) && $user && isset($password) && $password) {
        if (!getLogin($user,$password)) {
            gourl("Login Failed!","login.php?user=".$user);
        } else {
            $login_success = true;
            $_SESSION['REMOTE_USER'] = $user;
            if($_POST['remember']) {
                session_set_cookie_params(7*24*3600);
                setcookie("username",$user,time()+60*60*24*30,getCookiePath());
                setcookie("password",$password,time()+60*60*24*30,getCookiePath());
            } 
        }
    }

    if ($_SESSION['REMOTE_USER']||(!empty($_COOKIE['username'])&&!empty($_COOKIE['password']))) {
        if (trim($_GET['url']))
            header("Location:".$_GET['url']);
        else
            header("Location:".($goUrl?$goUrl:"index.php")."");
        return;
    } else {
        gourl("Login Failed!","login.php?user=".$user);       
    }
}

function logout($url)
{
    unset($_SESSION['REMOTE_USER']);
    setcookie("username", NULL, -1, getCookiePath());
    unset($_SESSION['PASSWORD']);
    
    setcookie("password", NULL, -1, getCookiePath());
    
    header("Location:".($url?$url:"login.php"));
}



function showLoginForm()
{
    $img = IMAGES;
    $url = isset($_GET['url'])?$_GET['url']:"";
    $user = isset($_GET['user'])?$_GET['user']:"";
    if (defined('LOGINTITLE'))
        $logintitle = LOGINTITLE;
    else
        $logintitle = "&nbsp;";
      
    if(defined('CONTACTER'))
        $toolContact = CONTACTER;
    else
        $toolContact = '<a href="mailto:brucehi@163.com (__mailto:brucehi@163.com)">Bruce</a>';
      
    if(defined('LOGINBG'))
        $loginbj = LOGINBG;
    else
        $loginbj = '#ffffff';
      
    if(defined('LOGINPIC'))
        $loginpic = 'images/'.LOGINPIC;
    else
        $loginpic = IMAGES."homepage.png";
    $str=<<<FOM
      <br />
      <p align="center" style="font-size:24px; font-weight:bold; font-family:Trebuchet MS; color:#0d4d8a">{$logintitle}</p>

      <table width="1000" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="{$loginbj}">
        <tr>
        <td width="49%"><img src="{$loginpic}" width="580" height="435" /></td>
        <td width="51%">
        
        <form id="form1" name="form1" method="post" action="" onsubmit="return chk()">
        
        <table width="95%" border="0" align="center" cellpadding="2" cellspacing="0" style="border:2px solid #CCCCCC">
          <tr>
          <td height="33" colspan="2" background="{$img}login_bg.gif"><p align="center" style="color:#555555; margin:4px 0 8px 0; font-weight:bold;">Please use your User Name and PASSWORD to Login</p> </td>
          </tr>
          <tr>
          <td width="28%" height="10"></td>
          <td width="72%"></td>
          </tr>
          <tr>
          <td height="45">&nbsp;&nbsp;User Name : </td>
          <td><input name="csl" type="text" class="itxt" id="csl" value="{$user}" /></td>
          </tr>
          <tr>
          <td height="45">&nbsp;&nbsp;PASSWORD : </td>
          <td><input name="password" type="password" class="itxt" id="password" /></td>
          </tr>
          <tr>
          <td></td>
          <td>
            <input align="absmiddle" name="remember" type="checkbox" id="remember" value="1" />
            <label for="remember">Remember</label>
          </td>
          </tr>
          <tr>
          <td height="45" colspan="2"><div align="center">
            <input name="Submit" type="submit"  value="Login" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input name="Submit2" type="reset"  value="Reset" />
          </div></td>
          </tr>
          <tr>
          <td colspan="2" align="center"><input name="act" type="hidden" id="act" value="login" />
          <input name="url" type="hidden" id="url" value="{$url}" />
          <p>Any login problem please contact {$toolContact}</p>
          </td>
          </tr>
        </table>
        </form>
        
        </td>
        </tr>
        <tr>
        <td colspan="2" height="50"></td>
        </tr>
        <tr>
        <td colspan="2" height="2" background="{$img}login_line.gif"></td>
        </tr>
      </table> 
    
FOM;
    return $str;
}

 function showCopy($text)
{
    $str = '<p style="font-family:Arial, Helvetica, sans-serif; font-size:13px;" align="center">Published &copy; '.$text.'</p>';
    return $str;
} 

function showChkLoginForm()
{
    $str=<<<SCR
      <script type="text/javascript">
      function chk()
      {
        var f = document.form1;
        if(f.csl.value=="")
        {
          alert("Please input user name!");
          f.csl.focus();
          return false;
        }
        if(f.password.value=="")
        {
          alert("Please input your password!");
          f.password.focus();
          return false;
        }
      }
      </script> 
SCR;
    return $str;
} 

?>