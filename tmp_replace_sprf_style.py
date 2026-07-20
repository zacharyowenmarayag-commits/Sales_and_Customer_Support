from pathlib import Path
path = Path('resources/views/SPRF/index.blade.php')
text = path.read_text(encoding='utf-8')
start = text.index("@push('styles')")
end = text.index('</style>\r\n@endpush', start)
if end == -1:
    end = text.index('</style>\n@endpush', start)
else:
    end += len('</style>\r\n@endpush')
new = "@push('styles')\n    @vite(['resources/css/pages/sprf-dashboard.css'])\n@endpush"
path.write_text(text[:start] + new + text[end:], encoding='utf-8')
print('updated')
