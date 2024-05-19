<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./admin/login.php");
    exit;
}
?>

<?php
require 'csrf_token.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LCA SMS Portal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 50%;
        }
        .form-container {
            width: 100%;
        }
        h1 {
            font-size: 1.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"],
        input[type="tel"],
        textarea {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
        }
        textarea {
            resize: vertical;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
            font-size: 1em;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        .form-toggle {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <div class="d-flex justify-content-between">
                <h1>Send SMS</h1>
                <a href="./admin/logout.php" class="btn btn-danger">Logout</a>
            </div>
            <div class="form-toggle">
                <label for="toggle-mode">Toggle Mode: </label>
                <input type="checkbox" id="toggle-mode">
            </div>
            <form id="manual-sms-form" enctype="multipart/form-data">
                <div id="single-sms">
                    <label for="number">Phone Number (with country code):</label>
                    <input type="tel" id="number" name="number"><br><br>
                </div>
                <div id="bulk-sms" style="display: none;">
                    <label for="number">Phone Numbers (comma-separated):</label>
                    <textarea id="number" name="number" rows="3"></textarea><br><br>
                    <label for="csv-file">Upload CSV File:</label>
                    <input type="file" id="csv-file" name="csv-file" accept=".csv"><br><br>
                </div>
                <label for="name">Sender ID:</label>
                <input type="text" id="name" name="name" required><br><br>
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea><br><br>
                <input type="hidden" name="csrf_token" value="<?php echo get_csrf_token(); ?>">
                <input type="submit" value="Send SMS">
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responseModalLabel">Response</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-message"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('toggle-mode').addEventListener('change', function() {
            const singleSMS = document.getElementById('single-sms');
            const bulkSMS = document.getElementById('bulk-sms');
            if (this.checked) {
                singleSMS.style.display = 'none';
                bulkSMS.style.display = 'block';
            } else {
                singleSMS.style.display = 'block';
                bulkSMS.style.display = 'none';
            }
        });

        document.getElementById('manual-sms-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('send_manual_sms.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                let message = '';
                data.forEach(result => {
                    message += result.message + '\n';
                });
                document.getElementById('modal-message').innerText = message;
                $('#responseModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('modal-message').innerText = 'An error occurred: ' + error.message;
                $('#responseModal').modal('show');
            });
        });
    </script>
</body>
</html>
