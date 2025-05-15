<?php

class RecipeController extends Controller {
    private $recipeModel;

    public function __construct() {
        $this->recipeModel = $this->model('RecipeModel');
    }

    public function index() {
        try {
            $recipes = $this->recipeModel->getAllRecipes();
            $this->outputJson(['status' => 'success', 'data' => $recipes]);
        } catch (Exception $e) {
            $this->outputJson(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function get($id = null) {
        if (!$id) {
            $this->outputJson(['status' => 'error', 'message' => 'ID de receta no especificado'], 400);
            return;
        }

        try {
            $recipe = $this->recipeModel->getRecipeById($id);

            if ($recipe) {
                $this->outputJson(['status' => 'success', 'data' => $recipe]);
            } else {
                $this->outputJson(['status' => 'error', 'message' => 'Receta no encontrada'], 404);
            }
        } catch (Exception $e) {
            $this->outputJson(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->outputJson(['status' => 'error', 'message' => 'Método no permitido'], 405);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->outputJson(['status' => 'error', 'message' => 'Datos de entrada inválidos'], 400);
            return;
        }

        if (empty($data['tituloReceta']) || empty($data['description']) || empty($data['ingredients'])) {
            $this->outputJson(['status' => 'error', 'message' => 'Faltan campos requeridos'], 400);
            return;
        }

        try {
            $recipeId = $this->recipeModel->createRecipe($data);
            $this->outputJson([
                'status' => 'success',
                'message' => 'Receta creada correctamente',
                'id' => $recipeId
            ], 201);
        } catch (Exception $e) {
            $this->outputJson(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'PATCH') {
            $this->outputJson(['status' => 'error', 'message' => 'Método no permitido'], 405);
            return;
        }

        if (!$id) {
            $this->outputJson(['status' => 'error', 'message' => 'ID de receta no especificado'], 400);
            return;
        }

        $recipe = $this->recipeModel->getRecipeById($id);
        if (!$recipe) {
            $this->outputJson(['status' => 'error', 'message' => 'Receta no encontrada'], 404);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            $this->outputJson(['status' => 'error', 'message' => 'Datos de entrada inválidos'], 400);
            return;
        }

        if (empty($data['tituloReceta']) || empty($data['description']) || empty($data['ingredients'])) {
            $this->outputJson(['status' => 'error', 'message' => 'Faltan campos requeridos'], 400);
            return;
        }

        try {
            $this->recipeModel->updateRecipe($id, $data);
            $this->outputJson([
                'status' => 'success',
                'message' => 'Receta actualizada correctamente'
            ]);
        } catch (Exception $e) {
            $this->outputJson(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            $this->outputJson(['status' => 'error', 'message' => 'Método no permitido'], 405);
            return;
        }

        if (!$id) {
            $this->outputJson(['status' => 'error', 'message' => 'ID de receta no especificado'], 400);
            return;
        }

        try {
            $result = $this->recipeModel->deleteRecipe($id);

            if ($result) {
                $this->outputJson([
                    'status' => 'success',
                    'message' => 'Receta eliminada correctamente'
                ]);
            } else {
                $this->outputJson(['status' => 'error', 'message' => 'Receta no encontrada'], 404);
            }
        } catch (Exception $e) {
            $this->outputJson(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function outputJson($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
}