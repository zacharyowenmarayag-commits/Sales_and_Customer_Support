<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Arr;

class CrmStorage
{
    private const FILE_RELATIVE = 'crm_data.json';

    private static function filePath(): string
    {
        return storage_path('app/' . self::FILE_RELATIVE);
    }

    private static function defaultState(): array
    {
        return [
            'customers' => [],
            'purchase_history' => [],
            'communication_logs' => [],
            'follow_ups' => [],
            'counters' => [
                'customer_id' => 0,
                'purchase_history_id' => 0,
                'communication_log_id' => 0,
                'follow_up_id' => 0,
            ],
        ];
    }

    private static function loadState(): array
    {
        $path = self::filePath();

        if (!file_exists($path)) {
            return self::defaultState();
        }

        $raw = file_get_contents($path);
        if ($raw === false || trim($raw) === '') {
            return self::defaultState();
        }

        $decoded = json_decode($raw, true);
        if (!is_array($decoded)) {
            return self::defaultState();
        }

        $state = array_merge(self::defaultState(), $decoded);

        // Ensure required keys exist with correct types to prevent runtime TypeErrors.
        $state['customers'] = is_array($state['customers'] ?? null) ? $state['customers'] : [];
        $state['purchase_history'] = is_array($state['purchase_history'] ?? null) ? $state['purchase_history'] : [];
        $state['communication_logs'] = is_array($state['communication_logs'] ?? null) ? $state['communication_logs'] : [];
        $state['follow_ups'] = is_array($state['follow_ups'] ?? null) ? $state['follow_ups'] : [];

        $state['counters'] = is_array($state['counters'] ?? null) ? $state['counters'] : [];
        $state['counters']['customer_id'] = (int) ($state['counters']['customer_id'] ?? 0);
        $state['counters']['purchase_history_id'] = (int) ($state['counters']['purchase_history_id'] ?? 0);
        $state['counters']['communication_log_id'] = (int) ($state['counters']['communication_log_id'] ?? 0);
        $state['counters']['follow_up_id'] = (int) ($state['counters']['follow_up_id'] ?? 0);

        return $state;
    }


