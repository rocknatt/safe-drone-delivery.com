<?php 

if (!function_exists('get_param')) {
    

    function get_param($uri)
    {
        return strtolower(
            str_replace('%2E', '.',
                str_replace('%40', '@',
                    str_replace('%26', '&',
                        str_replace('%28', '(',
                            str_replace('%29', ')',
                                str_replace('%20', '+', 
                                    str_replace('+', '-', 
                                        str_replace('.', '/', 
                                            str_replace('-', ' ', $uri
        ))))))))));
            
    }

    function set_param($param)
    {
        return strtolower(
                str_replace(' ', '-', 
                    str_replace('/', '.',
                        str_replace('-', '+', 
                            str_replace('+', '%20',
                                str_replace('.', '%2E',
                                    str_replace('@', '%40', 
                                        str_replace('&', '%26', 
                                            str_replace('(', '%28', 
                                                str_replace(')', '%29', $param
        ))))))))));
    }

    function update_get($query)
    {
        $query_array = explode('&', $query);

        for ($i=0; $i < sizeof($query_array); $i++) { 
            $item = explode('=', $query_array[$i]);
            $index = $item[0];
            $value = '';
            if (isset($item[1])) {
                $value = $item[1];
            }
            $_GET[$index] = urldecode($value);
        }

    }

    
}

if (!function_exists('get_file_size_string')) {
    function get_file_size_string_to_ko($chiffre)
    {
        $result = to_numeric_string($chiffre, 0) . ' o';

        $reste = (int)($chiffre) / 1;
        for ($i=1000000000000; $i >= 1; $i /= 1000) { 
            $y = (int)($reste / $i);
            if ($y != 0)
            {
                switch ($i)
                {
                    // case 1000000000000:
                    //     $result = to_numeric_string($y, 0) . " To";
                    //     break;
                    // case 1000000000:
                    //     $result = to_numeric_string($y, 0) . " Go";
                    //     break;
                    case 1000000:
                        $result = to_numeric_string($y, 0) . " Mo";
                        break;
                    case 1000:
                        $result = to_numeric_string($chiffre, 0) . " Ko";
                        break;

                    case 1:
                        $result = to_numeric_string($chiffre, 0) . " Ko";
                        break;
                }
            }
        }

        return $result;
    }
}

if (!function_exists('to_more_suggest')) {
    function to_more_suggest($str)
    {
        $result = array($str);
        // array_push($result, str_replace(' ', '', $str));
        array_push($result, str_replace(' ', '', $str));

        if (strlen($str) > 3) {
            array_push($result, substr($str, 0, strlen($str) - 1));
            array_push($result, substr($str, 0, strlen($str) - 2));
            array_push($result, substr($str, 0, strlen($str) - 3));
        }

        return $result;
    }
}

if (!function_exists('to_string')) {
    function to_string($array, $delimiter = ',')
    {
        return implode(',', $array);
    }
}

if (!function_exists('enclose')) {
    function enclose($str, $delimiter = '\'')
    {
        return $delimiter . $str . $delimiter; 
    }
}

if (!function_exists('replace_for')) {
    function replace_for($str, $array = array(), $replace_to = '')
    {
        for ($i=0; $i < sizeof($array); $i++) { 
            $str = str_replace($array[$i], $replace_to, $str);
        }
        return $str;
    }
}
if (!function_exists('replace_by_associative_array')) {
    function replace_by_associative_array($str, $array = array())
    {
        foreach ($array as $key => $value) {
            $str = str_replace($key, $value, $str);
        }
        return $str;
    }
}
if (!function_exists('to_lower_trim')) {
    function to_lower_trim($str)
    {
        return strtolower(ltrim(rtrim($str)));
    }
}

if (!function_exists('to_numeric')) {
    function to_numeric($str)
    {
        if ($str == null || $str == '') {
            return 0;
        }
        return (preg_replace('#\s|%|[A-Za-z]#', '', mb_convert_encoding(str_replace(',', '.', $str), 'ASCII')));
    }
}

if (!function_exists('to_numeric_string')) {
    function to_numeric_string($str, $nb_after_point = 2)
    {
        return $str != '' ? number_format($str, $nb_after_point, ',', ' ') : ($str == '0' ? '0' : '');
    }
}

