<?php

namespace App\Controllers;

use App\Models\Order_model;
use App\Models\User_model;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AiChat extends BaseController
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $session = session();
        $access  = $session->get('access');
        $role    = $session->get('role');

        if (empty($access) || $role !== '1' && $role !== '0') {
            $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized'])->send();
            exit;
        }
    }

    /**
     * POST /admin/ai-chat
     * Accepts { message: string } and returns { reply: string }
     */
    public function chat()
    {
        $apiKey = getenv('GEMINI_API_KEY');
        if (!$apiKey) {
            return $this->response->setJSON(['error' => 'AI not configured. Set GEMINI_API_KEY in .env']);
        }

        $userMessage = trim($this->request->getJSON(true)['message'] ?? '');
        if ($userMessage === '') {
            return $this->response->setJSON(['error' => 'Empty message']);
        }

        // RAG: retrieve relevant knowledge base entries for this query
        $knowledge    = $this->retrieveKnowledge($userMessage);
        $systemPrompt = $this->buildSystemPrompt($knowledge);

        // Maintain conversation history in session (Gemini format)
        $session = session();
        $history = $session->get('ai_chat_history') ?? [];

        $history[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        // Keep last 20 turns to stay within token limits
        if (\count($history) > 20) {
            $history = \array_slice($history, -20);
        }

        // Run agentic loop — Gemini may call tools multiple times before answering
        $reply = $this->callGeminiAgentic($apiKey, $systemPrompt, $history);

        if (isset($reply['error'])) {
            return $this->response->setJSON(['error' => $reply['error']]);
        }

        $history[] = ['role' => 'model', 'parts' => [['text' => $reply['text']]]];
        $session->set('ai_chat_history', $history);

        return $this->response->setJSON(['reply' => $reply['text']]);
    }

    /**
     * DELETE /admin/ai-chat/clear — clears conversation history
     */
    public function clearHistory()
    {
        session()->remove('ai_chat_history');
        return $this->response->setJSON(['ok' => true]);
    }

    // -----------------------------------------------------------------------
    // RAG — Knowledge retrieval
    // -----------------------------------------------------------------------

    private function retrieveKnowledge(string $query): string
    {
        if (empty(trim($query))) {
            return '';
        }

        $db = \Config\Database::connect();

        if (!$db->tableExists('ai_knowledge_base')) {
            return '';
        }

        $rows = $db->query(
            "SELECT title, content
             FROM ai_knowledge_base
             WHERE MATCH(title, content, keywords) AGAINST(? IN BOOLEAN MODE)
             LIMIT 3",
            [$query . '*']
        )->getResultArray();

        if (empty($rows)) {
            return '';
        }

        $chunks = array_map(fn($r) => "**{$r['title']}**: {$r['content']}", $rows);
        return "\n\n## Relevant Knowledge Base\n" . implode("\n\n", $chunks);
    }

    // -----------------------------------------------------------------------
    // System prompt
    // -----------------------------------------------------------------------

    private function buildSystemPrompt(string $knowledge = ''): string
    {
        $today = date('d M Y');

        return <<<PROMPT
You are an intelligent admin assistant for **EASE Sarawak**, a transport and delivery service management platform in Sarawak, Malaysia.

You have tools to query live database data on demand. **Always use tools to fetch numbers, order details, or user counts before answering** — never guess or invent data.

Rules:
- Be concise and professional.
- Always express monetary amounts in Malaysian Ringgit (RM).
- Today's date is {$today}.
- If the data is not available via tools or the knowledge base, say so honestly.
- When suggesting actions, be specific (e.g. "Follow up on Order #X").
{$knowledge}
PROMPT;
    }

    // -----------------------------------------------------------------------
    // Tool definitions (Gemini function_declarations format)
    // -----------------------------------------------------------------------

    private function getToolDefinitions(): array
    {
        return [
            [
                'function_declarations' => [
                    [
                        'name'        => 'get_dashboard_stats',
                        'description' => 'Get overall platform stats: total orders, pending orders, total revenue (RM), and total user count.',
                        'parameters'  => ['type' => 'object', 'properties' => new \stdClass],
                    ],
                    [
                        'name'        => 'search_orders',
                        'description' => 'Search orders by customer name, status, or service type. Returns up to 10 matching orders.',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'status'        => ['type' => 'string', 'description' => 'Filter by order status (pending, completed, cancelled)'],
                                'customer_name' => ['type' => 'string', 'description' => 'Partial first or last name to search'],
                                'service_type'  => ['type' => 'string', 'description' => 'Service type filter (e.g. express, standard, document)'],
                                'limit'         => ['type' => 'integer', 'description' => 'Max results to return (default 10)'],
                            ],
                        ],
                    ],
                    [
                        'name'        => 'get_order_details',
                        'description' => 'Get full details of a specific order by its order_id.',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'order_id' => ['type' => 'string', 'description' => 'The order ID to look up'],
                            ],
                            'required' => ['order_id'],
                        ],
                    ],
                    [
                        'name'        => 'get_revenue_report',
                        'description' => 'Get total revenue (RM) for completed orders, optionally filtered by date range.',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'from_date' => ['type' => 'string', 'description' => 'Start date in YYYY-MM-DD format'],
                                'to_date'   => ['type' => 'string', 'description' => 'End date in YYYY-MM-DD format'],
                            ],
                        ],
                    ],
                    [
                        'name'        => 'get_recent_activity',
                        'description' => 'Get the most recent orders across all statuses to give an overview of recent activity.',
                        'parameters'  => [
                            'type'       => 'object',
                            'properties' => [
                                'limit' => ['type' => 'integer', 'description' => 'Number of recent orders to return (default 5)'],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    private function executeTool(string $name, array $input): string
    {
        $orderModel = new Order_model();
        $userModel  = new User_model();

        switch ($name) {
            case 'get_dashboard_stats':
                $total   = $orderModel->where('is_deleted', 0)->countAllResults();
                $pending = $orderModel->where('status', 'pending')->where('is_deleted', 0)->countAllResults();
                $users   = $userModel->where('is_deleted', 0)->countAllResults();
                $sales   = $orderModel->selectSum('amount')->where('is_deleted', 0)->get()->getRow()->amount ?? 0;
                return json_encode([
                    'total_orders'   => $total,
                    'pending_orders' => $pending,
                    'total_users'    => $users,
                    'total_revenue'  => 'RM ' . number_format((float)$sales, 2),
                ]);

            case 'search_orders':
                $q = $orderModel->where('is_deleted', 0);
                if (!empty($input['status'])) {
                    $q->where('status', $input['status']);
                }
                if (!empty($input['customer_name'])) {
                    $q->groupStart()
                      ->like('first_name', $input['customer_name'])
                      ->orLike('last_name', $input['customer_name'])
                      ->groupEnd();
                }
                if (!empty($input['service_type'])) {
                    $q->like('service_type', $input['service_type']);
                }
                $rows = $q->select('order_id, first_name, last_name, service_type, status, amount, created_date')
                          ->orderBy('created_date', 'DESC')
                          ->limit($input['limit'] ?? 10)
                          ->findAll();
                return json_encode($rows ?: ['message' => 'No orders found matching the criteria.']);

            case 'get_order_details':
                $row = $orderModel
                    ->where('order_id', $input['order_id'])
                    ->where('is_deleted', 0)
                    ->first();
                return json_encode($row ?: ['message' => 'Order not found.']);

            case 'get_revenue_report':
                $q = $orderModel->selectSum('amount')->where('is_deleted', 0)->where('status', 'completed');
                if (!empty($input['from_date'])) {
                    $q->where('created_date >=', $input['from_date'] . ' 00:00:00');
                }
                if (!empty($input['to_date'])) {
                    $q->where('created_date <=', $input['to_date'] . ' 23:59:59');
                }
                $result = $q->get()->getRow();
                $amount = $result->amount ?? 0;
                return json_encode([
                    'revenue' => 'RM ' . number_format((float)$amount, 2),
                    'from'    => $input['from_date'] ?? 'all time',
                    'to'      => $input['to_date']   ?? 'all time',
                ]);

            case 'get_recent_activity':
                $rows = $orderModel
                    ->select('order_id, first_name, last_name, service_type, status, amount, created_date')
                    ->where('is_deleted', 0)
                    ->orderBy('created_date', 'DESC')
                    ->limit($input['limit'] ?? 5)
                    ->findAll();
                return json_encode($rows ?: ['message' => 'No recent orders found.']);

            default:
                return json_encode(['error' => "Unknown tool: {$name}"]);
        }
    }

    // -----------------------------------------------------------------------
    // Agentic loop — Gemini calls tools until it produces a final answer
    // -----------------------------------------------------------------------

    private function callGeminiAgentic(string $apiKey, string $systemPrompt, array $messages): array
    {
        $model         = 'gemini-2.0-flash';
        $maxIterations = 6;
        $url           = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        for ($i = 0; $i < $maxIterations; $i++) {
            $payload = json_encode([
                'system_instruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents'           => $messages,
                'tools'              => $this->getToolDefinitions(),
                'tool_config'        => ['function_calling_config' => ['mode' => 'AUTO']],
                'generationConfig'   => ['maxOutputTokens' => 1024],
            ]);

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $payload,
                CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
                CURLOPT_TIMEOUT        => 30,
            ]);

            $raw      = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            if ($curlErr) {
                return ['error' => 'Network error: ' . $curlErr];
            }

            $body = json_decode($raw, true);

            if ($httpCode !== 200) {
                $msg = $body['error']['message'] ?? 'API error ' . $httpCode;
                return ['error' => $msg];
            }

            $candidate = $body['candidates'][0] ?? null;
            if (!$candidate) {
                return ['error' => 'No response from AI.'];
            }

            $parts = $candidate['content']['parts'] ?? [];

            // Check if Gemini wants to call any tools
            $functionCalls = array_filter($parts, fn($p) => isset($p['functionCall']));

            if (!empty($functionCalls)) {
                // Append Gemini's response (including function call blocks) to history
                $messages[] = ['role' => 'model', 'parts' => $parts];

                // Execute each tool and collect results
                $toolResults = [];
                foreach ($functionCalls as $part) {
                    $fc            = $part['functionCall'];
                    $result        = $this->executeTool($fc['name'], $fc['args'] ?? []);
                    $toolResults[] = [
                        'functionResponse' => [
                            'name'     => $fc['name'],
                            'response' => ['result' => $result],
                        ],
                    ];
                }

                // Feed tool results back to Gemini for the next iteration
                $messages[] = ['role' => 'user', 'parts' => $toolResults];
                continue;
            }

            // Final text answer — done
            foreach ($parts as $part) {
                if (isset($part['text'])) {
                    return ['text' => $part['text']];
                }
            }

            break;
        }

        return ['error' => 'The agent could not produce a final answer. Please try again.'];
    }
}
