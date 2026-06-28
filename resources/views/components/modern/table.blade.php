@props([
'headers' => [],
'striped' => true,
'hover' => true,
'responsive' => true,
'wrapperClass' => '',
'tableClass' => ''
])

<div @class(['modern-table-wrapper' => true, 'table-responsive' => $responsive, $wrapperClass]) style="overflow-x: auto;">
    <table {{ $attributes->merge(['class' => 'table table-bordered align-middle mb-0' . ($striped ? ' table-striped' : '') . ($hover ? ' table-hover' : '') . ' ' . $tableClass]) }}>
        @if(!empty($headers))
        <thead>
            <tr>
                @foreach($headers as $header)
                <th class="modern-th">
                    {{ $header }}
                </th>
                @endforeach
            </tr>
        </thead>
        @endif
        <tbody class="modern-body">
            {{ $slot }}
        </tbody>
    </table>
</div>

<style>
    .modern-table-wrapper {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #edf2f9;
    }

    .modern-table-wrapper .table {
        margin-bottom: 0;
        border: none;
    }

    /* Standardized Branded Header with Lighter Shadowed Look */
    .modern-table-wrapper thead th.modern-th {
        background: linear-gradient(180deg, #f9fdfa 0%, #e8f5ed 100%) !important;
        color: var(--bs-primary) !important;
        font-size: 0.825rem;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        font-weight: 700;
        padding: 0.75rem 1rem;
        border: 1px solid #edf2f9 !important;
        border-bottom: 2px solid rgba(var(--bs-primary-rgb), 0.3) !important;
        vertical-align: middle;
        text-align: center;
        box-shadow: inset 0 1px 0 #ffffff;
    }

    .modern-table-wrapper .modern-body td {
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        border: 1px solid #edf2f9;
        color: #333;
        vertical-align: middle;
        text-align: center;
    }

    /* Enhanced Stripe styling */
    .modern-table-wrapper .table-striped>tbody>tr:nth-of-type(odd)>* {
        background-color: rgba(var(--bs-primary-rgb), 0.015);
        box-shadow: none;
    }

    .modern-table-wrapper .modern-body tr:hover td {
        background-color: rgba(var(--bs-primary-rgb), 0.05) !important;
        color: #000;
    }

    /* Ensure vertical alignment for action buttons */
    .modern-table-wrapper .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>