if (!function_exists('to_html_encode')) {
    function to_html_encode($str)
    {
        return str_replace('<', '&lt;', str_replace('>', '&gt;', str_replace('"', '&quot;', $str)));
    }
}

if (!function_exists('clean_jscode')) {
    function clean_jscode($script_str) {

        return preg_replace('#<script\b[^>]*>(.*?)<\/script>#is', '', $script_str);
    }
}

if (!function_exists('to_litteral')) {
    function to_litteral($chiffre, $money_unite = 'Ariary')
    {
        $CI =& get_instance();

        $centaine;
        $dizaine;
        $unite; 
        $reste;
        $y;
        $dix = false;
        $soixanteDix = false;
        $negative = false;
        $lettre = "";
        //strcp$y($lettre, "");
        $chiffre = floatval($chiffre);

        //On vérifie si le $chiffre est négative
        if ($chiffre < 0)
        {
            $negative = true;
            $chiffre = $chiffre * -1;
        }

        $reste = (int)($chiffre) / 1;

        for ($i = 1000000000; $i >= 1; $i /= 1000)
        {
            $y = (int)($reste / $i);
            if ($y != 0)
            {
                $centaine = (int)($y / 100);
                $dizaine = (int)(($y - $centaine * 100) / 10);
                $unite = (int)($y - ($centaine * 100) - ($dizaine * 10));
                switch ($centaine)
                {
                    case 0:
                        break;
                    case 1:
                        $lettre .= $CI->lang->line('std_hundred')  . ' ';
                        break;
                    case 2:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_two') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_two') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 3:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_three') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_three') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 4:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_four') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_four') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 5:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_five') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_five') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 6:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_six') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_six') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 7:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_seven') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_seven') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 8:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_eight') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_eight') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                    case 9:
                        if (($dizaine == 0) && ($unite == 0)) $lettre .= $CI->lang->line('std_nine') . ' ' . $CI->lang->line('std_hundreds') . ' ';
                        else $lettre .= $CI->lang->line('std_nine') . ' ' . $CI->lang->line('std_hundred') . ' ';
                        break;
                }// endSwitch($centaine)

                switch ($dizaine)
                {
                    case 0:
                        break;
                    case 1:
                        $dix = true;
                        break;
                    case 2:
                        $lettre .= $CI->lang->line('std_twenty') . ' ';
                        break;
                    case 3:
                        $lettre .= $CI->lang->line('std_thirty') . ' ';
                        break;
                    case 4:
                        $lettre .= $CI->lang->line('std_fourty') . ' ';
                        break;
                    case 5:
                        $lettre .= $CI->lang->line('std_fifty') . ' ';
                        break;
                    case 6:
                        $lettre .= $CI->lang->line('std_sixty') . ' ';
                        break;
                    case 7:
                        $dix = true;
                        $soixanteDix = true;
                        $lettre .= $CI->lang->line('std_sixty') . ' ';
                        break;
                    case 8:
                        $lettre .= $CI->lang->line('std_eighty') . ' ';
                        break;
                    case 9:
                        $dix = true;
                        $lettre .= $CI->lang->line('std_ninety') . ' ';
                        break;
                } // endSwitch($dizaine)

                switch ($unite)
                {
                    case 0:
                        if ($dix) $lettre .= $CI->lang->line('std_ten') . ' ';
                        break;
                    case 1:
                        if ($soixanteDix) $lettre .= $CI->lang->line('std_and_eleven') . ' ';
                        else
                            if ($dix) $lettre .= $CI->lang->line('std_eleven') . ' ';
                            else if (($dizaine != 1 && $dizaine != 0)) $lettre .= $CI->lang->line('std_and_one') . ' ';
                            else $lettre .= $CI->lang->line('std_one') . ' ';
                        break;
                    case 2:
                        if ($dix) $lettre .= $CI->lang->line('std_twelve') . ' ';
                        else $lettre .= $CI->lang->line('std_two') . ' ';
                        break;
                    case 3:
                        if ($dix) $lettre .= $CI->lang->line('std_thirteen') . ' ';
                        else $lettre .= $CI->lang->line('std_three') . ' ';
                        break;
                    case 4:
                        if ($dix) $lettre .= $CI->lang->line('std_fourteen') . ' ';
                        else $lettre .= $CI->lang->line('std_four') . ' ';
                        break;
                    case 5:
                        if ($dix) $lettre .= $CI->lang->line('std_fifteen') . ' ';
                        else $lettre .= $CI->lang->line('std_five') . ' ';
                        break;
                    case 6:
                        if ($dix) $lettre .= $CI->lang->line('std_sixteen') . ' ';
                        else $lettre .= $CI->lang->line('std_six') . ' ';
                        break;
                    case 7:
                        if ($dix) $lettre .= $CI->lang->line('std_seventeen') . ' ';
                        else $lettre .= $CI->lang->line('std_seven') . ' ';
                        break;
                    case 8:
                        if ($dix) $lettre .= $CI->lang->line('std_eighteen') . ' ';
                        else $lettre .= $CI->lang->line('std_eight') . ' ';
                        break;
                    case 9:
                        if ($dix) $lettre .= $CI->lang->line('std_nineteen') . ' ';
                        else $lettre .= $CI->lang->line('std_nine') . ' ';
                        break;
                } // endSwitch($unite)

                switch ($i)
                {
                    case 1000000000:
                        if ($y > 1) $lettre .= $CI->lang->line('std_billions') . ' ';
                        else $lettre .= $CI->lang->line('std_billion') . ' ';
                        break;
                    case 1000000:
                        if ($y > 1) $lettre .= $CI->lang->line('std_millions') . ' ';
                        else $lettre .= $CI->lang->line('std_million') . ' ';
                        break;
                    case 1000:
                        $lettre .= $CI->lang->line('std_thousand') . ' ';
                        break;
                }
            } // end if($y!=0)
            $reste -= (int)($y * $i);
            $dix = false;
            $soixanteDix = false;
        } // end for
        if (strlen($lettre) == 0) $lettre .= $CI->lang->line('std_zero');

        // pour les $chiffres apres la virgule :
        $chiffre3;

        $chiffre3 = (float)(($chiffre * 100) % 100);
        // Console.WriteLine($chiffre3);

        //On obtien les chiffres après virgule
        $str = strval($chiffre * 100);
        $str = substr($str, strlen($str) -2);
        $chiffre3 = intval($str);

        // $chiffre3 = (float)(($chiffre * 100) % 100);
        // Console.WriteLine($chiffre3);
        //int $chiffre2 = (($chiffre - ($chiffre/1))*100);
        //Console.WriteLine($chiffre2);

        $dizaine = (int)(($chiffre3) / 10);
        $unite = (int)($chiffre3 - ($dizaine * 10));

        $lettre2 = "";
        switch ($dizaine)
        {
            case 0:
                break;
            case 1:
                $dix = true;
                break;
            case 2:
                $lettre2 .= $CI->lang->line('std_twenty') . ' ';
                break;
            case 3:
                $lettre2 .= $CI->lang->line('std_thirty') . ' ';
                break;
            case 4:
                $lettre2 .= $CI->lang->line('std_fourty') . ' ';
                break;
            case 5:
                $lettre2 .= $CI->lang->line('std_fifty') . ' ';
                break;
            case 6:
                $lettre2 .= $CI->lang->line('std_sixty') . ' ';
                break;
            case 7:
                $dix = true;
                $soixanteDix = true;
                $lettre2 .= $CI->lang->line('std_sixty') . ' ';
                break;
            case 8:
                $lettre2 .= $CI->lang->line('std_eighty') . ' ';
                break;
            case 9:
                $dix = true;
                $lettre2 .= $CI->lang->line('std_ninety') . ' ';
                break;
        } // endSwitch($dizaine)

        switch ($unite)
        {
            case 0:
                if ($dix) $lettre2 .= $CI->lang->line('std_ten') . ' ';
                break;
            case 1:
                if ($soixanteDix) $lettre2 .= $CI->lang->line('std_and_eleven') . ' ';
                else
                    if ($dix) $lettre2 .= $CI->lang->line('std_eleven') . ' ';
                    else if (($dizaine != 1 && $dizaine != 0)) $lettre2 .= $CI->lang->line('std_and_one') . ' ';
                    else $lettre2 .= $CI->lang->line('std_one') . ' ';
                break;
            case 2:
                if ($dix) $lettre2 .= $CI->lang->line('std_twelve') . ' ';
                else $lettre2 .= $CI->lang->line('std_two') . ' ';
                break;
            case 3:
                if ($dix) $lettre2 .= $CI->lang->line('std_thirteen') . ' ';
                else $lettre2 .= $CI->lang->line('std_three') . ' ';
                break;
            case 4:
                if ($dix) $lettre2 .= $CI->lang->line('std_fourteen') . ' ';
                else $lettre2 .= $CI->lang->line('std_four') . ' ';
                break;
            case 5:
                if ($dix) $lettre2 .= $CI->lang->line('std_fifteen') . ' ';
                else $lettre2 .= $CI->lang->line('std_five') . ' ';
                break;
            case 6:
                if ($dix) $lettre2 .= $CI->lang->line('std_sixteen') . ' ';
                else $lettre2 .= $CI->lang->line('std_six') . ' ';
                break;
            case 7:
                if ($dix) $lettre2 .= $CI->lang->line('std_seventeen') . ' ';
                else $lettre2 .= $CI->lang->line('std_seven') . ' ';
                break;
            case 8:
                if ($dix) $lettre2 .= $CI->lang->line('std_eighteen') . ' ';
                else $lettre2 .= $CI->lang->line('std_eight') . ' ';
                break;
            case 9:
                if ($dix) $lettre2 .= $CI->lang->line('std_nineteen') . ' ';
                else $lettre2 .= $CI->lang->line('std_nine') . ' ';
                break;
        }

        // on enleve le un devant le mille :
        if (substr($lettre, 0, 8) == $CI->lang->line('std_one') . ' ' . $CI->lang->line('std_thousand'))
        {
            //Console.WriteLine("on enleve le un devant le mille");
            $lettre = substr($lettre, 0, 3);
        }

        if ($negative)
        {
            $lettre = $CI->lang->line('std_minus') . " " . $lettre;
        }
        
        if ($lettre2 == '')
            return $lettre . ' ' . $money_unite;
        else if ($dizaine == 0 && $unite == 1)
            return $lettre . ' ' . $money_unite . " " . $CI->lang->line('std_and') . " " . $lettre2 . "centime";
        else
            return $lettre . ' ' . $money_unite . " " . $CI->lang->line('std_and') . " " . $lettre2 . "centimes";
    }
}

