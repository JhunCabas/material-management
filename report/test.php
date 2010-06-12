<?php
function WordLimiter($text,$limit=20){
    $explode = explode(' ',$text);
    $string  = '';

    $dots = '...';
    if(count($explode) <= $limit){
        $dots = '';
    }
    for($i=0;$i<$limit;$i++){
        $string .= $explode[$i]." ";
    }
    if ($dots) {
        $string = substr($string, 0, strlen($string));
    }

    return $string.$dots;
}

$text = "Hello this is a list of words that is too long";
echo '1: ' . WordLimiter( $text );
$text = "Hello this is a list of words";
echo '2: ' . WordLimiter( $text );
?>
