<?php 
    $recurso = $_GET['recurso'];
    $method = $_SERVER['REQUEST_METHOD'];
    $id = $_GET['id'] ?? null;

    

    $curl = curl_init();

    if($id != null){
        curl_setopt($curl, CURLOPT_URL, "http://localhost/DPWDPLS/EC/Gerenciador-de-tarefas/public/index.php?recurso={$recurso}&id={$id}");
    }else{
        curl_setopt($curl, CURLOPT_URL, "http://localhost/DPWDPLS/EC/Gerenciador-de-tarefas/public/index.php?recurso={$recurso}");
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


    if($method == "GET"){
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        $resposta = curl_exec($curl);
        print_r($resposta);
        curl_close($curl);
    }
?>