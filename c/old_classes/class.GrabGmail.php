<?php
/**
* Grab Gmail : Gmail Addressbook Grabber Class
*
* @author  :  MA Razzaque Rupom <rupom_315@yahoo.com>, <rupom.bd@gmail.com>
*             Moderator, phpResource (http://groups.yahoo.com/group/phpresource/)
*             URL: http://www.rupom.info
                   http://rupom.wordpress.com
* @version :  1.1
* @date       05/25/2007
* Purpose  :  Grabbing Your Gmail Addressbook
*/

  class GrabGmail
  {
     //To use it in PHP4, please replace each "private" by "var"
     var $contactListArray = array();
     var $contactListTable;
     var $requireCaBundle = false;
     var $caBundleFile;
     var $cookiePath;
     var $proxyUrl='';
     var $userId     ='';
     var $password   ='';
     var $url        = "https://www.google.com/accounts/ServiceLoginAuth";
     var $refererUrl = "https://www.google.com/accounts/ServiceLogin?service=mail&passive=true&rm=false&continue=http://mail.google.com/mail/?ui=html&zy=f&ltmpl=yj_blanco&ltmplcache=2&hl=en";

     /**
     * Sets login information of Gmail account
     * @param user id and password
     * @return none
     */
     function setLoginInfo($userId, $password)
     {

        $this->userId   = $userId;
        $this->password = $password;

     }//EO Method

     /**
     * Sets cookie save path(where cookie will be saved)
     * @param cookie path
     * @return none
     */
     function setCookiePath($cookiePath)
     {
        $this->cookiePath = $cookiePath;
     }

     /**
     * Sets proxy information if any
     * @param proxy URL
     * @return none
     */
     function setProxy($proxyUrl)
     {
        $this->proxyUrl = $proxyUrl;
     }

     /**
     * Sets Certificate Bundle File (this is necessary in accessing Gmail from localhost)
     * @param user id and password
     * @return none
     */
     function setCaBundleFile($fileName)
     {
        $this->requireCaBundle = true;
        $this->caBundleFile    = $fileName; //provide absolute path
     }

     /**
     * Checks whether proxy info is set
     * @param none
     * @return none
     */
     function isProxy()
     {
        if($this->proxyUrl == '')
        {
           return false;
        }

        return true;

     }//EO Method

     /**
     * Prepares contact list
     * @param none
     * @return none
     */
     function prepareContactList()
     {

       if($this->userId == "" || $this->password=="")
       {
         die("Set Login Info");
       }
       else
       {
         $this->postRequestToGmail();
       }

     }//EO Method

     /**
     * Gets contact list in a table
     * @param none
     * @return Contact List Table
     */
     function getResultTable()
     {
        if(!empty($this->contactListTable))
        {
           return $this->contactListTable;
        }
        else
        {
           return "List is Empty. Check your user id and password.";
        }
     }//EO Method

     /**
     * Gets contact list in an array
     * @param none
     * @return Contact List Array
     */
     function getResultArray()
     {
        if(strlen($this->contactListTable)>33)
        {
           $strValue = $this->contactListTable;
           $this->convertToArray($strValue);

           return $this->contactListArray;

        }
        else
        {
           return "List is Empty. Check your user id and password.";
        }
     }//EO Method

     /**
     /** Modified On 05/25/07
     * Post request to Gmail, gets and parses contact list from Gmail
     * @param none
     * @return none
     */
     function postRequestToGmail()
     {

        $requestString  = "service=mail&Email=".urlencode($this->userId)."&Passwd=".urlencode(str_replace(' ','+',$this->password))."&null=Sign%20in&continue=http%3A%2F%2Fmail.google.com%2Fmail%3F&rm=false&hl=en";
        $user_agent     = $_SERVER['HTTP_USER_AGENT'];
        $cookie         = 1;
        $cookieFileName = md5($this->userId);

        //setting cookie file. make sure the file has write permission
        $cookieFileJar  = (isset($this->cookiePath))?($this->cookiePath. "/" . $cookieFileName) : ($_SERVER['DOCUMENT_ROOT'] . "/temp_dir/" . $cookieFileName);

        $cookieFile     = $cookieFileJar;
        $refererUrl     = $this->refererUrl;

        $c = curl_init();
	      curl_setopt($c, CURLOPT_URL, $this->url);
	      curl_setopt($c, CURLOPT_HEADER, 1);
	      curl_setopt($c, CURLOPT_POST,   1);
	      //this referer tells Gmail that request is coming from Gmail
	      curl_setopt($c, CURLOPT_REFERER, $this->refererUrl);
	      curl_setopt($c, CURLOPT_POSTFIELDS, $requestString);

	      curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

	      //Certificate information
        if($this->requireCaBundle)
        {
          if(isset($this->caBundleFile))
          {
             curl_setopt($c, CURLOPT_CAINFO, $this->caBundleFile);
          }
          else
          {
             die("Provide CA Bundle File");
          }
        }

        if($this->isProxy())
        {
           curl_setopt($c, CURLOPT_PROXY, $this->proxyUrl);
	      }

	      curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);

	      curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
	      curl_setopt($c, CURLOPT_USERAGENT, $user_agent);

	      //writting cookie
	      curl_setopt($c, CURLOPT_COOKIEJAR, $cookieFileJar);
	      curl_setopt($c, CURLOPT_COOKIE, $cookie);

	      $res = curl_exec($c);

	      curl_close($c);

        $requestString ='http://mail.google.com/mail/h/ggggsdf/?v=cl&pnl=a&ui=html&zy=f';

        //New curl session
        $ch = curl_init();

	      curl_setopt($ch, CURLOPT_URL, $requestString);
	      curl_setopt($ch, CURLOPT_HEADER, 0);

	      curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
	      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	      //Certificate information
        if($this->requireCaBundle)
        {
          if(isset($this->caBundleFile))
          {
             curl_setopt($ch, CURLOPT_CAINFO, $this->caBundleFile);
          }
          else
          {
             die("Provide CA Bundle File");
          }
        }

        if($this->isProxy())
        {
           curl_setopt($ch, CURLOPT_PROXY, $this->proxyUrl);
	      }

	      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 	      curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

 	      //reading cookie
 	      curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);

 	      curl_setopt($ch, CURLOPT_COOKIE, $cookie);

	      $gmailResponse = curl_exec($ch);

	      curl_close($ch);

	      $parsedResponse  = $this->parseResponse($gmailResponse);

	      $this->contactListTable = $parsedResponse;

     }//EO Method

     /** Modified On 05/25/07
     * Converts table data to array data
     * @param Table of Contact List
     * @return Contact List Array
     */
     function convertToArray($strValue)
     {
        $strVal = str_replace("&nbsp;","",$strValue);
        $output = array();
        $strVal = nl2br($strVal);
        $strVal = str_replace("<br />",'',$strVal);

        ## added on 01/23/07
        $strVal = preg_replace("/<span[^>]*?>.*?<\/span>/si","", $strVal);
        $matches1 = preg_match_all("/<b>([^<]+)<\/b>/si",$strVal,$output, PREG_PATTERN_ORDER);
        $offset = array();
        $matches2 = preg_match_all("/<td[^>]*>([a-zA-Z0-9_\.]+@[a-zA-Z0-9_\.]+)?([^<]*)<\/td>/si",$strVal,$offset, PREG_PATTERN_ORDER);

        //putting values into associative array
        for($i=0;$i<sizeof($offset[2]); $i++)
        {
        	 $email = trim($offset[2][$i]);
        	 $name  = $output[1][$i];
           $this->contactListArray[$email] =$name;
        }

     }//EO Method

     /**
     * Parses Gmail Response String
     * @param Gmail Response
     * @return Parsed Response
     */
     function parseResponse($str)
     {
        $str = nl2br($str);
        $str = str_replace("<br />","",$str);
        $str = preg_replace("/<script[^>]*?>.*?<\/script>/si","", $str);
        $off = array();
        $matches = preg_match_all("/<table[^>]*?>.*?<\/table>/si", $str, $off,PREG_PATTERN_ORDER);
        $strVal = $off[0][6];

        return $strVal;

     }//EO Method

     /**
     * Debugs dump/data
     * @param $dump
     * @return none
     */
     function dBug($dump)
     {

        echo "<PRE>";
        print_r($dump);
        echo "</PRE>";

     }//EO Method

  }//EO Class

?>