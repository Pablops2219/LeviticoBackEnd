CREATE TABLE IF NOT EXISTS step_ingredients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    step_id INT NOT NULL,
    ingredient_id INT NOT NULL,
    quantity DECIMAL(10,3) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_step_ingredients_step FOREIGN KEY (step_id) REFERENCES steps(id) ON DELETE CASCADE,
    CONSTRAINT fk_step_ingredients_ingredient FOREIGN KEY (ingredient_id) REFERENCES ingredients(id) ON DELETE CASCADE,
    UNIQUE KEY unique_step_ingredient (step_id, ingredient_id)
);
