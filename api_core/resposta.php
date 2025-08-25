<?php 
    class Resposta
    {
        public static function json($status = 200, $mensagem = 'sucesso', $dados = null){

        header('Content-Type: application/json');
        if(!API_IS_ACTIVE) {
            return json_encode([
                'status' => 400,
                'mensagem' => 'api is not running',
                'api_version' => API_VERSION,
                'time_response' => time(),
                'datatime_response' => date('y-m-d H:i:s'),
                'dados' => null
            ], JSON_PRETTY_PRINT);
        }
            return json_encode([
                'status' => $status,
                'mensagem' => $mensagem,
                'api_version' => API_VERSION,
                'time_response' => time(),
                'datatime_response' => date('y-m-d H:i:s'),
                'dados' => $dados
            ], JSON_PRETTY_PRINT);
        }
    }
?>