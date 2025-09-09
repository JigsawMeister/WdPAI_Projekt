CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user'
);

CREATE TABLE collections (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    user_id INT REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE recipes (
    id SERIAL PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    ingredients TEXT,
    steps TEXT,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    collection_id INT REFERENCES collections(id) ON DELETE SET NULL
);

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    recipe_id INT REFERENCES recipes(id) ON DELETE CASCADE
);

CREATE TABLE ratings (
    id SERIAL PRIMARY KEY,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    recipe_id INT REFERENCES recipes(id) ON DELETE CASCADE
);

INSERT INTO users (username, password_hash, role) VALUES
('admin', '$2y$10$examplehash', 'admin'),
('user1', '$2y$10$examplehash', 'user');

INSERT INTO collections (name, user_id) VALUES
('Zupy', 1),
('Desery', 1);

INSERT INTO recipes (title, description, ingredients, steps, user_id, collection_id) VALUES
('Rosół', 'Tradycyjny polski rosół', 'woda, kurczak, marchew, seler, przyprawy', 'Gotować 2h', 1, 1),
('Sernik', 'Klasyczny sernik', 'ser, cukier, jajka, spód', 'Piec 50 min', 1, 2);