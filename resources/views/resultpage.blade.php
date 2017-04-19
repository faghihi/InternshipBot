<html>
<head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <table class="table">
        <tr>
            <th>
                name
            </th>
            <th>
                grade
            </th>
            <th>
                gender
            </th>
            <th>
                city
            </th>
            <th>
                phone
            </th>
        </tr>
        @foreach($data as $d)
            <tr>
                <td>
                    {{$d['name']}}
                </td>
                <td>
                    {{$d['grade']}}
                </td>
                <td>
                    {{$d['gender']}}
                </td>
                <td>
                    {{$d['city']}}
                </td>
                <td>
                    {{$d['phone']}}
                </td>
            </tr>
        @endforeach
    </table>
</div>

<script type="text/javascript" src="/bootstrap/js/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="/bootstrap/js/bootstrap.js"></script>
</body>
</html>