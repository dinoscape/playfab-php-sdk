<?php

namespace PlayFabSDK;
if (!class_exists("PlayFabSDK\PlayFabSettings")) {
    class PlayFabSettings
    {
        public static $versionString = "PhpSdk-0.0.201014";
        public static $requestGetParams = array("sdk" => "0.0.201014");
        public static $productionEnvironmentUrl = ".playfabapi.com"; // This is only for customers running a private cluster.  Generally you shouldn't touch this
        public static $enableCompression = false; // Can't get this to work
        public static $verticalName = null; // The name of a customer vertical. This is only for customers running a private cluster. Generally you shouldn't touch this
        public static $titleId = null;
        public static $disableSsl = false;

        public static function GetFullUrl($titleId, $apiPath, $getParams)
        {
            if (!isset($titleId) && isset(PlayFabSettings::$titleId))
                $titleId = PlayFabSettings::$titleId;

            $output = "";
            if (!(substr(self::$productionEnvironmentUrl, 0, 4) === "http")) {
                if (isset(self::$verticalName)) {
                    $output = "https://" . self::$verticalName;
                } else {
                    $output = "https://" . $titleId;
                }
            }

            $output .= self::$productionEnvironmentUrl;
            $output .= $apiPath;

            $firstParam = True;
            foreach ($getParams as $key => $value) {
                if ($firstParam) {
                    $output = $output . "?";
                    $firstParam = False;
                } else {
                    $output = $output . "&";
                }

                $output = $output . $key . "=" . $value;
            }

            return $output;
        }
    }
}

if (!class_exists("PlayFabHttp")) {
    class PlayFabHttp
    {
        public static function MakeCurlApiCall($titleId, $apiPath, $request, $authKey, $authValue)
        {
            $fullUrl = PlayFabSettings::GetFullUrl($titleId, $apiPath, PlayFabSettings::$requestGetParams);
            $payload = json_encode($request);

            // ApiCall Headers
            $httpHeaders = [
                "Content-Type: application/json",
                "X-ReportErrorAsSuccess: true",
                "X-PlayFabSDK: " . PlayFabSettings::$versionString
            ];
            if (isset($authKey) && isset($authValue))
                array_push($httpHeaders, $authKey . ": " . $authValue);

            // Compression
            if (PlayFabSettings::$enableCompression) {
                array_push($httpHeaders, "Content-Encoding: GZIP");
                array_push($httpHeaders, "Accept-Encoding: GZIP");
                $payload = gzcompress($payload);
            }

            // Perform the call
            $ch = curl_init($fullUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            if (PlayFabSettings::$disableSsl) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // TODO: DON'T PUBLISH WITH THIS
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeaders);
            $rawResult = curl_exec($ch);

            if ($rawResult === false)
                echo "cUrl Error: " . curl_error($ch) . "\n\n";

            curl_close($ch);


            return $rawResult;
        }

        // public static function MakeHttpRequestApiCall($titleId, $apiPath, $request, $authKey, $authValue)
        // {
        // $fullUrl = PlayFabSettings::GetFullUrl($titleId, $apiPath, PlayFabSettings::$requestGetParams);
        // $httpRequest = new HttpRequest($fullUrl, HttpRequest::METH_POST);
        // $requestJson = json_encode($request);

        // // ApiCall Headers
        // $httpHeaders = [
        // "Content-Type" => "application/json",
        // "X-ReportErrorAsSuccess" => "true",
        // "X-PlayFabSDK" => PlayFabSettings::$versionString
        // ];
        // if ($authKey && $authValue)
        // $httpHeaders[$authKey] = $authValue;

        // // Compression
        // if (PlayFabSettings::$enableCompression) {
        // $httpHeaders[$authKey] = $authValue;
        // $httpHeaders["Content-Encoding"] = "GZIP";
        // $httpHeaders["Accept-Encoding"] = "GZIP";
        // $requestJson = gzcompress($requestJson);
        // }

        // // Perform the call
        // $httpRequest->addHeaders($httpHeaders);

        // try {
        // $httpMessage = $httpRequest->send();
        // $responseHeaders = $httpMessage->getHeaders();
        // $responseJson = $httpMessage->getBody();

        // $responseEncoding = $responseHeaders["Content-Encoding"];
        // if (isset($responseEncoding) && $responseEncoding === "GZIP")
        // $responseJson = gzdecode($responseJson);
        // return $responseJson;
        // } catch (HttpException $ex) {
        // return $ex;
        // }
        // }
    }
}

?>
