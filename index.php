<?php
$serverId = 1;

// Requires a correctly set up PHP-ICE Module and mumble server running with ICE.
//  For instructions see http://mumble.sourceforge.net/ICE

// Credits
//  This script was created by Kissaki
// updated for PHP-ICE > 3.4 by Veka

// This is as simple as it can get. You really SHOULD NOT make the URL to this script all-public as it may be abused/spammed.


if (!extension_loaded('ice')) {
	exit('Error: This example script needs the PHP extension for Ice to be loaded.');
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
</head>
<body>
<?php

require_once 'Ice.php';
require_once 'Murmur.php';

if( isset($_GET['uname']) && $_GET['password'] && $_GET['email'] && $_GET['uname']!='' && $_GET['password']!='' )
{

  try {
    $ICE = Ice_initialize();
    $meta = Murmur_MetaPrxHelper::checkedCast($ICE->stringToProxy('Meta:tcp -h 127.0.0.1 -p 6502'));

    $server = $meta->getServer($serverId);

		if(empty($server)){
			echo 'Server could not be found.';
		}else{

      $check = $server->getUserIds([$_GET['uname']]);
      if($check[$_GET['uname']] <= 0 )
      {
        $server->registerUser([$_GET['uname'],$_GET['email'],null,null,$_GET['password']]);
        echo "Utilisateur ajout&eacute;";
      }
      else
      {
        echo "Nom d'utilisateur indisponible";
      }

		}

	}catch(InvalidPlayerException $ex){
		echo 'InvalidPlayerException';
	}catch(ServerBootedException $ex){
    echo 'Server is not running';
	}catch(Ice_UnknownLocalException $exc){
		echo 'Ice could probably not be found';
	}catch (Ice_Exception $ex){
    echo "<p>\n<pre>\n";
    print_r($ex);
    echo "</pre>\n</p>\n";
  }
}

?>
  <form action="" method="get">
    <table>
      <tr><td>Username</td><td><input name="uname" type="text" value=""/></td></tr>
      <tr><td>Password</td><td><input name="password" type="password" value=""/></td>
      <tr><td>eMail</td><td><input name="email" type="text" value=""/></td></tr>
    </table>
    <input type="submit"/>
  </form>
</body>
</html>

