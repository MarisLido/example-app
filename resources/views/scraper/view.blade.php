<x-app-layout class="py-18">
<div id="content" style="display:none;" class="mx-auto mb-6 space-y-6 max-w-7xl sm:px-6 lg:px-8">
    <h1 style='text-align:center' class="mt-6 text-xl font-semibold text-gray-900" >Scraped Data</h1>
    <div class="button-container">
        <form action="{{ route('scraper.scrape') }}" method="post">
            @csrf
            <button type="submit" class="px-4 py-2 font-semibold text-gray-800 bg-white border border-gray-400 rounded shadow hover:bg-gray-100">Scrape Data</button>
        </form>
        <form action="{{ route('scraper.update') }}" method="post">
            @csrf
            <button type="submit" class="px-4 py-2 font-semibold text-gray-800 bg-white border border-gray-400 rounded shadow hover:bg-gray-100">Update Data</button>
        </form>
    </div>
    <table id="scraper-table" class="p-8 bg-white shadow table-auto sm:p-8 sm:rounded-lg">
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
                    <form action="{{ route('scraper.delete', $d->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 font-semibold text-red-700 bg-transparent border border-red-500 rounded-full hover:bg-red-500 hover:text-white hover:border-transparent">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready( function () {
            $('#scraper-table').DataTable({
                "pageLength": 25
            });
        });

        window.addEventListener('load', function() {
            var content = document.getElementById('content');
            content.style.display = 'block';
        });
    </script>
</div>
</x-app-layout>
