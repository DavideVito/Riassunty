<?php 

class CloudConvert
{
    private $apiKey = "DBoE1fW6G8G81qMqj3iwDObVfO0iclHKPYx3bNFKiuMfnicW-G-hrjn9GxbMnEEe3fPaVVTi91YEXkhXESt_gw";
    
    private $endPoint = "https://api.cloudconvert.com/v1/process";


    public function __constuct($apiKey)
    {
        /*
        if($apiKey == NULL)
        {
            $this->apiKey = "DBoE1fW6G8G81qMqj3iwDObVfO0iclHKPYx3bNFKiuMfnicW-G-hrjn9GxbMnEEe3fPaVVTi91YEXkhXESt_gw";
        }
        $this->apiKey = $apiKey;*/
           
    }

    public function preparaConversione($fileInput = "pdf", $fileOutput = "html")
    {
        $url = $this->endPoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        $parametri = array();
        $parametri['apikey'] = $this->apiKey;
        $parametri['inputformat'] = $fileInput;
        $parametri['outputformat'] = $fileOutput;
        
        $parametri = http_build_query($parametri);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $parametri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $rispostaServer = curl_exec($ch);
        curl_close ($ch);
        $rispostaServer = json_decode($rispostaServer, true);
        var_dump($rispostaServer);
        echo "<br><br><br><br>";
        return "https:" . $rispostaServer["url"];
        
    }

    public function iniziaConversione($urlEndpoint, $file, $outputformat = "html", $input = "download", $wait = "true")
    {
        
        $parametri = array();
        echo $file . "<br><br><br><br>";
        $parametri['input'] = $input;
        $parametri['file'] = /*"https://" . */$file;

        $parametri['outputformat'] = $outputformat;
        $parametri['wait'] = $wait;

        $parametri = http_build_query($parametri);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $urlEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $parametri);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
        $rispostaServer = curl_exec($ch);

        $e1 = curl_error($ch); 
        $e2 = curl_errno($ch);


        $rispostaServer = json_decode($rispostaServer, true);
        var_dump($rispostaServer);
        return "https:" . $rispostaServer['output']['url']. "/" . $rispostaServer['output']['filename'];
       
    }

    public function salvaFile($urlFile, $dove)
    {
        $testo = file_get_contents($urlFile);

        $file = fopen($dove, "w");

        fwrite($file, $testo);

        fclose($file);

    }
}




