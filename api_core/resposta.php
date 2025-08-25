<?php 
    class Resposta
    {
        public static function json($status = 200, $mensagem = 'sucesso', $data = null){

        header('Content-Type: application/json');
        if(!API_IS_ACTIVE) {
            return json_encode([
                'status' => 400,
                'mensagem' => 'api is not running',
                'api_version' => API_VERSION,
                'time_response' => time(),
                'datatime_response' => date('y-m-d H:i:s'),
                'data' => null
            ], JSON_PRETTY_PRINT);
        }
            return json_encode([
                'status' => $status,
                'mensagem' => $mensagem,
                'api_version' => API_VERSION,
                'time_response' => time(),
                'datatime_response' => date('y-m-d H:i:s'),
                'data' => $data
            ], JSON_PRETTY_PRINT);
        }
    }
?>