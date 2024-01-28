<?php

    // Instagram (Gönderi veya Reels) Linkini Tırnakların İçerisine Ekle

    $post = "        https://www.instagram.com/p/C2nCdgchArD/        ";
    
    // Instagram (Gönderi veya Reels) Linkini Tırnakların İçerisine Ekle

    $parsedUrl = parse_url(trim($post));

    $scheme = $parsedUrl['scheme']."://";
    $host = $parsedUrl['host'];
    $path = $parsedUrl['path'];

    $post_path = explode('/', $path);
    $post_path = "/".$post_path[1]."/".$post_path[2];

    $post = $scheme.$host.$post_path;

    echo "Eklediğin Link: ".$post."<br><br><br>";

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => "$post/embed/",
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G930F Build/R16NW; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/102.0.5005.78 Mobile Safari/537.36[FBAN/EMA;FBLC/nl_NL;FBAV/306.0.0.13.107;]',
        CURLOPT_RETURNTRANSFER => true
    ]);

    $output = curl_exec($ch);
    curl_close($ch);

    $posts = 0;
    $followers = 0;

    preg_match('@<span class="FollowerCountText">([0-9,]+) followers</span>@',$output,$result);

    if(isset($result[1]))
    {
        $followers = str_ireplace(",","",$result[1]);
    }
    else
    {
        preg_match('@{\\\"count\\\":([0-9]+)}@i',$output,$result);

        if(isset($result[1]))
        {
            $followers = number_format($result[1],0,",");
        }
        else
        {
            preg_match('@([0-9]+) posts · ([0-9]+)(.*?) followers@',$output,$result);
        
            if(isset($result[1]))
            {
                $posts = $result[1];
            }
            if(isset($result[2]))
            {
                $followers = $result[2];
            }
            if(isset($result[3]))
            {
                $followers .= $result[3] . " <br><br>(Onaylı IG hesaplarının tam takipçisini görmek için reels yerine gönderi bağlantısı ekle)";
            }
        }
    }

    /* preg_match('@{\\\"posts_count\\\":([0-9]+)}@i',$output,$result);
    print_r($result);
    if($result[1])
    {
        $posts = number_format($result[1],0,",");
    } */

    if($posts <= 0) { echo "Gönderi: Şu anda sadece reels bağlantılarında çalışıyor"; } 
    else { echo "Gönderi: ".$posts; }
    echo "<br>";
    echo "Takipçi: ".$followers;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-black">
    
</body>
</html>