if (!function_exists('get_first_letter')) {
    function get_first_letter($str)
    {
        if ($str == '') {
            return '';
        }

        return substr(strtoupper($str), 0, 1);
    }
}
if (!function_exists('get_initial')) {
    function get_initial($str, $nb = 2)
    {
        $adr = explode(' ', $str);
        $str = '';
        for ($i=0; $i < $nb; $i++) { 
            if (isset($adr[$i])) {
                $str .= get_first_letter($adr[$i]);
            }
        }

        return $str;
    }
}

if (!function_exists('get_text_link')) {
    function get_text_link($str)
    {
        if (!preg_match('#^[http://|https://|ftp://|file://]#', $str)) {
            return 'http://' . $str;
        }

        return $str;
    }
}

if (!function_exists('get_limited_string')) {
    function get_limited_string($str, $length=75)
    {
        if (mb_strlen($str) > $length) {
            return mb_substr($str, 0, $length) . ' ...';
        }

        return $str;
    }
}

if (!function_exists('get_first_word')) {
    function get_first_word($str)
    {
        $adr = explode(' ', $str);
        return isset($adr[0]) ? $adr[0] : '';
    }
}

//LWZ compression aloghorithme
if (!function_exists('compress')) {
    function compress($unc) {
        $i;$c;$wc;
        $w = "";
        $dictionary = array();
        $result = array();
        $dictSize = 256;
        for ($i = 0; $i < 256; $i += 1) {
            $dictionary[chr($i)] = $i;
        }
        for ($i = 0; $i < strlen($unc); $i++) {
            $c = $unc[$i];
            $wc = $w.$c;
            if (array_key_exists($w.$c, $dictionary)) {
                $w = $w.$c;
            } else {
                array_push($result,$dictionary[$w]);
                $dictionary[$wc] = $dictSize++;
                $w = (string)$c;
            }
        }
        if ($w !== "") {
            array_push($result,$dictionary[$w]);
        }
        return implode(",",$result);
    }
}

