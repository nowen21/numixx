<?php
class MY_Form_validation extends CI_Form_validation {

	function valid_url($url) {
	    $pattern = "/^((ht|f)tp(s?)\:\/\/|~/|/)?([w]{2}([\w\-]+\.)+([\w]{2,5}))(:[\d]{1,5})?/";
	    if (!preg_match($pattern, $url)) {
	        return FALSE;
	    }
	    return TRUE;
	}

}

