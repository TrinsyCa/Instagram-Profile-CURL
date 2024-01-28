<?php

    if($_GET) $username = $_GET["username"];

    if(isset($_GET["username"]) && $_GET["username"] != "")
    {
        $user = "https://www.instagram.com/".$username."/";

        $parsedUrl = parse_url(trim($user));

        $scheme = $parsedUrl['scheme']."://";
        $host = $parsedUrl['host'];
        $path = $parsedUrl['path'];

        $user_path = explode('/', $path);
        $user_path = "/".$user_path[1];

        $user = $scheme.$host.$user_path;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "$user/embed/",
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 8.0.0; SM-G930F Build/R16NW; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/102.0.5005.78 Mobile Safari/537.36[FBAN/EMA;FBLC/nl_NL;FBAV/306.0.0.13.107;]',
            CURLOPT_RETURNTRANSFER => true
        ]);

        $output = curl_exec($ch);
        curl_close($ch);

        $posts = 0;
        $followers = 0;
        $name_surname = "";

        //Username and Profile Photo
        preg_match('@\\\"profile_pic_url\\\":\\\"(.*?)\\\",\\\"username\\\":\\\"(.*?)\\\"@',$output,$result);

        if(isset($result[1]) && isset($result[2]))
        {
            $photo = str_replace('\\\\\\','',$result[1]);
            $username = $result[2];
            $profile_path = "profile/$username/";

            if (!file_exists($profile_path)) {
                if (mkdir($profile_path, 0777, true)) {
                    if(!file_exists($profile_path . "avatar.jpg"))
                    {
                        file_put_contents($profile_path."avatar.jpg",file_get_contents($photo));           
                    }
                }
            }
            $photo = $profile_path."avatar.jpg";
        }

        //Name Surname
        preg_match('@\\\"followers_count\\\":([0-9,]+),\\\"full_name\\\":\\\"(.*?)\\\"@',$output,$result);
        if(isset($result[1]))
        {
            $followers = number_format($result[1],0,",");
        }
        if(isset($result[2]))
        {
            $name_surname = json_decode('"'.str_replace("\\\\","\\",$result[2]).'"');
        }


        //Followers and posts
        preg_match('@\\\"edge_owner_to_timeline_media\\\":{\\\"count\\\":([0-9]+)@i',$output,$result);

        if(isset($result[1]))
        {
            $posts = number_format($result[1],0,",");
        }

        $userlink = "https://instagram.com/".$username;
    }
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
            if(isset($_GET["username"]) && $_GET["username"] != "") echo $name_surname." (@".$username.") Bilgileri";
            else echo "Instagram Profil Görüntüleme";
        ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-black">
    <div class="form">
        <div class="inputBx">
            <a href="./" class="box"><i class="fa-solid fa-house"></i></a>
            <input class="box" type="text" id="profile" name="username" placeholder="Instagram Kullanıcı Adı">
            <button class="box" onclick="goProfile();">Profili Görüntüle</button>
        </div>
    </div>

    <div class="profileBox">
        <?php if(isset($_GET["username"]) && $_GET["username"] != "") { ?>
            <h2><a href="<?=$userlink?>" target="_blank"><?=$name_surname?></a></h2>
            <h3><a href="<?=$userlink?>" target="_blank">@<?=$username?></a></h3>
            <a href="<?=$userlink?>" target="_blank" title="@<?=$username?>"><img src="<?=$photo?>" alt="@<?=$username?> avatar"></a>
            <p>Gönderi: <?=$posts?></p>
            <p>Takipçi: <?=$followers?></p>
        <?php } else { ?>
            <p>Instagram Profili Yaz</p>
        <?php } ?>
    </div>
    <script src="script.js"></script>
    <script src="https://kit.fontawesome.com/b40b33d160.js" crossorigin="anonymous"></script>
</body>
</html>