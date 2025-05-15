<?php

class ApiController extends Controller {
    public function index() {
        $this->outputJson([
            'status' => 'success',
            'message' => 'API de Recetario - Bienvenido',
            'endpoints' => [
                'GET /recipe' => 'Obtener todas las recetas',
                'GET /recipe/get/{id}' => 'Obtener una receta por ID',
                'POST /recipe/create' => 'Crear una nueva receta',
                'PUT /recipe/update/{id}' => 'Actualizar una receta existente',
                'DELETE /recipe/delete/{id}' => 'Eliminar una receta'
            ]
        ]);
    }

    private function outputJson($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}