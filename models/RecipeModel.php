<?php

class RecipeModel extends Model {

    public function getAllRecipes() {
        $stmt = $this->db->prepare("SELECT * FROM recipes");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getRecipeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM recipes WHERE idReceta = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $recipe = $stmt->fetch();

        if ($recipe) {
            $stmt = $this->db->prepare("SELECT * FROM recipe_steps WHERE recipe_id = :id ORDER BY step_order");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $steps = $stmt->fetchAll();

            $recipe['steps'] = $steps;
        }

        return $recipe;
    }

    public function createRecipe($data) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("INSERT INTO recipes (tituloReceta, description, ingredients) 
                                       VALUES (:titulo, :descripcion, :ingredientes)");

            $stmt->bindParam(':titulo', $data['tituloReceta'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':ingredientes', $data['ingredients'], PDO::PARAM_STR);
            $stmt->execute();

            $recipeId = $this->db->lastInsertId();

            if (isset($data['steps']) && is_array($data['steps'])) {
                $stepOrder = 1;
                $stmtSteps = $this->db->prepare("INSERT INTO recipe_steps (recipe_id, urlImg, txt, step_order) 
                                              VALUES (:recipe_id, :urlImg, :txt, :step_order)");

                foreach ($data['steps'] as $step) {
                    $stmtSteps->bindParam(':recipe_id', $recipeId, PDO::PARAM_INT);
                    $stmtSteps->bindParam(':urlImg', $step['urlImg'], PDO::PARAM_STR);
                    $stmtSteps->bindParam(':txt', $step['txt'], PDO::PARAM_STR);
                    $stmtSteps->bindParam(':step_order', $stepOrder, PDO::PARAM_INT);
                    $stmtSteps->execute();
                    $stepOrder++;
                }
            }

            $this->db->commit();
            return $recipeId;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateRecipe($id, $data) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("UPDATE recipes SET tituloReceta = :titulo, description = :descripcion, 
                                       ingredients = :ingredientes WHERE idReceta = :id");

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $data['tituloReceta'], PDO::PARAM_STR);
            $stmt->bindParam(':descripcion', $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(':ingredientes', $data['ingredients'], PDO::PARAM_STR);
            $stmt->execute();

            $stmtDelete = $this->db->prepare("DELETE FROM recipe_steps WHERE recipe_id = :id");
            $stmtDelete->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtDelete->execute();

            if (isset($data['steps']) && is_array($data['steps'])) {
                $stepOrder = 1;
                $stmtSteps = $this->db->prepare("INSERT INTO recipe_steps (recipe_id, urlImg, txt, step_order) 
                                              VALUES (:recipe_id, :urlImg, :txt, :step_order)");

                foreach ($data['steps'] as $step) {
                    $stmtSteps->bindParam(':recipe_id', $id, PDO::PARAM_INT);
                    $stmtSteps->bindParam(':urlImg', $step['urlImg'], PDO::PARAM_STR);
                    $stmtSteps->bindParam(':txt', $step['txt'], PDO::PARAM_STR);
                    $stmtSteps->bindParam(':step_order', $stepOrder, PDO::PARAM_INT);
                    $stmtSteps->execute();
                    $stepOrder++;
                }
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteRecipe($id) {
        try {
            $this->db->beginTransaction();

            $stmtSteps = $this->db->prepare("DELETE FROM recipe_steps WHERE recipe_id = :id");
            $stmtSteps->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtSteps->execute();

            $stmtRecipe = $this->db->prepare("DELETE FROM recipes WHERE idReceta = :id");
            $stmtRecipe->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtRecipe->execute();

            $this->db->commit();
            return $stmtRecipe->rowCount() > 0;

        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}