<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$DATA_FILE = 'players.json';

function loadPlayers() {
    global $DATA_FILE;
    if (file_exists($DATA_FILE)) {
        $data = file_get_contents($DATA_FILE);
        return json_decode($data, true);
    }
    return [];
}

function savePlayers($players) {
    global $DATA_FILE;
    file_put_contents($DATA_FILE, json_encode($players, JSON_PRETTY_PRINT));
}

function initializeData() {
    global $DATA_FILE;
    if (!file_exists($DATA_FILE)) {
        $seedData = [
            ["id" => 1, "name" => "LeBron James", "team" => "Lakers", "position" => "SF", "ppg" => 27.1, "years" => 23],
            ["id" => 2, "name" => "Stephen Curry", "team" => "Warriors", "position" => "PG", "ppg" => 24.5, "years" => 17],
            ["id" => 3, "name" => "Kevin Durant", "team" => "Rockets", "position" => "SF", "ppg" => 27.3, "years" => 18],
            ["id" => 4, "name" => "Giannis Antetokounmpo", "team" => "Bucks", "position" => "PF", "ppg" => 29.8, "years" => 13],
            ["id" => 5, "name" => "Nikola Jokic", "team" => "Nuggets", "position" => "C", "ppg" => 26.4, "years" => 11],
            ["id" => 6, "name" => "Luka Doncic", "team" => "Lakers", "position" => "PG", "ppg" => 28.7, "years" => 8],
            ["id" => 7, "name" => "Joel Embiid", "team" => "76ers", "position" => "C", "ppg" => 30.1, "years" => 11],
            ["id" => 8, "name" => "Jayson Tatum", "team" => "Celtics", "position" => "SF", "ppg" => 26.9, "years" => 9],
            ["id" => 9, "name" => "Damian Lillard", "team" => "Trailblazers", "position" => "PG", "ppg" => 25.1, "years" => 14],
            ["id" => 10, "name" => "Jimmy Butler", "team" => "Warriors", "position" => "SF", "ppg" => 22.3, "years" => 15],
            ["id" => 11, "name" => "Kawhi Leonard", "team" => "Clippers", "position" => "SF", "ppg" => 24.8, "years" => 14],
            ["id" => 12, "name" => "Devin Booker", "team" => "Suns", "position" => "SG", "ppg" => 27.1, "years" => 10],
            ["id" => 13, "name" => "Anthony Davis", "team" => "Mavericks", "position" => "PF", "ppg" => 24.2, "years" => 13],
            ["id" => 14, "name" => "Ja Morant", "team" => "Grizzlies", "position" => "PG", "ppg" => 26.1, "years" => 7],
            ["id" => 15, "name" => "Zion Williamson", "team" => "Pelicans", "position" => "PF", "ppg" => 25.0, "years" => 6],
            ["id" => 16, "name" => "Trae Young", "team" => "Wizards", "position" => "PG", "ppg" => 25.5, "years" => 8],
            ["id" => 17, "name" => "Paul George", "team" => "Clippers", "position" => "SG", "ppg" => 23.8, "years" => 15],
            ["id" => 18, "name" => "Bradley Beal", "team" => "Clippers", "position" => "SG", "ppg" => 22.5, "years" => 13],
            ["id" => 19, "name" => "Donovan Mitchell", "team" => "Cavaliers", "position" => "SG", "ppg" => 27.6, "years" => 9],
            ["id" => 20, "name" => "Bam Adebayo", "team" => "Heat", "position" => "C", "ppg" => 20.4, "years" => 9],
            ["id" => 21, "name" => "Jamal Murray", "team" => "Nuggets", "position" => "PG", "ppg" => 20.0, "years" => 9],
            ["id" => 22, "name" => "Shai Gilgeous-Alexander", "team" => "Thunder", "position" => "SG", "ppg" => 30.1, "years" => 8],
            ["id" => 23, "name" => "De'Aaron Fox", "team" => "Spurs", "position" => "PG", "ppg" => 25.2, "years" => 9],
            ["id" => 24, "name" => "Jaren Jackson Jr.", "team" => "Grizzlies", "position" => "PF", "ppg" => 22.4, "years" => 8],
            ["id" => 25, "name" => "Jrue Holiday", "team" => "Trailblazers", "position" => "PG", "ppg" => 18.5, "years" => 15],
            ["id" => 26, "name" => "Karl-Anthony Towns", "team" => "Knicks", "position" => "C", "ppg" => 23.1, "years" => 11],
            ["id" => 27, "name" => "Anthony Edwards", "team" => "Timberwolves", "position" => "SG", "ppg" => 26.0, "years" => 6],
            ["id" => 28, "name" => "Pascal Siakam", "team" => "Pacers", "position" => "PF", "ppg" => 22.0, "years" => 10],
            ["id" => 29, "name" => "Domantas Sabonis", "team" => "Kings", "position" => "C", "ppg" => 19.4, "years" => 11],
            ["id" => 30, "name" => "Tyrese Haliburton", "team" => "Pacers", "position" => "PG", "ppg" => 20.1, "years" => 6]
        ];
        savePlayers($seedData);
    }
}

