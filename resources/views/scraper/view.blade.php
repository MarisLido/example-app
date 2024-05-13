<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraper Data</title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <style>
        .button-container {
            white-space: nowrap;
            text-align: center;
        }

        .button-container form {
            display: inline-block;
        }

        .button-container form button {
            margin-right: 10px; /* Add some spacing between buttons */
        }

    </style>
</head>
<body id="content" style="display:none;">
    <h1 style='text-align:center' >Scraper Data</h1>
    <!-- Button to trigger the scraping function -->
    <div class="button-container">
        <form action="{{ route('scraper.scrape') }}" method="post">
            @csrf
            <button type="submit">Scrape Data</button>
        </form>
        <form action="{{ route('scraper.update') }}" method="post">
            @csrf
            <button type="submit">Update Data</button>
        </form>
    </div>
    <table id="scraper-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Link</th>
                <th>Score</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $d)
            <tr>
                <td>{{ $d->title }}</td>
                <td><a href="{{ $d->link }}" target="_blank">{{ $d->link }}</a></td>
                <td>{{ $d->score }}</td>
                <td>{{ $d->date }}</td>
                <td>
                    <!-- Delete form for each row -->
                    <form action="{{ route('scraper.delete', $d->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready( function () {
            $('#scraper-table').DataTable({
                "pageLength": 25 // Show 10 entries per page
            });
        });


        window.addEventListener('load', function() {
            var content = document.getElementById('content');
            content.style.display = 'block';
        });


    </script>
</body>
</html>
