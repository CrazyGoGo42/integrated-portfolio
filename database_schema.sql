-- Portfolio Database Schema (German Version)
-- Content model: categories -> galleries -> images, plus admin auth.

CREATE DATABASE IF NOT EXISTS `portfolio_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;

-- Categories table
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    cover_image VARCHAR(500),
    cover_image_big VARCHAR(500),
    description TEXT,
    friendly_url VARCHAR(255),
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Galleries table
CREATE TABLE galleries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    cover_image VARCHAR(500),
    cover_image_big VARCHAR(500),
    order_index INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Images table
CREATE TABLE images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gallery_id INT,
    src VARCHAR(500) NOT NULL,
    th VARCHAR(500), -- thumbnail path
    ord INT DEFAULT 0, -- order/position
    gname VARCHAR(255), -- gallery name cache
    alt_text VARCHAR(255),
    title VARCHAR(255),
    file_size INT,
    dimensions VARCHAR(50), -- e.g., "1920x1080"
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
);

-- Admin users table
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- API tokens table for authentication
CREATE TABLE api_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    token VARCHAR(255) UNIQUE NOT NULL,
    expires_at TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admin_users(id) ON DELETE CASCADE
);

-- Create indexes for better performance
CREATE INDEX idx_galleries_category ON galleries(category_id);
CREATE INDEX idx_galleries_active ON galleries(is_active, order_index);
CREATE INDEX idx_images_gallery ON images(gallery_id);
CREATE INDEX idx_images_order ON images(gallery_id, ord);
CREATE INDEX idx_images_active ON images(is_active);
CREATE INDEX idx_tokens_active ON api_tokens(is_active, expires_at);

-- Insert German categories with proper image paths
INSERT INTO categories (id, name, cover_image, cover_image_big, description, friendly_url, order_index) VALUES
(1, 'Zeichnungen', 'assets/images/disegni_images/disegni_001.webp', 'assets/images/disegni_images/disegni_001.webp', 'Eine Auswahl meiner digitalen und traditionellen Arbeiten!', '/disegni', 1),
(2, '3D Modelle', 'assets/images/3d_images/3d_001.jpg', 'assets/images/3d_images/3d_001.jpg', 'Obwohl es nicht meine Haupttätigkeit ist, möchte ich einen Raum für 3D-Modellierung schaffen', '/3d', 2),
(3, 'Fotografie', 'assets/images/fotografia_images/fotografia_001.webp', 'assets/images/fotografia_images/fotografia_001.webp', 'In diesem Bereich habe ich eine Auswahl von Fotos zusammengestellt, die meinen vom Kino inspirierten Stil widerspiegeln.', '/fotografia', 3),
(4, 'Logos', 'assets/images/loghi_images/loghi_001.jpg', 'assets/images/loghi_images/loghi_001.jpg', 'Eine Sammlung von Logos, die mit Illustrator erstellt wurden.', '/loghi', 4),
(5, 'Charaktere', 'assets/images/puppets_images/puppets_001.jpg', 'assets/images/puppets_images/puppets_001.jpg', 'Die Charaktere sind eine karikaturhafte Version der Menschen in meinem Leben, inspiriert von Funko Pop.', '/puppets', 5),
(6, 'Grafiken', 'assets/images/grafiche_images/grafiche_001.jpg', 'assets/images/grafiche_images/grafiche_001.jpg', 'Plakate, Poster und vieles mehr!', '/grafiche', 6);

-- Insert German galleries with correct descriptions
INSERT INTO galleries (id, category_id, name, description, cover_image, cover_image_big, order_index) VALUES
(1, 1, 'Zeichnungen', 'Ich liebe es, Zeit mit Photoshop und Procreate zu verbringen, um Figuren oder Szenarien zu erschaffen, die nur in meiner Fantasie existieren. Und auch einige Porträts.', 'assets/images/disegni_images/disegni_001.webp', 'assets/images/disegni_images/disegni_001.webp', 1),
(2, 2, '3D Modelle', '3D-Modellierung und Rendering für kreative Projekte.', 'assets/images/3d_images/3d_001.jpg', 'assets/images/3d_images/3d_001.jpg', 1),
(3, 3, 'Fotografie', 'Landschaften und Motive mit kinematografischem Schnitt und Nachbearbeitung.', 'assets/images/fotografia_images/fotografia_001.webp', 'assets/images/fotografia_images/fotografia_001.webp', 1),
(4, 4, 'Logos', 'Logo-Design und visuelle Identitäten für Marken und Projekte.', 'assets/images/loghi_images/loghi_001.jpg', 'assets/images/loghi_images/loghi_001.jpg', 1),
(5, 5, 'Charaktere', 'Karikaturhafte Figuren inspiriert von Menschen aus meinem Leben.', 'assets/images/puppets_images/puppets_001.jpg', 'assets/images/puppets_images/puppets_001.jpg', 1),
(6, 6, 'Grafiken', 'Grafikdesign für Events, Plakate und visuelle Kommunikation.', 'assets/images/grafiche_images/grafiche_001.jpg', 'assets/images/grafiche_images/grafiche_001.jpg', 1);

-- Insert sample images with correct paths for Zeichnungen gallery
INSERT INTO images (id, gallery_id, src, th, ord, gname) VALUES
(1, 1, 'assets/images/disegni_images/disegni_001.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_001.webp', 1, 'Zeichnungen'),
(2, 1, 'assets/images/disegni_images/disegni_002.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_002.webp', 2, 'Zeichnungen'),
(3, 1, 'assets/images/disegni_images/disegni_003.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_003.webp', 3, 'Zeichnungen'),
(4, 1, 'assets/images/disegni_images/disegni_004.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_004.webp', 4, 'Zeichnungen'),
(5, 1, 'assets/images/disegni_images/disegni_005.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_005.webp', 5, 'Zeichnungen'),
(6, 1, 'assets/images/disegni_images/disegni_006.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_006.webp', 6, 'Zeichnungen'),
(7, 1, 'assets/images/disegni_images/disegni_007.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_007.webp', 7, 'Zeichnungen'),
(8, 1, 'assets/images/disegni_images/disegni_008.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_008.webp', 8, 'Zeichnungen'),
(9, 1, 'assets/images/disegni_images/disegni_009.webp', 'assets/images/disegni_images/thumbnails/thumb_disegni_009.webp', 9, 'Zeichnungen');

-- Insert sample images for 3D Modelle gallery
INSERT INTO images (id, gallery_id, src, th, ord, gname) VALUES
(10, 2, 'assets/images/3d_images/3d_001.jpg', 'assets/images/3d_images/thumbnails/thumb_3d_001.webp', 1, '3D Modelle'),
(11, 2, 'assets/images/3d_images/3d_002.jpg', 'assets/images/3d_images/thumbnails/thumb_3d_002.webp', 2, '3D Modelle'),
(12, 2, 'assets/images/3d_images/3d_003.jpg', 'assets/images/3d_images/thumbnails/thumb_3d_003.webp', 3, '3D Modelle'),
(13, 2, 'assets/images/3d_images/3d_004.jpg', 'assets/images/3d_images/thumbnails/thumb_3d_004.webp', 4, '3D Modelle'),
(14, 2, 'assets/images/3d_images/3d_005.jpg', 'assets/images/3d_images/thumbnails/thumb_3d_005.webp', 5, '3D Modelle');

-- Insert sample images for Fotografie gallery
INSERT INTO images (id, gallery_id, src, th, ord, gname) VALUES
(15, 3, 'assets/images/fotografia_images/fotografia_001.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_001.webp', 1, 'Fotografie'),
(16, 3, 'assets/images/fotografia_images/fotografia_002.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_002.webp', 2, 'Fotografie'),
(17, 3, 'assets/images/fotografia_images/fotografia_003.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_003.webp', 3, 'Fotografie'),
(18, 3, 'assets/images/fotografia_images/fotografia_004.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_004.webp', 4, 'Fotografie'),
(19, 3, 'assets/images/fotografia_images/fotografia_005.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_005.webp', 5, 'Fotografie'),
(20, 3, 'assets/images/fotografia_images/fotografia_006.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_006.webp', 6, 'Fotografie'),
(21, 3, 'assets/images/fotografia_images/fotografia_007.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_007.webp', 7, 'Fotografie'),
(22, 3, 'assets/images/fotografia_images/fotografia_008.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_008.webp', 8, 'Fotografie'),
(23, 3, 'assets/images/fotografia_images/fotografia_009.webp', 'assets/images/fotografia_images/thumbnails/thumb_fotografia_009.webp', 9, 'Fotografie');

-- Insert sample images for Logos gallery
INSERT INTO images (id, gallery_id, src, th, ord, gname) VALUES
(24, 4, 'assets/images/loghi_images/loghi_001.jpg', 'assets/images/loghi_images/thumbnails/thumb_loghi_001.webp', 1, 'Logos'),
(25, 4, 'assets/images/loghi_images/loghi_002.jpg', 'assets/images/loghi_images/thumbnails/thumb_loghi_002.webp', 2, 'Logos'),
(26, 4, 'assets/images/loghi_images/loghi_003.jpg', 'assets/images/loghi_images/thumbnails/thumb_loghi_003.webp', 3, 'Logos'),
(27, 4, 'assets/images/loghi_images/loghi_004.jpg', 'assets/images/loghi_images/thumbnails/thumb_loghi_004.webp', 4, 'Logos'),
(28, 4, 'assets/images/loghi_images/loghi_005.jpg', 'assets/images/loghi_images/thumbnails/thumb_loghi_005.webp', 5, 'Logos');

-- Insert sample images for Charaktere gallery
INSERT INTO images (id, gallery_id, src, th, ord, gname) VALUES
(29, 5, 'assets/images/puppets_images/puppets_001.jpg', 'assets/images/puppets_images/thumbnails/thumb_puppets_001.webp', 1, 'Charaktere'),
(30, 5, 'assets/images/puppets_images/puppets_002.jpg', 'assets/images/puppets_images/thumbnails/thumb_puppets_002.webp', 2, 'Charaktere'),
(31, 5, 'assets/images/puppets_images/puppets_003.jpg', 'assets/images/puppets_images/thumbnails/thumb_puppets_003.webp', 3, 'Charaktere'),
(32, 5, 'assets/images/puppets_images/puppets_004.jpg', 'assets/images/puppets_images/thumbnails/thumb_puppets_004.webp', 4, 'Charaktere'),
(33, 5, 'assets/images/puppets_images/puppets_005.jpg', 'assets/images/puppets_images/thumbnails/thumb_puppets_005.webp', 5, 'Charaktere');

-- Insert sample images for Grafiken gallery
INSERT INTO images (id, gallery_id, src, th, ord, gname) VALUES
(34, 6, 'assets/images/grafiche_images/grafiche_001.jpg', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_001.webp', 1, 'Grafiken'),
(35, 6, 'assets/images/grafiche_images/grafiche_002.jpg', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_002.webp', 2, 'Grafiken'),
(36, 6, 'assets/images/grafiche_images/grafiche_003.jpg', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_003.webp', 3, 'Grafiken'),
(37, 6, 'assets/images/grafiche_images/grafiche_004.jpg', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_004.webp', 4, 'Grafiken'),
(38, 6, 'assets/images/grafiche_images/grafiche_005.jpg', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_005.webp', 5, 'Grafiken'),
(39, 6, 'assets/images/grafiche_images/grafiche_006.webp', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_006.webp', 6, 'Grafiken'),
(40, 6, 'assets/images/grafiche_images/grafiche_007.webp', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_007.webp', 7, 'Grafiken'),
(41, 6, 'assets/images/grafiche_images/grafiche_008.webp', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_008.webp', 8, 'Grafiken'),
(42, 6, 'assets/images/grafiche_images/grafiche_009.webp', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_009.webp', 9, 'Grafiken'),
(43, 6, 'assets/images/grafiche_images/grafiche_010.webp', 'assets/images/grafiche_images/thumbnails/thumb_grafiche_010.webp', 10, 'Grafiken');

-- Add sample admin user (password: admin123 - hash this in production!)
INSERT INTO admin_users (username, password_hash, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@portfolio.local');

-- Add sample API token
INSERT INTO api_tokens (user_id, token, expires_at) VALUES
(1, 'sample_admin_token_change_this', DATE_ADD(NOW(), INTERVAL 1 YEAR));