function getPlayers() {
    $players = loadPlayers();
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $pageSize = isset($_GET['page_size']) ? intval($_GET['page_size']) : 10;
    
    $start = ($page - 1) * $pageSize;
    $paginatedPlayers = array_slice($players, $start, $pageSize);
    
    echo json_encode([
        'players' => $paginatedPlayers,
        'total' => count($players),
        'page' => $page,
        'page_size' => $pageSize,
        'total_pages' => ceil(count($players) / $pageSize)
    ]);
}

function getPlayer($id) {
    $players = loadPlayers();
    foreach ($players as $player) {
        if ($player['id'] == $id) {
            echo json_encode($player);
            return;
        }
    }
    http_response_code(404);
    echo json_encode(['error' => 'Player not found']);
}

function createPlayer() {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Server-side validation
    if (empty($data['name']) || empty($data['team'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and team are required']);
        return;
    }
    
    $ppg = floatval($data['ppg'] ?? 0);
    $years = intval($data['years'] ?? 0);
    
    if ($ppg < 0 || $ppg > 50) {
        http_response_code(400);
        echo json_encode(['error' => 'PPG must be between 0 and 50']);
        return;
    }
    
    if ($years < 0 || $years > 25) {
        http_response_code(400);
        echo json_encode(['error' => 'Years must be between 0 and 25']);
        return;
    }
    
    $players = loadPlayers();
    
    // Generate new ID
    $maxId = 0;
    foreach ($players as $player) {
        if ($player['id'] > $maxId) {
            $maxId = $player['id'];
        }
    }
    $newId = $maxId + 1;
    
    $newPlayer = [
        'id' => $newId,
        'name' => trim($data['name']),
        'team' => trim($data['team']),
        'position' => $data['position'] ?? 'PG',
        'ppg' => $ppg,
        'years' => $years
    ];
    
    $players[] = $newPlayer;
    savePlayers($players);
    
    http_response_code(201);
    echo json_encode($newPlayer);
}

function updatePlayer($id) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Server-side validation
    if (empty($data['name']) || empty($data['team'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and team are required']);
        return;
    }
    
    $ppg = floatval($data['ppg'] ?? 0);
    $years = intval($data['years'] ?? 0);
    
    if ($ppg < 0 || $ppg > 50) {
        http_response_code(400);
        echo json_encode(['error' => 'PPG must be between 0 and 50']);
        return;
    }
    
    if ($years < 0 || $years > 25) {
        http_response_code(400);
        echo json_encode(['error' => 'Years must be between 0 and 25']);
        return;
    }
    
    $players = loadPlayers();
    $found = false;
    
    foreach ($players as &$player) {
        if ($player['id'] == $id) {
            $player['name'] = trim($data['name']);
            $player['team'] = trim($data['team']);
            $player['position'] = $data['position'] ?? 'PG';
            $player['ppg'] = $ppg;
            $player['years'] = $years;
            $found = true;
            $updatedPlayer = $player;
            break;
        }
    }
    
    if (!$found) {
        http_response_code(404);
        echo json_encode(['error' => 'Player not found']);
        return;
    }
    
    savePlayers($players);
    echo json_encode($updatedPlayer);
}

function deletePlayer($id) {
    $players = loadPlayers();
    $found = false;
    $newPlayers = [];
    
    foreach ($players as $player) {
        if ($player['id'] == $id) {
            $found = true;
        } else {
            $newPlayers[] = $player;
        }
    }
    
    if (!$found) {
        http_response_code(404);
        echo json_encode(['error' => 'Player not found']);
        return;
    }
    
    savePlayers($newPlayers);
    echo json_encode(['message' => 'Player deleted successfully']);
}

function getStats() {
    $players = loadPlayers();
    
    if (count($players) == 0) {
        echo json_encode([
            'total_players' => 0,
            'avg_ppg' => 0,
            'avg_years' => 0,
            'position_counts' => ['PG' => 0, 'SG' => 0, 'SF' => 0, 'PF' => 0, 'C' => 0]
        ]);
        return;
    }
    
    $totalPpg = 0;
    $totalYears = 0;
    $positionCounts = ['PG' => 0, 'SG' => 0, 'SF' => 0, 'PF' => 0, 'C' => 0];
    
    foreach ($players as $player) {
        $totalPpg += $player['ppg'];
        $totalYears += $player['years'];
        $positionCounts[$player['position']]++;
    }
    
    echo json_encode([
        'total_players' => count($players),
        'avg_ppg' => round($totalPpg / count($players), 2),
        'avg_years' => round($totalYears / count($players), 2),
        'position_counts' => $positionCounts
    ]);
}

// Initialize data
initializeData();

// Router
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove /api prefix if present
$path = preg_replace('#^/api#', '', $path);

// Route handling
if (preg_match('#^/players/(\d+)$#', $path, $matches)) {
    $playerId = $matches[1];
    
    switch ($requestMethod) {
        case 'GET':
            getPlayer($playerId);
            break;
        case 'PUT':
            updatePlayer($playerId);
            break;
        case 'DELETE':
            deletePlayer($playerId);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} elseif ($path === '/players' || $path === '/players/') {
    switch ($requestMethod) {
        case 'GET':
            getPlayers();
            break;
        case 'POST':
            createPlayer();
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} elseif ($path === '/stats' || $path === '/stats/') {
    if ($requestMethod === 'GET') {
        getStats();
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
}
?>