if (!function_exists('decompress')) {
    function decompress($com) {
        $com = explode(",",$com);
        $i;$w;$k;$result;
        $dictionary = array();
        $entry = "";
        $dictSize = 256;
        for ($i = 0; $i < 256; $i++) {
            $dictionary[$i] = chr($i);
        }
        $w = chr($com[0]);
        $result = $w;
        for ($i = 1; $i < count($com);$i++) {
            $k = $com[$i];
            if ($dictionary[$k]) {
                $entry = $dictionary[$k];
            } else {
                if ($k === $dictSize) {
                    $entry = $w.$w[0];
                } else {
                    return null;
                }
            }
            $result .= $entry;
            $dictionary[$dictSize++] = $w . $entry[0];
            $w = $entry;
        }
        return $result;
    }
}

if (! function_exists('get_displayname_from_rfc_email')) {
    function get_displayname_from_rfc_email($rfc_email_string) {

        //Si le nom est codé en Base64
        if (preg_match('#^\=\?#', $rfc_email_string)) {
            $adr = explode('<', $rfc_email_string);

            mb_internal_encoding('UTF-8');
            $adr[0] = trim($adr[0]);
            return str_replace("_"," ", mb_decode_mimeheader($adr[0]));
        }

        // match all words and whitespace, will be terminated by '<'
        $name = preg_match('/[\w\s]+/', $rfc_email_string, $matches);
        if (isset($matches[0])) {
            $matches[0] = trim($matches[0]);
            return $matches[0];
        }
        return '';
    }
}

