@if ($flash = session('success'))
    <div id="flash" class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
        <span class="block sm:inline">{{$flash}}</span>
    </div>
@endif