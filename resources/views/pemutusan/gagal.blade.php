@extends('layouts.app')
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (Session::has('message'))
                Swal.fire({
                    title: 'Berhasil',
                    text: '{{ Session::get('message') }}',
                    icon: 'success',
                    confirmButtonText: 'Ok'
                });
            @endif
            @if ($errors->any())
                var errorMessage = '';
                @foreach ($errors->all() as $error)
                    errorMessage += '{{ $error }}\n';
                @endforeach

                Swal.fire({
                    title: 'Error',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Ok'
                });
            @endif
        });
    </script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4" style="color: white"><span class="text-muted fw-light">Service /</span> Pemutusan
        </h4>
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                <li class="nav-item">
                    <a href="{{ route('route.pemutusans.index') }}" class="nav-link" role="tab" aria-selected="false"
                        style="color: white">
                        Proses
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('route.pemutusans-berhasil') }}" class="nav-link" role="tab" aria-selected="false"
                        style="color: white">
                        Berhasil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('route.pemutusans-gagal') }}" class="nav-link active" role="tab"
                        aria-selected="true" style="color: white">
                        Gagal
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="table-responsive text-nowrap">
                    <table id="myTable" class="table mb-4">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No Pelanggan</th>
                                <th>Nama Pelanggan</th>
                                <th>Alamat</th>
                                <th>Telepon</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach ($gagal as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->pelanggan->no_pelanggan }}</td>
                                    <td>{{ $item->pelanggan->nama }}</td>
                                    <td>{{ $item->pelanggan->alamat }}</td>
                                    <td>{{ $item->pelanggan->telepon }}</td>
                                    <td><span class="badge bg-danger">{{ $item->status_pemutusan }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
