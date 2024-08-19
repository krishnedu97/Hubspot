<!DOCTYPE html>
<html>
<head>
    <title>Contact Details</title>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 18px;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
<body>
    
    <h1>Contact Details</h1>
    <ul>
        <li>First Name: {{ $contact->first_name }}</li>
        <li>Last Name: {{ $contact->last_name }}</li>
        <li>Email: {{ $contact->email }}</li>
    </ul>
    <a href="{{ route('contacts.index') }}">Back to List</a>
</body>
</html>
