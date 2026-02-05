{{-- Pagination Controls Component --}}
@props(['items', 'exportRoute'])

<div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 px-6 pb-6">
    {{-- Per Page Selector --}}
    <div class="flex items-center gap-3">
        <label class="text-sm font-semibold text-gray-600">Show:</label>
        <select onchange="window.location.href = updateQueryParam('per_page', this.value)"
            class="px-4 py-2 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>
        <span class="text-sm text-gray-600">
            Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} entries
        </span>
    </div>

    {{-- Export Button --}}
    <a href="{{ route($exportRoute, request()->except('page')) }}"
        class="flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export Filtered Data
    </a>
</div>

{{-- Pagination Links --}}
<div class="px-6 pb-6">
    {{ $items->links() }}
</div>

<script>
    function updateQueryParam(key, value) {
        const url = new URL(window.location.href);
        url.searchParams.set(key, value);
        url.searchParams.delete('page'); // Reset to first page when changing per_page
        return url.toString();
    }
</script>