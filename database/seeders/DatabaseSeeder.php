<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\ServiceItem;
use App\Models\Ticket;
use App\Models\TicketActivity;
use App\Models\TicketApproval;
use App\Models\TicketComment;
use App\Models\User;
use App\Notifications\TicketNotification;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        $reviewer = User::create([
            'name' => 'Team Lead Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'password123',
            'role' => 'reviewer',
        ]);

        $employee = User::create([
            'name' => 'Regular Employee',
            'email' => 'employee@example.com',
            'password' => 'password123',
            'role' => 'regular',
        ]);

        $employeeTwo = User::create([
            'name' => 'Second Employee',
            'email' => 'employee2@example.com',
            'password' => 'password123',
            'role' => 'regular',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Service items
        |--------------------------------------------------------------------------
        */

        $vacation = ServiceItem::create([
            'name' => 'Vacation Request',
            'description' => 'Request approval for annual leave.',
            'is_active' => true,
        ]);

        $infrastructure = ServiceItem::create([
            'name' => 'Infrastructure Access',
            'description' => 'Request access to infrastructure or environments.',
            'is_active' => true,
        ]);

        $software = ServiceItem::create([
            'name' => 'Software Request',
            'description' => 'Request installation or purchase of software.',
            'is_active' => true,
        ]);

        ServiceItem::create([
            'name' => 'Disabled Test Service',
            'description' => 'This service should not appear when creating tickets.',
            'is_active' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ticket 1 — Waiting for review
        |--------------------------------------------------------------------------
        */

        $ticketOne = Ticket::create([
            'ticket_number' => 'REQ-000001',
            'requester_id' => $employee->id,
            'service_item_id' => $vacation->id,
            'reviewer_id' => $reviewer->id,
            'title' => 'Vacation request for July',
            'description' => 'I would like to request vacation from 10 July until 15 July.',
            'status' => 'submitted',
            'priority' => 'normal',
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketOne->id,
            'user_id' => $employee->id,
            'type' => 'ticket_created',
            'description' => 'Ticket was created.',
            'metadata' => [
                'status' => 'submitted',
                'reviewer_id' => $reviewer->id,
            ],
        ]);

        TicketComment::create([
            'ticket_id' => $ticketOne->id,
            'user_id' => $employee->id,
            'comment' => 'Please let me know if more information is required.',
            'is_internal' => false,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketOne->id,
            'user_id' => $employee->id,
            'type' => 'comment_added',
            'description' => 'A comment was added.',
            'metadata' => [
                'comment' => 'Please let me know if more information is required.',
                'is_internal' => false,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ticket 2 — Approved
        |--------------------------------------------------------------------------
        */

        $ticketTwo = Ticket::create([
            'ticket_number' => 'REQ-000002',
            'requester_id' => $employeeTwo->id,
            'service_item_id' => $software->id,
            'reviewer_id' => $reviewer->id,
            'title' => 'Request PhpStorm licence',
            'description' => 'I need PhpStorm for development work.',
            'status' => 'approved',
            'priority' => 'normal',
        ]);

        TicketApproval::create([
            'ticket_id' => $ticketTwo->id,
            'reviewer_id' => $reviewer->id,
            'decision' => 'approved',
            'comment' => 'The software request is justified and approved.',
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketTwo->id,
            'user_id' => $employeeTwo->id,
            'type' => 'ticket_created',
            'description' => 'Ticket was created.',
            'metadata' => [
                'status' => 'submitted',
                'reviewer_id' => $reviewer->id,
            ],
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketTwo->id,
            'user_id' => $reviewer->id,
            'type' => 'review_decision',
            'description' => 'Ticket was approved.',
            'metadata' => [
                'decision' => 'approved',
                'comment' => 'The software request is justified and approved.',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ticket 3 — More information required
        |--------------------------------------------------------------------------
        */

        $ticketThree = Ticket::create([
            'ticket_number' => 'REQ-000003',
            'requester_id' => $employee->id,
            'service_item_id' => $infrastructure->id,
            'reviewer_id' => $reviewer->id,
            'title' => 'Production infrastructure access',
            'description' => 'I need access to the production monitoring environment.',
            'status' => 'more_information_required',
            'priority' => 'high',
        ]);

        TicketApproval::create([
            'ticket_id' => $ticketThree->id,
            'reviewer_id' => $reviewer->id,
            'decision' => 'more_information_required',
            'comment' => 'Please provide the business reason and required access duration.',
        ]);

        TicketComment::create([
            'ticket_id' => $ticketThree->id,
            'user_id' => $reviewer->id,
            'comment' => 'The infrastructure owner should also review this request.',
            'is_internal' => true,
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketThree->id,
            'user_id' => $employee->id,
            'type' => 'ticket_created',
            'description' => 'Ticket was created.',
            'metadata' => [
                'status' => 'submitted',
                'reviewer_id' => $reviewer->id,
            ],
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketThree->id,
            'user_id' => $reviewer->id,
            'type' => 'review_decision',
            'description' => 'More information was requested.',
            'metadata' => [
                'decision' => 'more_information_required',
                'comment' => 'Please provide the business reason and required access duration.',
            ],
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketThree->id,
            'user_id' => $reviewer->id,
            'type' => 'internal_note_added',
            'description' => 'An internal note was added.',
            'metadata' => [
                'comment' => 'The infrastructure owner should also review this request.',
                'is_internal' => true,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Ticket 4 — Rejected
        |--------------------------------------------------------------------------
        */

        $ticketFour = Ticket::create([
            'ticket_number' => 'REQ-000004',
            'requester_id' => $employeeTwo->id,
            'service_item_id' => $infrastructure->id,
            'reviewer_id' => $admin->id,
            'title' => 'Administrator access request',
            'description' => 'I need administrator access for normal development tasks.',
            'status' => 'rejected',
            'priority' => 'critical',
        ]);

        TicketApproval::create([
            'ticket_id' => $ticketFour->id,
            'reviewer_id' => $admin->id,
            'decision' => 'rejected',
            'comment' => 'Administrator access is not required for this work.',
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketFour->id,
            'user_id' => $employeeTwo->id,
            'type' => 'ticket_created',
            'description' => 'Ticket was created.',
            'metadata' => [
                'status' => 'submitted',
                'reviewer_id' => $admin->id,
            ],
        ]);

        TicketActivity::create([
            'ticket_id' => $ticketFour->id,
            'user_id' => $admin->id,
            'type' => 'review_decision',
            'description' => 'Ticket was rejected.',
            'metadata' => [
                'decision' => 'rejected',
                'comment' => 'Administrator access is not required for this work.',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | General audit logs
        |--------------------------------------------------------------------------
        */

        foreach ([$ticketOne, $ticketTwo, $ticketThree, $ticketFour] as $ticket) {
            AuditLog::create([
                'user_id' => $ticket->requester_id,
                'action' => 'ticket_created',
                'entity_type' => Ticket::class,
                'entity_id' => $ticket->id,
                'description' => "Ticket {$ticket->ticket_number} was created.",
                'old_values' => null,
                'new_values' => [
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                    'reviewer_id' => $ticket->reviewer_id,
                ],
            ]);
        }

        AuditLog::create([
            'user_id' => $reviewer->id,
            'action' => 'ticket_reviewed',
            'entity_type' => Ticket::class,
            'entity_id' => $ticketTwo->id,
            'description' => "Ticket {$ticketTwo->ticket_number} was approved.",
            'old_values' => ['status' => 'submitted'],
            'new_values' => ['status' => 'approved'],
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'action' => 'user_created',
            'entity_type' => User::class,
            'entity_id' => $reviewer->id,
            'description' => "User {$reviewer->name} was created.",
            'old_values' => null,
            'new_values' => [
                'email' => $reviewer->email,
                'role' => $reviewer->role,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Queued test notifications
        |--------------------------------------------------------------------------
        */

        $reviewer->notify(
            new TicketNotification(
                $ticketOne,
                "You were assigned as reviewer for ticket {$ticketOne->ticket_number}."
            )
        );

        $employeeTwo->notify(
            new TicketNotification(
                $ticketTwo,
                'Your software request was approved.'
            )
        );
    }
}
