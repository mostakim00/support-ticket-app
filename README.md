Mini Support Ticketing System
A Laravel-based application for submitting and managing support tickets. Users can submit tickets with a subject, description, and priority, which are automatically assigned to support agents with the least open tickets. The system displays a user's tickets and provides a report of top support agents based on ticket assignments over the last 7 days.
Setup Instructions
Prerequisites

PHP >= 8.1
Composer
MySQL
Node.js and npm (optional, for Bootstrap if installed locally)
Laravel CLI

Installation Steps

Clone the Repository
git clone <repository-url>
cd <repository-directory>


Install DependenciesInstall PHP dependencies via Composer:
composer install

If using Bootstrap locally, install Node.js dependencies:
npm install
npm run build


Configure Environment

Copy the .env.example file to .env:cp .env.example .env


Update .env with your database credentials:DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password


Set the queue connection:QUEUE_CONNECTION=database




Generate Application Key
php artisan key:generate


Run MigrationsCreate the database tables:
php artisan migrate


Seed the DatabasePopulate the users table with test data (a user and two support agents):
php artisan db:seed


Start the Development Server
php artisan serve

Access the application at http://127.0.0.1:8000/index or http://127.0.0.1:8000/tickets.


Queue Setup Instructions
The application uses Laravel’s queue system to assign tickets to support agents asynchronously.

Create the Jobs TableGenerate the jobs table for queue storage:
php artisan queue:table
php artisan migrate


Configure Queue ConnectionEnsure QUEUE_CONNECTION=database is set in .env.

Run the Queue WorkerStart the queue worker to process ticket assignments:
php artisan queue:work

Alternatively, use a process manager like Supervisor for production.


Implementation Details
Observer

Location: app/Observers/TicketObserver.php
Purpose: Observes the Ticket model and triggers the TicketCreated event when a new ticket is created.
How It Works: The created method dispatches the TicketCreated event, which initiates the ticket assignment process.
Registration: Registered in app/Providers/EventServiceProvider.php via Ticket::observe(TicketObserver::class) in the boot method.

Event

Location: app/Events/TicketCreated.php
Purpose: Represents the event of a new ticket being created, carrying the Ticket instance.
How It Works: Triggered by the TicketObserver and passes the ticket to the listener for further processing.

Listener

Location: app/Listeners/AssignTicketListener.php
Purpose: Listens for the TicketCreated event and dispatches the AssignTicketJob to assign the ticket to a support agent.
How It Works: Implements ShouldQueue to run asynchronously, dispatching the job to the queue.
Registration: Mapped to TicketCreated in app/Providers/EventServiceProvider.php under the $listen array.

Job

Location: app/Jobs/AssignTicketJob.php
Purpose: Assigns a ticket to the support agent with the fewest open tickets.
How It Works: Queries users with role = 'support_agent', counts open tickets, and assigns the ticket to the agent with the lowest count. Updates the ticket’s assigned_to and status fields.
Execution: Processed by the queue worker (php artisan queue:work).

Raw SQL

Location: app/Http/Controllers/TicketController.php in the topSupportAgents method
Purpose: Generates a report of the top 3 support agents based on the number of tickets assigned to them in the last 7 days.
Query:SELECT u.name, COUNT(t.id) as ticket_count
FROM users u
LEFT JOIN tickets t ON u.id = t.assigned_to
WHERE u.role = 'support_agent'
AND t.created_at >= NOW() - INTERVAL 7 DAY
GROUP BY u.id, u.name
ORDER BY ticket_count DESC
LIMIT 3


How It Works: Uses a raw SQL query via DB::select to join users and tickets, filter by support_agent role, and aggregate ticket counts.

Usage

Submit a Ticket: Visit http://127.0.0.1:8000/index or http://127.0.0.1:8000/tickets, fill out the form, and submit. The ticket is saved, and the queue assigns it to a support agent.
View Tickets: The table displays tickets created by the simulated user (ID 1).
Top Support Agents: Access http://127.0.0.1:8000/top-support-agents to view a JSON report of the top 3 support agents.

Notes

The application uses a simulated user ID (1) for testing. In a production environment, integrate Laravel’s authentication to use the authenticated user’s ID.
Ensure the queue worker is running for ticket assignments.
The design uses Bootstrap 5 for a responsive, modern UI.
