<?php
namespace PlayFabSDK;
use PlayFabHttp;

include 'PlayFabHttp.php';

class PlayFabAuthenticationApi
{
    /// <summary>
    /// Method to exchange a legacy AuthenticationTicket or title SecretKey for an Entity Token or to refresh a still valid
    /// Entity Token.
    /// https://docs.microsoft.com/rest/api/playfab/authentication/authentication/getentitytoken
    /// </summary>
    public static function GetEntityToken($titleId, $entityToken, $clientSessionTicket, $developerSecreteKey, $request)
    {
        if (!is_null($entityToken)) {
            $authKey = "X-EntityToken";
            $authValue = $entityToken;
        } elseif (!is_null($clientSessionTicket)) {
            $authKey = "X-Authorization";
            $authValue = $clientSessionTicket;
        } elseif (!is_null($developerSecreteKey)) {
            $authKey = "X-SecretKey";
            $authValue = $developerSecreteKey;
        }

        $result = PlayFabHttp::MakeCurlApiCall($titleId, "/Authentication/GetEntityToken", $request, $authKey, $authValue);
        return $result;
    }

    /// <summary>
    /// Method to exchange a legacy AuthenticationTicket or title SecretKey for an Entity Token or to refresh a still valid
    /// Entity Token.
    /// https://docs.microsoft.com/rest/api/playfab/authentication/authentication/getentitytoken
    /// </summary>
    public static function GetEntityTokenWithEntityToken($titleId, $entityToken, $request)
    {
        //TODO: Check the entityToken

        $result = PlayFabHttp::MakeCurlApiCall($titleId, "/Authentication/GetEntityToken", $request, "X-EntityToken", $entityToken);
        return $result;
    }

    /// <summary>
    /// Method to exchange a legacy AuthenticationTicket or title SecretKey for an Entity Token or to refresh a still valid
    /// Entity Token.
    /// https://docs.microsoft.com/rest/api/playfab/authentication/authentication/getentitytoken
    /// </summary>
    public static function GetEntityTokenWithSessionTicket($titleId, $clientSessionTicket, $request)
    {
        //TODO: Check the sessionTicket

        $result = PlayFabHttp::GetEntityToken($titleId, "/Authentication/GetEntityToken", $request, "X-Authorization", $clientSessionTicket);
        return $result;
    }

    /// <summary>
    /// Method to exchange a legacy AuthenticationTicket or title SecretKey for an Entity Token or to refresh a still valid
    /// Entity Token.
    /// https://docs.microsoft.com/rest/api/playfab/authentication/authentication/getentitytoken
    /// </summary>
    public static function GetEntityTokenWithSecretKey($titleId, $developerSecreteKey, $request)
    {
        //TODO: Check the devSecretKey

        $result = PlayFabHttp::GetEntityToken($titleId, "/Authentication/GetEntityToken", $request, "X-SecretKey", $developerSecreteKey);
        return $result;
    }

    /// <summary>
    /// Method for a server to validate a client provided EntityToken. Only callable by the title entity.
    /// https://docs.microsoft.com/rest/api/playfab/authentication/authentication/validateentitytoken
    /// </summary>
    public static function ValidateEntityToken($titleId, $entityToken, $request)
    {
        //TODO: Check the entityToken

        $result = PlayFabHttp::MakeCurlApiCall($titleId, "/Authentication/ValidateEntityToken", $request, "X-EntityToken", $entityToken);
        return $result;
    }

}

?>
