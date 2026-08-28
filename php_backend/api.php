<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');

// Handle OPTIONS preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration
require_once 'config.php';

class PortfolioAPI
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            // Fallback to static data if database not available
            $this->pdo = null;
        }
    }

    public function getPortfolioData()
    {
        // Read from the database; fall back to static data on any problem.
        if (!$this->pdo) {
            return $this->getStaticData();
        }
        try {
            $cats = $this->pdo->query(
                "SELECT * FROM categories WHERE is_active = 1 ORDER BY order_index, id"
            )->fetchAll();
            if (!$cats) {
                return $this->getStaticData();
            }
            $galByCat = $this->pdo->prepare(
                "SELECT * FROM galleries WHERE category_id = ? AND is_active = 1 ORDER BY order_index, id"
            );
            $imgByGal = $this->pdo->prepare(
                "SELECT * FROM images WHERE gallery_id = ? AND is_active = 1 ORDER BY ord, id"
            );
            $out = [];
            foreach ($cats as $c) {
                $galleries = [];
                $galByCat->execute([$c['id']]);
                $gseq = 0;
                foreach ($galByCat->fetchAll() as $g) {
                    $gseq++;
                    $imgByGal->execute([$g['id']]);
                    $images = [];
                    $iseq = 0;
                    foreach ($imgByGal->fetchAll() as $im) {
                        $iseq++;
                        $images[] = [
                            "id" => $iseq,
                            "src" => $im['src'],
                            "th" => $im['th'],
                            "idgall" => $gseq,
                            "ord" => (int)$im['ord'],
                            "gname" => $im['gname'],
                        ];
                    }
                    $galleries[(string)$gseq] = [
                        "gid" => $gseq,
                        "gname" => $g['name'],
                        "descr" => $g['description'],
                        "gcover" => $g['cover_image'],
                        "gcoverBig" => $g['cover_image_big'],
                        "images" => $images,
                    ];
                }
                $out[(string)$c['id']] = [
                    "id" => (int)$c['id'],
                    "category" => $c['name'],
                    "cover" => $c['cover_image'],
                    "bigCover" => $c['cover_image_big'],
                    "description" => $c['description'],
                    "friendlyURL" => $c['friendly_url'],
                    "galleries" => $galleries,
                ];
            }
            return $out;
        } catch (PDOException $e) {
            return $this->getStaticData();
        }
    }

    private function getStaticData()
    {
        return [
            "1" => [
                "id" => 1,
                "category" => "Zeichnungen",
                "cover" => "/assets/images/minerva/img/1cd23958d821b2f2f22c3efb78d45458.jpg",
                "bigCover" => "/assets/images/minerva/img/1cd23958d821b2f2f22c3efb78d45458.jpg",
                "description" => "Eine Auswahl meiner digitalen und traditionellen Arbeiten!",
                "friendlyURL" => "/disegni",
                "galleries" => [
                    "1" => [
                        "gid" => 1,
                        "gname" => "Zeichnungen",
                        "descr" => "Ich liebe es, Zeit mit Photoshop und Procreate zu verbringen, um Figuren oder Szenarien zu erschaffen, die nur in meiner Fantasie existieren. Und auch einige Porträts.",
                        "gcover" => "/assets/images/minerva/img/1cd23958d821b2f2f22c3efb78d45458.jpg",
                        "gcoverBig" => "/assets/images/minerva/img/1cd23958d821b2f2f22c3efb78d45458.jpg",
                        "images" => [
                            [
                                "id" => 1,
                                "src" => "/assets/images/minerva/img/1cd23958d821b2f2f22c3efb78d45458.jpg",
                                "th" => "/assets/images/minerva/thumb/img/1cd23958d821b2f2f22c3efb78d45458.jpg",
                                "idgall" => 1,
                                "ord" => 1,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 2,
                                "src" => "/assets/images/minerva/img/1d63f1cbef15e2158c2f51a042cbc145.jpg",
                                "th" => "/assets/images/minerva/thumb/img/1d63f1cbef15e2158c2f51a042cbc145.jpg",
                                "idgall" => 1,
                                "ord" => 2,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 3,
                                "src" => "/assets/images/minerva/img/b534289dbf42f4df388df0d38ea64d87.jpg",
                                "th" => "/assets/images/minerva/thumb/img/b534289dbf42f4df388df0d38ea64d87.jpg",
                                "idgall" => 1,
                                "ord" => 3,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 4,
                                "src" => "/assets/images/minerva/img/31dc1b9b6be684ce2b872b15eb4cc53c.jpg",
                                "th" => "/assets/images/minerva/thumb/img/31dc1b9b6be684ce2b872b15eb4cc53c.jpg",
                                "idgall" => 1,
                                "ord" => 4,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 5,
                                "src" => "/assets/images/minerva/img/86b74a654cb2638a38af4121ab9895e7.jpg",
                                "th" => "/assets/images/minerva/thumb/img/86b74a654cb2638a38af4121ab9895e7.jpg",
                                "idgall" => 1,
                                "ord" => 5,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 6,
                                "src" => "/assets/images/minerva/img/9f5d715ff7df769c2fa1c05972021b21.jpg",
                                "th" => "/assets/images/minerva/thumb/img/9f5d715ff7df769c2fa1c05972021b21.jpg",
                                "idgall" => 1,
                                "ord" => 6,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 7,
                                "src" => "/assets/images/minerva/img/f43a57686d615ef9202725e877795878.jpg",
                                "th" => "/assets/images/minerva/thumb/img/f43a57686d615ef9202725e877795878.jpg",
                                "idgall" => 1,
                                "ord" => 7,
                                "gname" => "Zeichnungen"
                            ],
                            [
                                "id" => 8,
                                "src" => "/assets/images/minerva/img/11f325fcfe3e2f0aefa6817afb821321.jpg",
                                "th" => "/assets/images/minerva/thumb/img/11f325fcfe3e2f0aefa6817afb821321.jpg",
                                "idgall" => 1,
                                "ord" => 8,
                                "gname" => "Zeichnungen"
                            ]
                        ]
                    ]
                ]
            ],
            "2" => [
                "id" => 2,
                "category" => "3D Modelle",
                "cover" => "/assets/images/minerva/img/73597d21dbb176ae079f6f6b9d310d0b.jpg",
                "bigCover" => "/assets/images/minerva/img/73597d21dbb176ae079f6f6b9d310d0b.jpg",
                "description" => "Obwohl es nicht meine Haupttätigkeit ist, möchte ich einen Raum für 3D-Modellierung schaffen",
                "friendlyURL" => "/3d",
                "galleries" => [
                    "1" => [
                        "gid" => 1,
                        "gname" => "3D Modelle",
                        "descr" => "3D-Modellierung und Rendering für kreative Projekte.",
                        "gcover" => "/assets/images/minerva/img/73597d21dbb176ae079f6f6b9d310d0b.jpg",
                        "gcoverBig" => "/assets/images/minerva/img/73597d21dbb176ae079f6f6b9d310d0b.jpg",
                        "images" => [
                            [
                                "id" => 1,
                                "src" => "/assets/images/minerva/img/73597d21dbb176ae079f6f6b9d310d0b.jpg",
                                "th" => "/assets/images/minerva/thumb/img/73597d21dbb176ae079f6f6b9d310d0b.jpg",
                                "idgall" => 1,
                                "ord" => 1,
                                "gname" => "3D Modelle"
                            ],
                            [
                                "id" => 2,
                                "src" => "/assets/images/minerva/img/fa42a73f730f0eec885b341a5542cda4.jpg",
                                "th" => "/assets/images/minerva/thumb/img/fa42a73f730f0eec885b341a5542cda4.jpg",
                                "idgall" => 1,
                                "ord" => 2,
                                "gname" => "3D Modelle"
                            ],
                            [
                                "id" => 3,
                                "src" => "/assets/images/minerva/img/064697aad4e45a1c7d5ced6f4ab08381.jpg",
                                "th" => "/assets/images/minerva/thumb/img/064697aad4e45a1c7d5ced6f4ab08381.jpg",
                                "idgall" => 1,
                                "ord" => 3,
                                "gname" => "3D Modelle"
                            ],
                            [
                                "id" => 4,
                                "src" => "/assets/images/minerva/img/18a0edb54327f19097e1b5159a4f406d.jpg",
                                "th" => "/assets/images/minerva/thumb/img/18a0edb54327f19097e1b5159a4f406d.jpg",
                                "idgall" => 1,
                                "ord" => 4,
                                "gname" => "3D Modelle"
                            ],
                            [
                                "id" => 5,
                                "src" => "/assets/images/minerva/img/7b1679c92d21a4af7e9fdb4cd5cb5da2.jpg",
                                "th" => "/assets/images/minerva/thumb/img/7b1679c92d21a4af7e9fdb4cd5cb5da2.jpg",
                                "idgall" => 1,
                                "ord" => 5,
                                "gname" => "3D Modelle"
                            ],
                            [
                                "id" => 6,
                                "src" => "/assets/images/minerva/img/8a8b3d1e465ae97b467318efb2e38fc7.jpg",
                                "th" => "/assets/images/minerva/thumb/img/8a8b3d1e465ae97b467318efb2e38fc7.jpg",
                                "idgall" => 1,
                                "ord" => 6,
                                "gname" => "3D Modelle"
                            ]
                        ]
                    ]
                ]
            ],
            "3" => [
                "id" => 3,
                "category" => "Fotografie",
                "cover" => "/assets/images/minerva/img/7c227165ae9c58c1813bfd82e6b7cf3e.jpg",
                "bigCover" => "/assets/images/minerva/img/7c227165ae9c58c1813bfd82e6b7cf3e.jpg",
                "description" => "In diesem Bereich habe ich eine Auswahl von Fotos zusammengestellt, die meinen vom Kino inspirierten Stil widerspiegeln.",
                "friendlyURL" => "/fotografia",
                "galleries" => [
                    "1" => [
                        "gid" => 1,
                        "gname" => "Fotografie",
                        "descr" => "Landschaften und Motive mit kinematografischem Schnitt und Nachbearbeitung.",
                        "gcover" => "/assets/images/minerva/img/7c227165ae9c58c1813bfd82e6b7cf3e.jpg",
                        "gcoverBig" => "/assets/images/minerva/img/7c227165ae9c58c1813bfd82e6b7cf3e.jpg",
                        "images" => [
                            [
                                "id" => 1,
                                "src" => "/assets/images/minerva/img/7c227165ae9c58c1813bfd82e6b7cf3e.jpg",
                                "th" => "/assets/images/minerva/thumb/img/7c227165ae9c58c1813bfd82e6b7cf3e.jpg",
                                "idgall" => 1,
                                "ord" => 1,
                                "gname" => "Fotografie"
                            ],
                            [
                                "id" => 2,
                                "src" => "/assets/images/minerva/img/8555d776a0778a8df4dd2f497becdf6a.jpg",
                                "th" => "/assets/images/minerva/thumb/img/8555d776a0778a8df4dd2f497becdf6a.jpg",
                                "idgall" => 1,
                                "ord" => 2,
                                "gname" => "Fotografie"
                            ],
                            [
                                "id" => 3,
                                "src" => "/assets/images/minerva/img/1754592bffda2992bd49dfd03139b1af.jpg",
                                "th" => "/assets/images/minerva/thumb/img/1754592bffda2992bd49dfd03139b1af.jpg",
                                "idgall" => 1,
                                "ord" => 3,
                                "gname" => "Fotografie"
                            ]
                        ]
                    ]
                ]
            ],
            "4" => [
                "id" => 4,
                "category" => "Logos",
                "cover" => "/assets/images/minerva/img/28d02dbd2d4816dd9adba1efe9abd9b0.jpg",
                "bigCover" => "/assets/images/minerva/img/28d02dbd2d4816dd9adba1efe9abd9b0.jpg",
                "description" => "Eine Sammlung von Logos, die mit Illustrator erstellt wurden.",
                "friendlyURL" => "/loghi",
                "galleries" => [
                    "1" => [
                        "gid" => 1,
                        "gname" => "Logos",
                        "descr" => "Logo-Design und visuelle Identitäten für Marken und Projekte.",
                        "gcover" => "/assets/images/minerva/img/28d02dbd2d4816dd9adba1efe9abd9b0.jpg",
                        "gcoverBig" => "/assets/images/minerva/img/28d02dbd2d4816dd9adba1efe9abd9b0.jpg",
                        "images" => [
                            [
                                "id" => 1,
                                "src" => "/assets/images/minerva/img/28d02dbd2d4816dd9adba1efe9abd9b0.jpg",
                                "th" => "/assets/images/minerva/thumb/img/28d02dbd2d4816dd9adba1efe9abd9b0.jpg",
                                "idgall" => 1,
                                "ord" => 1,
                                "gname" => "Logos"
                            ],
                            [
                                "id" => 2,
                                "src" => "/assets/images/minerva/img/32f9f6609cc2df4cf7c69ac3609c0b00.jpg",
                                "th" => "/assets/images/minerva/thumb/img/32f9f6609cc2df4cf7c69ac3609c0b00.jpg",
                                "idgall" => 1,
                                "ord" => 2,
                                "gname" => "Logos"
                            ],
                            [
                                "id" => 3,
                                "src" => "/assets/images/minerva/img/34d9ec52a3ac24530c49a516687634ae.jpg",
                                "th" => "/assets/images/minerva/thumb/img/34d9ec52a3ac24530c49a516687634ae.jpg",
                                "idgall" => 1,
                                "ord" => 3,
                                "gname" => "Logos"
                            ]
                        ]
                    ]
                ]
            ],
            "5" => [
                "id" => 5,
                "category" => "Charaktere",
                "cover" => "/assets/images/minerva/img/361a5b1fd58d679589b8a690d2f19eb7.jpg",
                "bigCover" => "/assets/images/minerva/img/361a5b1fd58d679589b8a690d2f19eb7.jpg",
                "description" => "Die Charaktere sind eine karikaturhafte Version der Menschen in meinem Leben, inspiriert von Funko Pop.",
                "friendlyURL" => "/puppets",
                "galleries" => [
                    "1" => [
                        "gid" => 1,
                        "gname" => "Charaktere",
                        "descr" => "Karikaturhafte Figuren inspiriert von Menschen aus meinem Leben.",
                        "gcover" => "/assets/images/minerva/img/361a5b1fd58d679589b8a690d2f19eb7.jpg",
                        "gcoverBig" => "/assets/images/minerva/img/361a5b1fd58d679589b8a690d2f19eb7.jpg",
                        "images" => [
                            [
                                "id" => 1,
                                "src" => "/assets/images/minerva/img/361a5b1fd58d679589b8a690d2f19eb7.jpg",
                                "th" => "/assets/images/minerva/thumb/img/361a5b1fd58d679589b8a690d2f19eb7.jpg",
                                "idgall" => 1,
                                "ord" => 1,
                                "gname" => "Charaktere"
                            ],
                            [
                                "id" => 2,
                                "src" => "/assets/images/minerva/img/3f4260cd530deb5b1dd110c51bb10e5e.jpg",
                                "th" => "/assets/images/minerva/thumb/img/3f4260cd530deb5b1dd110c51bb10e5e.jpg",
                                "idgall" => 1,
                                "ord" => 2,
                                "gname" => "Charaktere"
                            ],
                            [
                                "id" => 3,
                                "src" => "/assets/images/minerva/img/4162312da5cc49ba24beee19a82ddfcb.jpg",
                                "th" => "/assets/images/minerva/thumb/img/4162312da5cc49ba24beee19a82ddfcb.jpg",
                                "idgall" => 1,
                                "ord" => 3,
                                "gname" => "Charaktere"
                            ]
                        ]
                    ]
                ]
            ],
            "6" => [
                "id" => 6,
                "category" => "Grafiken",
                "cover" => "/assets/images/minerva/img/6a7d3179e01fc6fee73f8a3a7a83e40b.jpg",
                "bigCover" => "/assets/images/minerva/img/6a7d3179e01fc6fee73f8a3a7a83e40b.jpg",
                "description" => "Plakate, Poster und vieles mehr!",
                "friendlyURL" => "/grafiche",
                "galleries" => [
                    "1" => [
                        "gid" => 1,
                        "gname" => "Grafiken",
                        "descr" => "Grafikdesign für Events, Plakate und visuelle Kommunikation.",
                        "gcover" => "/assets/images/minerva/img/6a7d3179e01fc6fee73f8a3a7a83e40b.jpg",
                        "gcoverBig" => "/assets/images/minerva/img/6a7d3179e01fc6fee73f8a3a7a83e40b.jpg",
                        "images" => [
                            [
                                "id" => 1,
                                "src" => "/assets/images/minerva/img/6a7d3179e01fc6fee73f8a3a7a83e40b.jpg",
                                "th" => "/assets/images/minerva/thumb/img/6a7d3179e01fc6fee73f8a3a7a83e40b.jpg",
                                "idgall" => 1,
                                "ord" => 1,
                                "gname" => "Grafiken"
                            ],
                            [
                                "id" => 2,
                                "src" => "/assets/images/minerva/img/76942030903e0758db14a29dddabb220.jpg",
                                "th" => "/assets/images/minerva/thumb/img/76942030903e0758db14a29dddabb220.jpg",
                                "idgall" => 1,
                                "ord" => 2,
                                "gname" => "Grafiken"
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Parse request
        $path = parse_url($uri, PHP_URL_PATH);
        $query = parse_url($uri, PHP_URL_QUERY);
        parse_str($query ?? '', $params);

        switch ($method) {
            case 'GET':
                return $this->handleGetRequest($path, $params);
            case 'POST':
                return $this->handlePostRequest($path);
            default:
                http_response_code(405);
                return ['error' => 'Method not allowed'];
        }
    }

    private function handleGetRequest($path, $params)
    {
        // Handle different GET endpoints
        if (strpos($path, '/api/portfolio') !== false) {
            return $this->getPortfolioData();
        }

        if (strpos($path, '/api/category') !== false) {
            $categoryId = $params['id'] ?? null;
            if ($categoryId) {
                return $this->getCategoryData($categoryId);
            }
        }

        if (strpos($path, '/api/gallery') !== false) {
            $galleryId = $params['id'] ?? null;
            if ($galleryId) {
                return $this->getGalleryData($galleryId);
            }
        }

        // Default: return full portfolio
        return $this->getPortfolioData();
    }

    private function handlePostRequest($path)
    {
        // Handle portfolio updates (admin only)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!$this->validateAuthToken($authHeader)) {
            http_response_code(401);
            return ['error' => 'Unauthorized'];
        }

        $input = json_decode(file_get_contents('php://input'), true);

        if (strpos($path, '/api/upload') !== false) {
            return $this->handleImageUpload();
        }

        if (strpos($path, '/api/gallery') !== false) {
            return $this->updateGallery($input);
        }

        return ['status' => 'endpoint_not_implemented'];
    }

    private function getCategoryData($categoryId)
    {
        $data = $this->getPortfolioData();
        return $data[$categoryId] ?? ['error' => 'Category not found'];
    }

    private function getGalleryData($galleryId)
    {
        $data = $this->getPortfolioData();

        foreach ($data as $category) {
            if (isset($category['galleries'][$galleryId])) {
                return $category['galleries'][$galleryId];
            }
        }

        return ['error' => 'Gallery not found'];
    }

    private function validateAuthToken($authHeader)
    {
        $token = str_replace('Bearer ', '', $authHeader);
        return $token === ADMIN_TOKEN;
    }

    private function handleImageUpload()
    {
        if (!isset($_FILES['image'])) {
            return ['error' => 'No image uploaded'];
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $file = $_FILES['image'];
        $fileName = uniqid() . '_' . $file['name'];
        $uploadPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'filename' => $fileName,
                'path' => $uploadPath
            ];
        }

        return ['error' => 'Upload failed'];
    }

    private function updateGallery($data)
    {
        // TODO: Implement gallery update functionality
        return ['status' => 'gallery_update_not_implemented'];
    }
}

// Initialize and handle request
$api = new PortfolioAPI();
$result = $api->handleRequest();

// Debug log
error_log("API called at " . date('Y-m-d H:i:s') . " - Categories: " . count($result));

// Output JSON response
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);