<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
<table>
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
                {{$d->name}}
            </td>
            <td>
                {{$d->grade}}
            </td>
            <td>
                {{$d->gender}}
            </td>
            <td>
                {{$d->city}}
            </td>
            <td>
               {{$d->phone}}
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>