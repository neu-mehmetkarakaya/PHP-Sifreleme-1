<html>
    <head>
        <title>ŞİFRELEME</title>
    </head>
</html>

<?php
    
    // Sifrelenmiş metin
    $metin = "phukded gxqbd";

    // Türkçe olmayan metinlerini gösteren diziler
    $turkceOlmayan = new SplFixedArray(29);
    $resArr = array();

        // Gerçek şifrenin ne olduğu gösteren fonksiyon ve geri döndürme
        function sifre($ch, $key)
        {
        if (!ctype_alpha($ch))
        return $ch;

        // Büyük harf ve küçük harf tanımı
        $offset = ord(ctype_upper($ch) ? 'A' : 'a');
        return chr(fmod(((ord($ch) + $key) - $offset), 26) + $offset);
        }

        function Esifre($input, $key)
        {
        $output = "";

        $inputArr = str_split($input);
        foreach ($inputArr as $ch)
        $output .= sifre($ch, $key);

        return $output;
        }

        function Desifre($input, $key)
        {
        return Esifre($input, 26 - $key);
        }

        // Site yüklenme ayarları
        function get_web_page($url) 
        {
        $ayar = array(
        CURLOPT_RETURNTRANSFER => true, 
        CURLOPT_HEADER => false, 
        CURLOPT_FOLLOWLOCATION => true, 
        CURLOPT_MAXREDIRS => 10, 
        CURLOPT_ENCODING => "", 
        CURLOPT_USERAGENT => "test", 
        CURLOPT_AUTOREFERER => true, 
        CURLOPT_CONNECTTIMEOUT => 120,
        CURLOPT_TIMEOUT => 120,
        ); 

        $ch = curl_init($url);
        curl_setopt_array($ch, $ayar);

        $bilgi = curl_exec($ch);
        curl_close($ch);

        return $bilgi;
        }

        // Kaçıncı dizide kaç tane türkçe sözcük olduğunu gösteren dizilerin tanımı
        for ($i = 0; $i <= 28; $i++) 
        {
        $turkceOlmayan[$i] = 0;
        }

            for ($x = 1; $x <= 28; $x++) 
            {
            echo $x."-";
    
            $gercekMetin = Esifre($metin, $x);
            echo $gercekMetin."<br>";
    
        $array = explode(' ', $gercekMetin);
        
        // Dizilerin döngüsü sonunda türkçe sözcüklerin kontrolü için kullanılan sözlük sitesi givi 
        foreach ($array as $values)
        {
        $response = get_web_page("https://sozluk.gov.tr/gts?ara=".$values);
        $resArr = json_decode($response, true);
    
            if( !isset( $resArr['error'] ) )
            {
            $turkceOlmayan[$x]+=1;
            }
        }
    }
    print_r($turkceOlmayan);
?>