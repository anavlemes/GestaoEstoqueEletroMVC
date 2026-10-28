<?php
require_once __DIR__ . '/../CONFIG/config.php';

// Autoloader compatível com maiúsculas/minúsculas da estrutura do projeto
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../APP/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $parts = explode('\\', $relative_class);
    
    for ($i = 0; $i < count($parts) - 1; $i++) {
        $parts[$i] = strtoupper($parts[$i]);
    }
    
    $file = $base_dir . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        $fallback = __DIR__ . '/../app/' . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($fallback)) {
            require_once $fallback;
        }
    }
});

use App\Core\Request;
use App\Views\JsonView;
use App\Controllers\EletronicoController;
use App\Controllers\EstoqueController;

$method = Request::method();
$path = Request::path();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Roteamento
if ($method === 'GET' && $path === '/health') {
    JsonView::render(["status" => "ok", "mensagem" => "API Gestão de Estoque operacional."]);
} elseif ($method === 'GET' && $path === '/eletronico') {
    (new EletronicoController())->listar();
} elseif ($method === 'POST' && $path === '/eletronico') {
    (new EletronicoController())->criar();
} elseif ($method === 'PUT' && $path === '/eletronico') {
    (new EletronicoController())->atualizar();
} elseif ($method === 'DELETE' && $path === '/eletronico') {
    (new EletronicoController())->excluir();
} elseif ($method === 'GET' && $path === '/eletronicoId') {
    (new EletronicoController())->porId();
} elseif ($method === 'GET' && $path === '/eletronicoNome') {
    (new EletronicoController())->porNome();
} elseif ($method === 'GET' && $path === '/eletronicoDescricao') {
    (new EletronicoController())->porDescricao();
} elseif ($method === 'GET' && $path === '/eletronicoLote') {
    (new EletronicoController())->porLote();
} elseif ($method === 'GET' && $path === '/resumo') {
    (new EletronicoController())->resumo();
} elseif ($method === 'PUT' && $path === '/estoque') {
    (new EstoqueController())->movimentar();
} elseif ($method === 'GET' && $path === '/movimentacoes') {
    (new EstoqueController())->historico();
} else {
    JsonView::render(["erro" => true, "mensagem" => "Rota não encontrada."], 404);
}