    private static function saveState(array $state): void
    {
        $path = self::filePath();
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        file_put_contents($path, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private static function nextId(string $key): int
    {
        $state = self::loadState();
        if (!is_array($state['counters'] ?? null)) {
            $state['counters'] = [];
        }

        $current = 0;
        if (isset($state['counters']) && is_array($state['counters'])) {
            $current = (int) ($state['counters'][$key] ?? 0);
        }
        $state['counters'][$key] = $current + 1;


        $id = (int) $state['counters'][$key];
        self::saveState($state);
        return $id;
    }

    /**
     * @return object[] each having created_at (Carbon) and other scalar props
     */
    public static function listCustomers(?string $q = null): array
    {
        $state = self::loadState();
        $customers = $state['customers'] ?? [];

        $q = $q !== null ? trim($q) : null;

        $filtered = array_values(array_filter($customers, function ($customer) use ($q) {
            if (!$q) {
                return true;
            }

            $name = (string) ($customer['name'] ?? '');

            return stripos($name, $q) !== false;
        }));

        usort($filtered, function ($a, $b) {
            $ta = strtotime((string) ($a['created_at'] ?? '')) ?: 0;
            $tb = strtotime((string) ($b['created_at'] ?? '')) ?: 0;

            return $ta <=> $tb;
        });

        return array_map(function ($customer) {
            $obj = (object) $customer;
            $obj->created_at = isset($customer['created_at']) ? Carbon::parse($customer['created_at']) : null;

            return $obj;
        }, $filtered);
    }

    public static function createCustomer(array $data): object
    {
        $id = self::nextId('customer_id');
        $state = self::loadState();

        $now = Carbon::now();

        $record = [
            'id' => $id,
            'created_at' => $now->toISOString(),
            'name' => (string) ($data['name'] ?? ''),
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
        ];

        $state['customers'][] = $record;
        self::saveState($state);

        $obj = (object) $record;
        $obj->created_at = $now;

        return $obj;
    }

    /**
     * @return object[] each having created_at (Carbon) and other scalar props
     */
    public static function listCommunicationLogs(?string $q = null, ?string $subject = null): array
    {
        $state = self::loadState();
        $logs = $state['communication_logs'] ?? [];

        $q = $q !== null ? trim($q) : null;
        $subject = $subject !== null ? trim($subject) : null;

        $filtered = array_values(array_filter($logs, function ($log) use ($q, $subject) {
            if ($subject && ($log['subject'] ?? null) !== $subject) {
                return false;
            }
            if ($q) {
                $customer = (string) ($log['customer'] ?? '');
                if (stripos($customer, $q) === false) {
                    return false;
                }
            }
            return true;
        }));

        // Sort oldest first so newly added entries appear at the bottom.
        usort($filtered, function ($a, $b) {
            $ta = strtotime((string) ($a['created_at'] ?? '')) ?: 0;
            $tb = strtotime((string) ($b['created_at'] ?? '')) ?: 0;
            return $ta <=> $tb;
        });

        return array_map(function ($log) {
            $obj = (object) $log;
            $obj->created_at = isset($log['created_at']) ? Carbon::parse($log['created_at']) : null;
            return $obj;
        }, $filtered);
    }

    public static function createCommunicationLog(array $data): object
    {
        $id = self::nextId('communication_log_id');
        $state = self::loadState();

        $now = Carbon::now();

        $record = [
            'id' => $id,
            'created_at' => $now->toISOString(),
            'customer' => (string) ($data['customer'] ?? ''),
            'channel' => (string) ($data['channel'] ?? ''),
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'subject' => (string) ($data['subject'] ?? ''),
            'message' => $data['message'] ?? null,
            'handled_by' => (string) ($data['handled_by'] ?? 'Admin'),
        ];

        $state['communication_logs'][] = $record;
        self::saveState($state);

        $obj = (object) $record;
        $obj->created_at = $now;
        return $obj;
    }

    /**
     * @return object[] each having due_date (Carbon|null)
     */
    public static function listFollowUps(?string $q = null, ?string $status = null): array
    {
        $state = self::loadState();
        $followUps = $state['follow_ups'] ?? [];

        $q = $q !== null ? trim($q) : null;
        $status = $status !== null ? trim($status) : null;

        $filtered = array_values(array_filter($followUps, function ($fu) use ($q, $status) {
            if ($status && ($fu['status'] ?? null) !== $status) {
                return false;
            }

            if (!$q) {
                return true;
            }

            $customer = (string) ($fu['customer'] ?? '');

            return stripos($customer, $q) !== false;
        }));

        usort($filtered, function ($a, $b) {
            return ((int) ($a['id'] ?? 0)) <=> ((int) ($b['id'] ?? 0));
        });

        return array_map(function ($fu) {
            $obj = (object) $fu;
            $obj->due_date = !empty($fu['due_date']) ? Carbon::parse($fu['due_date']) : null;
            return $obj;
        }, $filtered);
    }

    public static function createFollowUp(array $data): object
    {
        $id = self::nextId('follow_up_id');
        $state = self::loadState();

        $due = $data['due_date'] ?? now()->addDays(7);
        if ($due instanceof Carbon) {
            $dueIso = $due->toISOString();
        } else {
            $dueIso = Carbon::parse((string) $due)->toISOString();
        }

        $record = [
            'id' => $id,
            'communication_log_id' => (int) ($data['communication_log_id'] ?? 0),
            'task' => (string) ($data['task'] ?? ''),
            'customer' => (string) ($data['customer'] ?? ''),
            'due_date' => $dueIso,
            'status' => (string) ($data['status'] ?? 'Pending'),
        ];

        $state['follow_ups'][] = $record;
        self::saveState($state);

        $obj = (object) $record;
        $obj->due_date = Carbon::parse($dueIso);
        return $obj;
    }

    public static function updateFollowUpStatus(int|string $followUpId, string $status): ?object
    {
        $state = self::loadState();
        $id = (int) $followUpId;

        $updated = null;
        foreach ($state['follow_ups'] as $idx => $fu) {
            if ((int) ($fu['id'] ?? 0) === $id) {
                $state['follow_ups'][$idx]['status'] = $status;
                $updated = $state['follow_ups'][$idx];
                break;
            }
        }

        if ($updated === null) {
            return null;
        }

        self::saveState($state);

        $obj = (object) $updated;
        $obj->due_date = !empty($updated['due_date']) ? Carbon::parse($updated['due_date']) : null;
        return $obj;
    }

    public static function communicationLogsCount(): int
    {
        $state = self::loadState();
        return count($state['communication_logs'] ?? []);
    }

    public static function followUpsCountByStatus(string $status): int
    {
        $state = self::loadState();
        $list = $state['follow_ups'] ?? [];
        return count(array_filter($list, fn ($fu) => ($fu['status'] ?? null) === $status));
    }

    /**
     * @return string[] distinct customer names
     */
    public static function distinctCustomers(): array
    {
        $state = self::loadState();
        $names = array_map(fn ($c) => (string) ($c['name'] ?? ''), $state['customers'] ?? []);
        $names = array_values(array_filter(array_unique($names)));
        sort($names);

        return $names;
    }

    /**
     * @return string[] customers that have at least one follow-up
     */
    public static function customersWithFollowUps(): array
    {
        $state = self::loadState();
        $names = array_map(fn ($fu) => (string) ($fu['customer'] ?? ''), $state['follow_ups'] ?? []);
        $names = array_values(array_filter(array_unique($names)));
        sort($names);
        return $names;
    }

    /**
     * @return array{order_id:string,amount:string}
     */
    public static function parseOrderDetailsFromMessage(?string $message): array
    {
        $orderId = '—';
        $amount = '—';
        $msg = (string) ($message ?? '');

        if (preg_match('/\b(ORD-[A-Za-z0-9_-]+)\b/', $msg, $m)) {
            $orderId = $m[1];
        }

        if (preg_match('/₱\s*([0-9]{1,3}(?:,[0-9]{3})*(?:\.[0-9]+)?|[0-9]+(?:\.[0-9]+)?)/u', $msg, $m)) {
            $amount = '₱' . $m[1];
        }

        return [
            'order_id' => $orderId,
            'amount' => $amount,
        ];
    }

    public static function createPurchaseHistory(array $data): object
    {
        $id = self::nextId('purchase_history_id');
        $state = self::loadState();

        $purchasedAt = $data['purchased_at'] ?? now();
        if ($purchasedAt instanceof Carbon) {
            $purchasedAtIso = $purchasedAt->toISOString();
        } else {
            $purchasedAtIso = Carbon::parse((string) $purchasedAt)->toISOString();
        }

        $record = [
            'id' => $id,
            'purchased_at' => $purchasedAtIso,
            'customer' => (string) ($data['customer'] ?? ''),
            'order_id' => (string) ($data['order_id'] ?? '—'),
            'amount' => (string) ($data['amount'] ?? '—'),
        ];

        $state['purchase_history'][] = $record;
        self::saveState($state);

        $obj = (object) $record;
        $obj->purchased_at = Carbon::parse($purchasedAtIso);

        return $obj;
    }

    /**
     * @return object[] each having purchased_at (Carbon)
     */
    public static function listPurchaseHistory(?string $q = null): array
    {
        self::ensurePurchaseHistorySeeded();

        $state = self::loadState();
        $records = $state['purchase_history'] ?? [];

        $q = $q !== null ? trim($q) : null;

        $filtered = array_values(array_filter($records, function ($record) use ($q) {
            if (!$q) {
                return true;
            }

            $customer = (string) ($record['customer'] ?? '');

            return stripos($customer, $q) !== false;
        }));

        usort($filtered, function ($a, $b) {
            $ta = strtotime((string) ($a['purchased_at'] ?? '')) ?: 0;
            $tb = strtotime((string) ($b['purchased_at'] ?? '')) ?: 0;

            return $ta <=> $tb;
        });

        return array_map(function ($record) {
            $obj = (object) $record;
            $obj->purchased_at = !empty($record['purchased_at']) ? Carbon::parse($record['purchased_at']) : null;

            return $obj;
        }, $filtered);
    }

    /**
     * @return string[] distinct customer names with purchase records
     */
    public static function customersWithPurchases(): array
    {
        $records = self::listPurchaseHistory();
        $names = array_map(fn ($record) => (string) $record->customer, $records);
        $names = array_values(array_filter(array_unique($names)));
        sort($names);

        return $names;
    }

    private static function ensurePurchaseHistorySeeded(): void
    {
        $state = self::loadState();

        if (!empty($state['purchase_history'] ?? [])) {
            return;
        }

        foreach ($state['communication_logs'] ?? [] as $log) {
            if (($log['subject'] ?? '') !== 'Order Follow-up') {
                continue;
            }

            $details = self::parseOrderDetailsFromMessage($log['message'] ?? null);

            self::createPurchaseHistory([
                'customer' => (string) ($log['customer'] ?? ''),
                'order_id' => $details['order_id'],
                'amount' => $details['amount'],
                'purchased_at' => $log['created_at'] ?? now(),
            ]);
        }

        $state = self::loadState();
        if (!empty($state['purchase_history'] ?? [])) {
            return;
        }

        self::createPurchaseHistory([
            'customer' => 'Jollipop Foods Corporation',
            'order_id' => 'ORD-001',
            'amount' => '₱12,450',
            'purchased_at' => now()->subDays(30),
        ]);
    }

    /**
     * @return array<int,array{date:string,customer:string,order_id:string,amount:string}>
     */
    public static function purchaseHistoryRows(?string $q = null): array
    {
        return array_map(function ($record) {
            return [
                'date' => $record->purchased_at ? $record->purchased_at->format('M j, Y') : '—',
                'customer' => (string) $record->customer,
                'order_id' => (string) $record->order_id,
                'amount' => (string) $record->amount,
            ];
        }, self::listPurchaseHistory($q));
    }

    // --- optional seed for usability ---
    public static function ensureSeeded(): void
    {
        $state = self::loadState();

        if (empty($state['customers'] ?? [])) {
            self::createCustomer([
                'name' => 'Jollipop Foods Corporation',
                'email' => 'orders@jollipopfoods.com',
                'phone' => '+63 2 8123 4567',
            ]);

            self::createCustomer([
                'name' => 'SM Retail Inc.',
                'email' => 'procurement@smretail.com',
                'phone' => '+63 2 8765 4321',
            ]);
        }

        $state = self::loadState();
        if (!empty($state['communication_logs'] ?? [])) {
            return;
        }

        // Minimal demo data so UI is immediately usable.
        self::createCommunicationLog([
            'customer' => 'Jollipop Foods Corporation',
            'channel' => 'Email',
            'email' => null,
            'phone' => null,
            'subject' => 'Order Follow-up',
            'message' => 'Order ORD-001 - Total ₱12,450 - Follow up requested.',
            'handled_by' => 'Admin',
        ]);

        self::createCommunicationLog([
            'customer' => 'SM Retail Inc.',
            'channel' => 'Facebook',
            'email' => null,
            'phone' => null,
            'subject' => 'Product Inquiry',
            'message' => 'Interested in bulk pricing.',
            'handled_by' => 'Admin',
        ]);

        // Create a follow-up for the first demo log.
        $logs = self::listCommunicationLogs(null, 'Order Follow-up');
        if (!empty($logs)) {
            $first = $logs[0];
            self::createFollowUp([
                'communication_log_id' => $first->id,
                'task' => 'Order Follow-up: ORD-001',
                'customer' => $first->customer,
                'due_date' => now()->addDays(7),
                'status' => 'Pending',
            ]);
        }
    }
}

