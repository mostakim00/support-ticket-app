<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Ticketing System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 900px;
            margin-top: 2rem;
        }
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .alert {
            margin-bottom: 1rem;
        }
        .form-label {
            font-weight: 500;
        }
        .table {
            background-color: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table th {
            background-color: #e9ecef;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card p-4 mb-4">
        <h1 class="mb-4">Submit a Support Ticket</h1>
        <div id="alert-container"></div>

        <form id="ticket-form" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="col-md-6">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Submit Ticket</button>
            </div>
        </form>
    </div>

    <div class="card p-4">
        <h2 class="mb-4">Your Tickets</h2>
        <div class="table-responsive">
            <table class="table table-striped" id="tickets-table">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->subject }}</td>
                        <td>{{ $ticket->priority }}</td>
                        <td>{{ $ticket->status }}</td>
                        <td>{{ $ticket->created_at->format('Y-m-d H:i:s') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for alerts and responsiveness) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('#ticket-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("tickets.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#alert-container').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Ticket Submitted Successfully
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    $('#ticket-form')[0].reset();
                    $('#tickets-table tbody').prepend(`
                            <tr>
                                <td>${response.ticket.subject}</td>
                                <td>${response.ticket.priority}</td>
                                <td>${response.ticket.status}</td>
                                <td>${new Date(response.ticket.created_at).toLocaleString()}</td>
                            </tr>
                        `);
                    setTimeout(() => $('.alert').alert('close'), 3000);
                },
                error: function(xhr) {
                    $('#alert-container').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Error submitting ticket
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                }
            });
        });
    });
</script>
</body>
</html>
