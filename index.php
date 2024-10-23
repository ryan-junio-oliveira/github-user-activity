<?php

echo "ATIVIDADES DO USUÁRIO";
echo "\n------------------------------------------------------------------";

echo "\nPor favor, forneça o nome de usuário para saber as atividades mais recentes: ";
$line = trim(fgets(STDIN));

if (empty($line)) {
    echo "Erro: Nome de usuário não pode estar vazio.\n";
    exit(1);
}

$headers = [
    'User-Agent: PHP-Script',
    'Accept: application/vnd.github.v3+json'
];

$ch = curl_init("https://api.github.com/users/{$line}/events");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "Erro: Não foi possível obter os eventos para o usuário '{$line}'. Verifique se o nome do usuário está correto.\n";
    exit(1);
}

$events = json_decode($response, true);

if (empty($events)) {
    echo "Nenhum evento encontrado para o usuário '{$line}'.\n";
} else {
    echo "\nEventos mais recentes para o usuário '{$line}':\n";
    echo "------------------------------------------------------------------\n";
    
    foreach ($events as $event) {
        $eventType = $event['type'];
        $repoName = $event['repo']['name'];
        $createdAt = $event['created_at'];

        echo "Tipo: {$eventType}\n";
        echo "Repositório: {$repoName}\n";
        echo "Data: {$createdAt}\n";
        echo "------------------------------------------------------------------\n";
    }
}
