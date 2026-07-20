<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Support\CrmStorage;
use Carbon\Carbon;

class CrmSeeder extends Seeder
{
    public function run(): void
    {
        // Define a list of realistic customer names, emails, and phones
        $mockCustomers = [
            ['name' => 'Sarah Jenkins', 'email' => 'sarah.jenkins@novacorp.com', 'phone' => '+63 915 111 2222'],
            ['name' => 'Jollipop Foods Corporation', 'email' => 'orders@jollipopfoods.com', 'phone' => '+63 2 8123 4567'],
            ['name' => 'SM Retail Inc.', 'email' => 'procurement@smretail.com', 'phone' => '+63 2 8765 4321'],
            ['name' => 'TechCorp Solutions', 'email' => 'accounts@techcorp.com', 'phone' => '+63 2 8222 3333'],
            ['name' => 'BrightRetail Group', 'email' => 'info@brightretail.com', 'phone' => '+63 2 8333 4444'],
            ['name' => 'Mr Donald Foods', 'email' => 'mrdonald@gmail.com', 'phone' => '0289183982'],
            ['name' => 'Emmanuel Creo', 'email' => 'emanpogi@hotmail.com', 'phone' => '+639876543210'],
            ['name' => 'Maria Santos', 'email' => 'maria.santos@gmail.com', 'phone' => '+639181234567'],
            ['name' => 'Jose Reyes', 'email' => 'jose.reyes@yahoo.com', 'phone' => '+639221234567'],
            ['name' => 'Elsa Lgh', 'email' => 'elsa.lgh@gmail.com', 'phone' => '+639171112222'],
            ['name' => 'Dee Nuts Corp', 'email' => 'deenuts@gmail.com', 'phone' => '+639172223333'],
            ['name' => 'Lee Kah Retail', 'email' => 'leekah@gmail.com', 'phone' => '+639173334444'],
        ];

        // Reset CRM data file state
        $state = [
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

        // Seed Customers
        foreach ($mockCustomers as $index => $c) {
            $id = $index + 1;
            // Spread out creation dates
            $createdAt = Carbon::now()->subDays(rand(1, 40))->toISOString();
            $state['customers'][] = [
                'id' => $id,
                'created_at' => $createdAt,
                'name' => $c['name'],
                'email' => $c['email'],
                'phone' => $c['phone'],
            ];
        }
        $state['counters']['customer_id'] = count($mockCustomers);

        // Seed Purchase History
        $mockPurchases = [
            ['customer' => 'Jollipop Foods Corporation', 'order_id' => 'ORD-001', 'amount' => '₱12,450', 'days_ago' => 5],
            ['customer' => 'SM Retail Inc.', 'order_id' => 'ORD-002', 'amount' => '₱45,800', 'days_ago' => 12],
            ['customer' => 'TechCorp Solutions', 'order_id' => 'ORD-003', 'amount' => '₱28,900', 'days_ago' => 2],
            ['customer' => 'BrightRetail Group', 'order_id' => 'ORD-004', 'amount' => '₱18,200', 'days_ago' => 20],
            ['customer' => 'Sarah Jenkins', 'order_id' => 'ORD-005', 'amount' => '₱5,400', 'days_ago' => 1],
            ['customer' => 'Maria Santos', 'order_id' => 'ORD-006', 'amount' => '₱15,200', 'days_ago' => 15],
            ['customer' => 'Jose Reyes', 'order_id' => 'ORD-007', 'amount' => '₱8,750', 'days_ago' => 25],
        ];

        foreach ($mockPurchases as $index => $p) {
            $id = $index + 1;
            $state['purchase_history'][] = [
                'id' => $id,
                'purchased_at' => Carbon::now()->subDays($p['days_ago'])->toISOString(),
                'customer' => $p['customer'],
                'order_id' => $p['order_id'],
                'amount' => $p['amount'],
            ];
        }
        $state['counters']['purchase_history_id'] = count($mockPurchases);

        // Seed Communication Logs
        $mockLogs = [
            ['customer' => 'Sarah Jenkins', 'channel' => 'Call', 'subject' => 'Renewal Discussion', 'message' => 'Discussed annual subscription renewal terms.', 'days_ago' => 0],
            ['customer' => 'TechCorp Solutions', 'channel' => 'Meeting', 'subject' => 'Contract Review', 'message' => 'In-person meeting to review SLA compliance.', 'days_ago' => 0],
            ['customer' => 'Jollipop Foods Corporation', 'channel' => 'Email', 'subject' => 'Order Follow-up', 'message' => 'Follow up on shipment status of ORD-001.', 'days_ago' => 4],
            ['customer' => 'SM Retail Inc.', 'channel' => 'Facebook', 'subject' => 'Product Inquiry', 'message' => 'Interested in catalog pricing for Q3 product list.', 'days_ago' => 6],
            ['customer' => 'Nova Corp', 'channel' => 'Email', 'subject' => 'Quotation Request', 'message' => 'Requested official quote for wholesale order.', 'days_ago' => 3],
            ['customer' => 'BrightRetail Group', 'channel' => 'Call', 'subject' => 'Onboarding Sync', 'message' => 'Initial onboarding call completed.', 'days_ago' => 8],
        ];

        foreach ($mockLogs as $index => $l) {
            $id = $index + 1;
            $state['communication_logs'][] = [
                'id' => $id,
                'created_at' => Carbon::now()->subDays($l['days_ago'])->toISOString(),
                'customer' => $l['customer'],
                'channel' => $l['channel'],
                'email' => '',
                'phone' => '',
                'subject' => $l['subject'],
                'message' => $l['message'],
                'handled_by' => 'Admin',
            ];
        }
        $state['counters']['communication_log_id'] = count($mockLogs);

        // Seed Follow-Ups
        $mockFollowUps = [
            ['log_id' => 1, 'task' => 'Call Sarah Jenkins', 'customer' => 'Sarah Jenkins', 'due' => Carbon::now()->setHour(13)->setMinute(0)->toISOString(), 'status' => 'Pending'],
            ['log_id' => 3, 'task' => 'Prepare Q3 Report', 'customer' => 'Jollipop Foods Corporation', 'due' => Carbon::now()->setHour(13)->setMinute(30)->toISOString(), 'status' => 'Pending'],
            ['log_id' => 2, 'task' => 'Meeting with TechCorp', 'customer' => 'TechCorp Solutions', 'due' => Carbon::now()->setHour(15)->setMinute(0)->toISOString(), 'status' => 'Pending'],
            ['log_id' => 5, 'task' => 'Follow up Email', 'customer' => 'Nova Corp', 'due' => Carbon::tomorrow()->toISOString(), 'status' => 'Pending'],
            ['log_id' => 4, 'task' => 'Team Standup', 'customer' => 'SM Retail Inc.', 'due' => Carbon::now()->setHour(16)->setMinute(30)->toISOString(), 'status' => 'Pending'],
            ['log_id' => 5, 'task' => 'Renew Contract - Nova Corp', 'customer' => 'Nova Corp', 'due' => Carbon::now()->next(Carbon::FRIDAY)->setHour(10)->setMinute(0)->toISOString(), 'status' => 'Pending'],
            ['log_id' => 5, 'task' => 'Send Proposal to Nova Corp', 'customer' => 'Nova Corp', 'due' => Carbon::now()->subDays(2)->toISOString(), 'status' => 'Completed'],
            ['log_id' => 6, 'task' => 'Onboard New Client - BrightRetail', 'customer' => 'BrightRetail Group', 'due' => Carbon::now()->subDays(5)->toISOString(), 'status' => 'Completed'],
        ];

        foreach ($mockFollowUps as $index => $fu) {
            $id = $index + 1;
            $state['follow_ups'][] = [
                'id' => $id,
                'communication_log_id' => $fu['log_id'],
                'task' => $fu['task'],
                'customer' => $fu['customer'],
                'due_date' => $fu['due'],
                'status' => $fu['status'],
            ];
        }
        $state['counters']['follow_up_id'] = count($mockFollowUps);

        // Save state to JSON file
        $path = storage_path('app/crm_data.json');
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($path, json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
