<?php

Namespace EDiff\Bundle\AdminBundle;

Class Utils {
	
	const key = "keydencryption";
	
	public static function encrypt_password($password)
    {
    	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(Utils::key), $password, MCRYPT_MODE_CBC, md5(md5(Utils::key))));
    }
    
    public static function decrypt_password($encrypted_password)
    {
    	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(Utils::key), base64_decode($encrypted_password), MCRYPT_MODE_CBC, md5(md5(Utils::key))), "\0");
    }
}

