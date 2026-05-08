<!DOCTYPE html>
<html>
<head>
    <title>Register Rental Company</title>

    <style>
        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        .card{
            max-width:600px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:12px;
        }

        input, textarea{
            width:100%;
            padding:12px;
            margin-top:10px;
            margin-bottom:20px;
        }

        button{
            background:black;
            color:white;
            padding:12px 20px;
            border:none;
            border-radius:8px;
        }
    </style>
</head>
<body>

<div class="card">

    <h1>Rental Company Registration</h1>

    <form method="POST" action="{{ route('company.store') }}">
        @csrf

        <label>Company Name</label>
        <input type="text" name="company_name">

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Email</label>
        <input type="email" name="email">

        <label>Address</label>
        <textarea name="address"></textarea>

        <button type="submit">
            Submit Registration
        </button>
    </form>

</div>

</body>
</html>