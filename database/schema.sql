CREATE TABLE users IF NOT EXISTS (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user'
);

CREATE TABLE collections IF NOT EXISTS (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    user_id INT REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE recipes IF NOT EXISTS (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    ingredients TEXT,
    steps TEXT,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    collection_id INT REFERENCES collections(id) ON DELETE SET NULL
);

CREATE TABLE comments IF NOT EXISTS (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    recipe_id INT REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE ratings IF NOT EXISTS (
    id SERIAL PRIMARY KEY,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    recipe_id INT REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS recipe_collections (
    recipe_id INT NOT NULL,
    collection_id INT NOT NULL,
    PRIMARY KEY (recipe_id, collection_id),
    CONSTRAINT fk_recipe
        FOREIGN KEY (recipe_id)
        REFERENCES recipes(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_collection
        FOREIGN KEY (collection_id)
        REFERENCES collections(id)
        ON DELETE CASCADE
);

-- INSERT INTO users (username, email, password_hash, role) VALUES
-- ('admin', 'email1@example.com', '$2y$10$examplehash', 'admin'),
-- ('user1', 'email2@example.com', '$2y$10$examplehash', 'user');

-- INSERT INTO collections (name, user_id) VALUES
-- ('Zupy', 1),
-- ('Desery', 1);

-- INSERT INTO recipes (title, description, ingredients, steps, user_id, collection_id) VALUES
-- ('Rosół', 'Tradycyjny polski rosół', 'woda, kurczak, marchew, seler, przyprawy', 'Gotować 2h', 1, 1),
-- ('Sernik', 'Klasyczny sernik', 'ser, cukier, jajka, spód', 'Piec 50 min', 1, 2);