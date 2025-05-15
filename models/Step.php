<?php

class Step extends Model {
    protected $table = 'steps';

    public function getIngredients($stepId) {
        $stmt = $this->db->prepare(
            "SELECT i.*, si.quantity, si.unit
             FROM ingredients i
             JOIN step_ingredients si ON i.id = si.ingredient_id
             WHERE si.step_id = :step_id"
        );
        $stmt->bindParam(':step_id', $stepId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
