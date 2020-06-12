<?php

class ApiHelper
{
    private const session_timeout = 60;

    public static function get($url, $params){

        if( !function_exists('curl_version') ){
            return false;
        }

        // クエリを連結
        $url .= count($params) > 0 ? '?' . http_build_query($params) : "";

        $headers = [
            'Content-type: application/json; charset=UTF-8',
            'Accept: application/json'
        ];

        // curlオブジェクト作成
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_TIMEOUT, self::session_timeout);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);

        // コネクション
        $result = curl_exec( $ch );
        $response = curl_getinfo( $ch, CURLINFO_HTTP_CODE);

        // コネクションクローズ
        curl_close( $ch );

        return $response === 200 ? json_decode( $result ) : false;
    }
}