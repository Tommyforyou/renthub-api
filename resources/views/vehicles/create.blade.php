<!DOCTYPE html>
<html>
<head>
    <title>Add Vehicle</title>

    <style>
        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        .card{
            max-width:700px;
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

    <h1>Add Vehicle</h1>

   <form method="POST"
      action="{{ route('vehicles.store') }}"
      enctype="multipart/form-data">
        @csrf

        <label>Vehicle Title</label>
        <input type="text" name="title">

        <label>Brand</label>
        <input type="text" name="brand">

        <label>Model</label>
        <input type="text" name="model">

        <label>Year</label>
        <input type="number" name="year">

        <label>Transmission</label>
        <input type="text" name="transmission">

        <label>Fuel Type</label>
        <input type="text" name="fuel_type">

        <label>Seats</label>
        <input type="number" name="seats">

        <label>Price Per Day</label>
        <input type="number" step="0.01" name="price_per_day">

        <label>Vehicle Image</label>
        <input type="file" name="image">

        <label>Description</label>
        <textarea name="description"></textarea>

        <button type="submit">
            Add Vehicle
        </button>

    </form>

</div>

</body>
</html>