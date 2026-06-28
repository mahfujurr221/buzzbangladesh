@extends('backend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12 text-center no-print">
            <h3>Employee ID Cards</h3>
            <button onclick="window.print()" class="btn btn-primary">Print Cards</button>
        </div>
    </div>

    <!-- Print Area -->
    <div class="row">
        @foreach($employees as $emp)
        <div class="col-md-4 mb-4">
            <div class="card id-card border-2" style="max-width: 350px; margin: 0 auto; border: 1px solid #ddd;">
                <div class="card-body text-center p-4">
                    <!-- Header -->
                    <div class="mb-3">
                        <h5 class="fw-bold text-uppercase mb-0">Shunno International</h5>
                        <small class="text-muted">Employee ID Card</small>
                    </div>

                    <!-- Photo -->
                    <div class="mb-3">
                        @if($emp->image)
                            <img src="{{ asset('backend/images/employees/' . $emp->image) }}" alt="{{ $emp->user->fname }}" class="rounded-circle border border-2 border-primary shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                        @elseif($emp->user->image)
                            <img src="{{ asset('backend/images/users/' . $emp->user->image) }}" alt="{{ $emp->user->fname }}" class="rounded-circle border border-2 border-primary shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div style="width: 100px; height: 100px; background-color: #f0f0f0; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; color: #aaa;">
                                {{ substr($emp->user->fname, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Details -->
                    <h5 class="fw-bold mb-1">{{ $emp->user->fname }} {{ $emp->user->lname }}</h5>
                    <p class="text-primary fw-bold mb-1">{{ $emp->designation->name ?? 'N/A' }}</p>
                    <p class="small text-muted mb-3">{{ $emp->department->name ?? 'N/A' }}</p>

                    <!-- ID and QR -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div class="text-start">
                            <small class="d-block text-muted">ID Number</small>
                            <span class="fw-bold font-monospace">{{ $emp->employee_id }}</span>
                        </div>
                        <div class="qr-area">
                            {!! QrCode::size(70)->generate(route('frontend.member.show', $emp->employee_id)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        .id-card {
            break-inside: avoid;
            border: 1px solid #000 !important;
        }

        body {
            background: white;
        }
    }
</style>
@endsection