if (! function_exists('get_email_from_rfc_email')) {
    function get_email_from_rfc_email($rfc_email_string) {
        // extract parts between the two parentheses
        $mailAddress = preg_match('/(?:<)(.+)(?:>)$/', $rfc_email_string, $matches);
        return isset($matches[1]) ? $matches[1] : $rfc_email_string;
    }
}


if (! function_exists('multiexplode')) {
    function multiexplode ($delimiters,$string) {
   
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }
}

if (! function_exists('valid_email')) {
    function valid_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}

/**
 * Ratchet Websocket Library: helper file
 * @author Romain GALLIEN <romaingallien.rg@gmail.com>
 */
if (!function_exists('valid_json')) {

    /**
     * Check JSON validity
     * @method valid_json
     * @author Romain GALLIEN <romaingallien.rg@gmail.com>
     * @param  mixed  $var  Variable to check
     * @return bool
     */
    function valid_json($var) {
        return (is_string($var)) && (is_array(json_decode($var, true))) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('get_emoji_data_base')) {
    function get_emoji_data_base()
    {

        return array(
            //Emoticone
            'emoticone' => array(
                ':)|:-)|U+1F603' => '😃',
                ':D|:-D|U+1F604' => '😄',
                ':\'D||U+1F605' => '😅',
                'XD|U+1F606' => '😆',
                ';)|;-)|U+1F609' => '😉',
                'w)|w-)|U+1F60A' => '😊',
                'w-p|U+1F60B' => '😋',
                '(w))|U+1F60C' => '😌',
                '<3D|<3-D|U+1F60D' => '😍',
                'U+1F60F' => '😏',
                'U+1F612' => '😒',
                'U+1F613' => '😓',
                'U+1F614' => '😔',
                ':w|U+1F616' => '😖',
                ':*|:-*|U+1F618' => '😘',
                '3:)|3:-)|U+1F608' => '😈',
                '8:)|8-)|U+1F60E' => '😎',
                '0:)|0:-)|U+1F607' => '😇',
                'U+1F61A' => '😚',
                ';p|;-p|U+1F61C' => '😜',
                'X-P|U+1F61D' => '😜',
                ':(|:-(|U+1F61E' => '😞',
                'v(|v-(|U+1F620' => '😠',
                'v.(|v.-(|U+1F621' => '😡',
                ':\'(|:\'-(|U+1F622' => '😢',
                'x(|x-(|U+1F623' => '😣',
                ':<|:-<|U+1F624' => '😤',
                'U+1F625' => '😥',
                'U+1F628' => '😨',
                'U+1F629' => '😩',
                'U+1F62A' => '😪',
                'U+1F62D' => '😭',
                ':=|:-=|U+1F62D' => '😭',
                'U+1F631' => '😱',
                'U+1F632' => '😲',
                'U+1F633' => '😳',
                'U+1F635' => '😵',
                'U+1F637' => '😷',
            ),
            'hand' => array(
                'U+1F450' => '👐',
                ':strong:|U+1F4A9' => '💪',
                'U+1F446' => '✊',
                'U+1F447' => '✋',
                'U+1F448' => '✌',
                'U+1F449' => '👆',
                'U+1F44A' => '👇',
                'U+1F44B' => '👈',
                'U+1F44C' => '👉',
                'U+1F44D' => '👊',
                'U+1F44E' => '👋',
                'U+1F44F' => '👌',
                'U+261D' => '👍',
                'U+270A' => '👎',
                'U+270B' => '👏',
                'U+270A' => '☝',
                'U+270B' => '🙌',
                'U+270C' => '✌',
                'U+270D' => '✍',
                'U+270E' => '✎',
                'U+270F' => '✏',
                'U+2710' => '✐',
                'U+2711' => '✑',
                'U+2712' => '✒',
                'U+2713' => '✓',
                'U+2714' => '✔',
                'U+2715' => '✕',
                'U+2716' => '✖',
                'U+2717' => '✗',
                'U+2718' => '✘',
                'U+2719' => '✙',
                'U+271A' => '✙',
                'U+271B' => '✚',
                'U+271C' => '✛',
                'U+271D' => '✜',
                'U+271E' => '✝',
                'U+271F' => '✟',
                // 'U+271F' => '&#x271F;',
            ),
            'other' => array(
                ':U+1F4A0' => '💠',
                ':U+1F4A1' => '💡',
                ':U+1F4A2' => '💢',
                ':U+1F4A3' => '💣',
                ':U+1F4A4' => '💤',
                ':U+1F4A5' => '💥',
                ':U+1F4A6' => '💦',
                ':U+1F4A7' => '💧',
                ':U+1F4A8' => '💨',
                ':poop:|U+1F4A9' => '💩',
                ':U+1F4AB' => '💫',
                ':U+1F4AC' => '💬',
                ':U+1F4AD' => '💭',
                ':U+1F4AE' => '💮',
                ':U+1F4AF' => '💯',
                
                
            )
            
        );
    }
}
if (!function_exists('search_emoji')) {
    function search_emoji($str)
    {
        if ($str == null || $str == '') {
            return '';
        }

        $emoji_list = get_emoji_data_base();

        foreach ($emoji_list as $index => $emoji_hex) {
            $emoji_index = explode('|', $index);
            $str = str_replace($emoji_index, $emoji_hex, $str);
        }

        return $str;
    }
}

if (!function_exists('contain_only_emoji')) {
    function contain_only_emoji($str)
    {
        // echo $str;
        if ($str == null || $str == '') {
            return false;
        }

        $str = search_emoji($str);

        return preg_match('#[&\#x0-9 A-F\;]{1,};$#', strval($str));
    }
}

if ( ! function_exists('get_icon_notification_type'))
{
    function get_icon_notification_type($str)
    {
        $file_type_icon = array(
            'explorer' => 'fa-folder',
            'application/pdf' => 'fa-file-pdf-o',
        );
        return isset($file_type_icon[$str]) ? $file_type_icon[$str] : 'fa-file-o';
    }
}

if (!function_exists('get_tag_view')) {
    function get_tag_view($str)
    {
        $str = mb_strtolower($str);
        $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
        $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
        $str = str_replace($search, $replace, $str);

        return $str = preg_replace('/[^\w]+/', '-', $str);
    }
}

if (!function_exists('get_tag_name')) {
    function get_tag_name($str)
    {
        $str = mb_strtolower($str);
        $search = explode(",","ç,æ,œ,á,é,í,ó,ú,à,è,ì,ò,ù,ä,ë,ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u");
        $replace = explode(",","c,ae,oe,a,e,i,o,u,a,e,i,o,u,a,e,i,o,u,y,a,e,i,o,u,a,e,i,o,u");
        $str = str_replace($search, $replace, $str);

        return '@'. preg_replace('/[^\w]+/', '-', $str);
    }
}
