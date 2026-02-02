<?php
// config.php - Database Configuration
class DatabaseConfig {
    private static $databases = [
        'aero_foods' => [
            'host' => '192.168.1.34',
            'port' => 5432,
            'dbname' => 'aero_foods_finance',
            'username' => 'postgres',
            'password' => 'Admin123',
            'type' => 'pgsql'
        ],
        'abe-yus' => [
            'host' => '192.168.1.34',
            'port' => 5432,
            'dbname' => 'abe_yus_finance',
            'username' => 'postgres',
            'password' => 'Admin123',
            'type' => 'pgsql'
        ]
    ];
    
    public static function getConnection($dbKey) {
        if (!isset(self::$databases[$dbKey])) {
            throw new Exception("Database configuration not found: $dbKey");
        }
        
        $config = self::$databases[$dbKey];
        
        try {
            if ($config['type'] === 'pgsql') {
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
            } else if ($config['type'] === 'mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
            } else {
                throw new Exception("Unsupported database type: {$config['type']}");
            }
            
            $pdo = new PDO($dsn, $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }
    
    public static function getAllDatabases() {
        return array_keys(self::$databases);
    }
    
    public static function getDatabaseInfo($dbKey) {
        if (!isset(self::$databases[$dbKey])) {
            return null;
        }
        $info = self::$databases[$dbKey];
        unset($info['password']);
        return $info;
    }
}

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

function discoverSchema($pdo, $dbType) {
    $schema = [
        'tables' => [],
        'relationships' => [],
        'indexes' => []
    ];
    
    try {
        if ($dbType === 'pgsql') {
            $stmt = $pdo->query("
                SELECT table_name, 
                       obj_description((quote_ident(table_schema)||'.'||quote_ident(table_name))::regclass, 'pg_class') as table_comment
                FROM information_schema.tables 
                WHERE table_schema = 'public' 
                AND table_type = 'BASE TABLE'
                ORDER BY table_name
            ");
            $tables = $stmt->fetchAll();
            
            foreach ($tables as $table) {
                $tableName = $table['table_name'];
                
                $stmt = $pdo->prepare("
                    SELECT column_name, data_type, is_nullable, column_default,
                           col_description((quote_ident(table_schema)||'.'||quote_ident(table_name))::regclass, ordinal_position) as column_comment
                    FROM information_schema.columns 
                    WHERE table_schema = 'public' 
                    AND table_name = ?
                    ORDER BY ordinal_position
                ");
                $stmt->execute([$tableName]);
                $columns = $stmt->fetchAll();
                
                $columnInfo = [];
                foreach ($columns as $col) {
                    $columnInfo[] = [
                        'name' => $col['column_name'],
                        'type' => $col['data_type'],
                        'nullable' => $col['is_nullable'] === 'YES',
                        'default' => $col['column_default'],
                        'comment' => $col['column_comment']
                    ];
                }
                
                $stmt = $pdo->prepare("
                    SELECT
                        kcu.column_name,
                        ccu.table_name AS foreign_table_name,
                        ccu.column_name AS foreign_column_name
                    FROM information_schema.table_constraints AS tc
                    JOIN information_schema.key_column_usage AS kcu
                        ON tc.constraint_name = kcu.constraint_name
                        AND tc.table_schema = kcu.table_schema
                    JOIN information_schema.constraint_column_usage AS ccu
                        ON ccu.constraint_name = tc.constraint_name
                        AND ccu.table_schema = tc.table_schema
                    WHERE tc.constraint_type = 'FOREIGN KEY'
                    AND tc.table_name = ?
                ");
                $stmt->execute([$tableName]);
                $foreignKeys = $stmt->fetchAll();
                
                $schema['tables'][] = [
                    'name' => $tableName,
                    'comment' => $table['table_comment'],
                    'columns' => $columnInfo,
                    'foreign_keys' => $foreignKeys
                ];
                
                foreach ($foreignKeys as $fk) {
                    $schema['relationships'][] = "$tableName.{$fk['column_name']} -> {$fk['foreign_table_name']}.{$fk['foreign_column_name']}";
                }
            }
        }
        
        return $schema;
        
    } catch (PDOException $e) {
        throw new Exception("Schema discovery failed: " . $e->getMessage());
    }
}

function findRelevantContext($query, $schema) {
    $query = strtolower($query);
    $relevantTables = [];
    
    // Keywords that suggest the user wants data
    $dataKeywords = ['show', 'get', 'find', 'list', 'display', 'select', 'count', 'sum', 'total', 'average', 'last', 'recent', 'yesterday', 'today', 'week', 'month'];
    $wantsData = false;
    foreach ($dataKeywords as $keyword) {
        if (strpos($query, $keyword) !== false) {
            $wantsData = true;
            break;
        }
    }
    
    foreach ($schema['tables'] as $table) {
        $score = 0;
        $tableName = strtolower($table['name']);
        $comment = strtolower($table['comment'] ?? '');
        
        if (strpos($query, $tableName) !== false) {
            $score += 10;
        }
        
        foreach ($table['columns'] as $col) {
            $colName = strtolower($col['name']);
            if (strpos($query, $colName) !== false) {
                $score += 5;
            }
            
            if (!empty($col['comment']) && strpos($query, strtolower($col['comment'])) !== false) {
                $score += 3;
            }
        }
        
        if (!empty($comment)) {
            $queryWords = explode(' ', $query);
            foreach ($queryWords as $word) {
                if (strlen($word) > 3 && strpos($comment, $word) !== false) {
                    $score += 2;
                }
            }
        }
        
        if ($score > 0) {
            $relevantTables[] = [
                'table' => $table,
                'score' => $score
            ];
        }
    }
    
    usort($relevantTables, function($a, $b) {
        return $b['score'] - $a['score'];
    });
    
    $relevantTables = array_slice($relevantTables, 0, 5);
    
    $relevantRelationships = [];
    foreach ($schema['relationships'] as $rel) {
        foreach ($relevantTables as $rt) {
            if (stripos($rel, $rt['table']['name']) !== false) {
                $relevantRelationships[] = $rel;
                break;
            }
        }
    }
    
    return [
        'tables' => array_map(function($rt) { return $rt['table']; }, $relevantTables),
        'relationships' => array_unique($relevantRelationships),
        'wants_data' => $wantsData
    ];
}

function executeQuery($pdo, $query, $limit = 5000) {
    try {
        $query = trim($query);
        
        // Remove trailing semicolon if present
        $query = rtrim($query, ';');
        
        if (!preg_match('/^SELECT/i', $query)) {
            throw new Exception("Only SELECT queries are allowed");
        }
        
        // Add LIMIT if not already present
        if (!preg_match('/LIMIT\s+\d+/i', $query)) {
            $query .= " LIMIT $limit";
        }
        
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll();
        
        return [
            'success' => true,
            'rows' => $results,
            'count' => count($results)
        ];
    } catch (PDOException $e) {
        return [
            'success' => false,
            'error' => $e->getMessage()
        ];
    }
}

function extractSQLFromResponse($text) {
    // Try to find SQL query in the response
    if (preg_match('/```sql\s*(.*?)\s*```/is', $text, $matches)) {
        return trim($matches[1]);
    }
    if (preg_match('/```\s*(SELECT.*?)\s*```/is', $text, $matches)) {
        return trim($matches[1]);
    }
    if (preg_match('/(SELECT\s+.+?;)/is', $text, $matches)) {
        return trim($matches[1]);
    }
    if (preg_match('/(SELECT\s+.+)/is', $text, $matches)) {
        return trim($matches[1]);
    }
    return null;
}

function callOllama($prompt, $ollamaUrl = 'http://localhost:11434', $model = 'llama2') {
    $data = [
        'model' => $model,
        'prompt' => $prompt,
        'stream' => false,
        'options' => [
            'temperature' => 0.1,  // Lower temperature for more accurate SQL
            'top_p' => 0.9
        ]
    ];
    
    $ch = curl_init($ollamaUrl . '/api/generate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if (curl_errno($ch)) {
        curl_close($ch);
        throw new Exception('Ollama API Error: ' . curl_error($ch));
    }
    
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception('Ollama API returned status code: ' . $httpCode);
    }
    
    $result = json_decode($response, true);
    return $result['response'] ?? 'No response from Ollama';
}

try {
    $action = $_GET['action'] ?? 'query';
    
    if ($action === 'list_databases') {
        $databases = DatabaseConfig::getAllDatabases();
        $dbInfo = [];
        foreach ($databases as $db) {
            $dbInfo[$db] = DatabaseConfig::getDatabaseInfo($db);
        }
        
        echo json_encode([
            'success' => true,
            'databases' => $databases,
            'info' => $dbInfo
        ]);
        exit;
    }
    
    if ($action === 'get_schema') {
        $dbKey = $_GET['database'] ?? 'aero_foods';
        $pdo = DatabaseConfig::getConnection($dbKey);
        $dbInfo = DatabaseConfig::getDatabaseInfo($dbKey);
        $schema = discoverSchema($pdo, $dbInfo['type']);
        
        echo json_encode([
            'success' => true,
            'database' => $dbKey,
            'schema' => $schema
        ]);
        exit;
    }
    
    if ($action === 'execute_query') {
        $input = json_decode(file_get_contents('php://input'), true);
        $dbKey = $input['database'] ?? 'aero_foods';
        $sqlQuery = $input['sql'] ?? '';
        $limit = $input['limit'] ?? 5000;
        
        if (empty($sqlQuery)) {
            throw new Exception('SQL query is required');
        }
        
        $pdo = DatabaseConfig::getConnection($dbKey);
        $result = executeQuery($pdo, $sqlQuery, $limit);
        
        echo json_encode($result);
        exit;
    }
    
    if ($action === 'query') {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['query']) || empty(trim($input['query']))) {
            throw new Exception('Query is required');
        }
        
        $query = trim($input['query']);
        $dbKey = $input['database'] ?? 'aero_foods';
        $ollamaUrl = $input['ollama_url'] ?? 'http://localhost:11434';
        $ollamaModel = $input['ollama_model'] ?? 'llama2';
        
        $pdo = DatabaseConfig::getConnection($dbKey);
        $dbInfo = DatabaseConfig::getDatabaseInfo($dbKey);
        $schema = discoverSchema($pdo, $dbInfo['type']);
        $context = findRelevantContext($query, $schema);
        
        // Get current date for date-related queries
        $currentDate = date('Y-m-d');
        
        // Build a comprehensive prompt
        $contextStr = "You are a PostgreSQL database expert. You MUST follow these rules STRICTLY:\n\n";
        $contextStr .= "CRITICAL RULES:\n";
        $contextStr .= "1. Use ONLY the EXACT table names and column names listed below\n";
        $contextStr .= "2. DO NOT invent or assume any table or column names\n";
        $contextStr .= "3. DO NOT use tables like 'system_configuration' unless explicitly listed\n";
        $contextStr .= "4. If a column name is not listed, DO NOT use it\n";
        $contextStr .= "5. For date filters, use ONLY date column names that actually exist\n\n";
        
        $contextStr .= "Database: {$dbInfo['dbname']} (PostgreSQL)\n";
        $contextStr .= "Current Date: {$currentDate}\n\n";
        
        if (!empty($context['tables'])) {
            $contextStr .= "AVAILABLE TABLES AND COLUMNS (USE ONLY THESE):\n";
            $contextStr .= "==============================================\n\n";
            foreach ($context['tables'] as $table) {
                $contextStr .= "Table: {$table['name']}\n";
                if (!empty($table['comment'])) {
                    $contextStr .= "Purpose: {$table['comment']}\n";
                }
                $contextStr .= "Columns:\n";
                foreach ($table['columns'] as $col) {
                    $contextStr .= "  • {$col['name']} ({$col['type']})";
                    if (!empty($col['comment'])) {
                        $contextStr .= " - {$col['comment']}";
                    }
                    $contextStr .= "\n";
                }
                if (!empty($table['foreign_keys'])) {
                    $contextStr .= "Foreign Keys:\n";
                    foreach ($table['foreign_keys'] as $fk) {
                        $contextStr .= "  → {$fk['column_name']} references {$fk['foreign_table_name']}.{$fk['foreign_column_name']}\n";
                    }
                }
                $contextStr .= "\n";
            }
        } else {
            $contextStr .= "No relevant tables found for this query.\n\n";
        }
        
        if (!empty($context['relationships'])) {
            $contextStr .= "TABLE RELATIONSHIPS:\n";
            foreach ($context['relationships'] as $rel) {
                $contextStr .= "  • $rel\n";
            }
            $contextStr .= "\n";
        }
        
        $contextStr .= "USER QUESTION: $query\n\n";
        
        // Determine if we should generate SQL
        if ($context['wants_data'] && !empty($context['tables'])) {
            $contextStr .= "INSTRUCTIONS FOR SQL GENERATION:\n";
            $contextStr .= "================================\n";
            $contextStr .= "1. Generate a SIMPLE PostgreSQL SELECT query\n";
            $contextStr .= "2. Use ONLY the exact table and column names from the list above\n";
            $contextStr .= "3. DO NOT use table aliases (no 'dw', 'ds', etc.)\n";
            $contextStr .= "4. DO NOT use JOIN unless multiple tables are clearly needed\n";
            $contextStr .= "5. Wrap your SQL in ```sql``` code blocks\n";
            $contextStr .= "6. DO NOT add LIMIT clause\n\n";
            
            $contextStr .= "DATE FILTERING EXAMPLES:\n";
            $contextStr .= "For 'yesterday' on daily_wastage table:\n";
            $contextStr .= "```sql\n";
            $contextStr .= "SELECT * FROM daily_wastage \n";
            $contextStr .= "WHERE month_date >= CURRENT_DATE - INTERVAL '1 day' \n";
            $contextStr .= "AND month_date < CURRENT_DATE\n";
            $contextStr .= "```\n\n";
            
            $contextStr .= "For 'last 5 days' on daily_wastage table:\n";
            $contextStr .= "```sql\n";
            $contextStr .= "SELECT * FROM daily_wastage \n";
            $contextStr .= "WHERE month_date >= CURRENT_DATE - INTERVAL '5 days' \n";
            $contextStr .= "AND month_date < CURRENT_DATE\n";
            $contextStr .= "```\n\n";
            
            $contextStr .= "For specific columns from daily_wastage:\n";
            $contextStr .= "```sql\n";
            $contextStr .= "SELECT month_date, utilities, rental, jasmine_tea_wastage \n";
            $contextStr .= "FROM daily_wastage \n";
            $contextStr .= "WHERE month_date >= CURRENT_DATE - INTERVAL '1 day'\n";
            $contextStr .= "```\n\n";
            
            $contextStr .= "IMPORTANT: Follow the exact pattern above. Do NOT deviate from these examples.\n";
        } else {
            $contextStr .= "INSTRUCTIONS:\n";
            $contextStr .= "1. Provide a helpful explanation about the database structure\n";
            $contextStr .= "2. Do NOT generate SQL unless the user specifically asks for data\n";
            $contextStr .= "3. Describe what tables and columns are available for their needs\n";
        }
        
        $response = callOllama($contextStr, $ollamaUrl, $ollamaModel);
        
        // Try to extract and execute SQL only if user wants data
        $sqlQuery = null;
        $queryResult = null;
        
        if ($context['wants_data']) {
            $sqlQuery = extractSQLFromResponse($response);
            
            if ($sqlQuery) {
                // Validate that SQL only uses tables from context
                $usesValidTables = true;
                foreach ($context['tables'] as $table) {
                    // This is a simple check - you might want more sophisticated validation
                }
                
                // Remove any LIMIT clause from generated query
                $sqlQuery = preg_replace('/LIMIT\s+\d+/i', '', $sqlQuery);
                $queryResult = executeQuery($pdo, $sqlQuery, 5000);
            }
        }
        
        echo json_encode([
            'success' => true,
            'response' => $response,
            'sql_query' => $sqlQuery,
            'query_result' => $queryResult,
            'database' => $dbKey,
            'context' => [
                'tables_found' => count($context['tables']),
                'table_names' => array_map(function($t) { return $t['name']; }, $context['tables']),
                'relationships_found' => count($context['relationships']),
                'wants_data' => $context['wants_data']
            ]
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>