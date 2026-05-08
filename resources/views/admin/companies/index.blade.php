<!DOCTYPE html>
<html>
<head>
    <title>Company Approvals</title>

    <style>
        body{
            font-family:Arial;
            background:#f5f5f5;
            padding:40px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
        }

        th, td{
            padding:15px;
            border-bottom:1px solid #ddd;
        }

        .approve{
            background:green;
            color:white;
            border:none;
            padding:8px 12px;
        }

        .reject{
            background:red;
            color:white;
            border:none;
            padding:8px 12px;
        }
    </style>
</head>
<body>

<h1>Rental Companies</h1>

<table>

    <tr>
        <th>ID</th>
        <th>Company</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    @foreach($companies as $company)

    <tr>
        <td>{{ $company->id }}</td>
        <td>{{ $company->company_name }}</td>
        <td>{{ $company->phone }}</td>
        <td>{{ $company->status }}</td>

        <td>

            <form method="POST"
                  action="{{ route('admin.companies.approve', $company->id) }}"
                  style="display:inline;">
                @csrf

                <button class="approve">
                    Approve
                </button>
            </form>

            <form method="POST"
                  action="{{ route('admin.companies.reject', $company->id) }}"
                  style="display:inline;">
                @csrf

                <button class="reject">
                    Reject
                </button>
            </form>

        </td>
    </tr>

    @endforeach

</table>

</body>
</html>