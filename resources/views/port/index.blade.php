@extends('layouts.app')
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
@push('styles')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
@endpush


@section('content')
    <div class="content">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4" style="color: white"><span class="text-muted fw-light">Data Master /</span> ODP Port
            </h4>
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary float-end mb-3" data-bs-toggle="modal"
                        data-bs-target="#add">Tambah</button>
                    <div class="table-responsive text-nowrap">
                        {{-- <table id="myTable" class="table mb-4">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Slot</th>
                                    <th>Port</th>
                                    <th>Index Inc</th>
                                    <th>No Pelanggan</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($port as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <button data-bs-toggle="modal"
                                                        data-bs-target="#update{{ $item->id }}" class="dropdown-item"><i
                                                            class="bx bx-edit-alt me-1"></i>
                                                        Edit</button>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#delete{{ $item->id }}"><i
                                                            class="bx bx-trash me-1"></i>
                                                        Delete</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->slot }}</td>
                                        <td>{{ $item->port }}</td>
                                        <td>{{ $item->index_inc }}</td>
                                        <td>{{ optional($item->pelanggan)->no_pelanggan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table> --}}

                        <div id="jsGridTable"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal tambah ges --}}
    <div class="modal fade" id="add" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Tambahkan Data OLT PORT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTambah" method="POST" action="{{ route('route.port.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="slot" class="form-label">Pilih Slot</label>
                            <select id="slot" class="form-select" name="slot" required>
                                <option value="" selected>Pilih Slot</option>
                                <option value="1">
                                    1
                                </option>
                                <option value="2">
                                    2
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="port" class="form-label">Pilih Port</label>
                            <select id="port" class="form-select" name="port" required>
                                <option value="" selected>Pilih Port</option>
                                @for ($i = 1; $i <= 16; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="index_inc" class="form-label">Index Inc</label>
                            <input class="form-control" type="text" id="index_inc" name="index_inc" maxlength="3"
                                required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal edit ges --}}
    @foreach ($port as $item)
        <div class="modal fade" id="update{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Data OLT PORT</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('route.port.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="slot" class="form-label">Pilih Slot</label>
                                <select id="slot" class="form-select" name="slot" required>
                                    <option value="" selected>Pilih Slot</option>
                                    <option value="1" {{ $item->slot == 1 ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ $item->slot == 2 ? 'selected' : '' }}>2</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="port" class="form-label">Pilih Port</label>
                                <select id="port" class="form-select" name="port" required>
                                    <option value="" selected>Pilih Port</option>
                                    @for ($i = 1; $i <= 16; $i++)
                                        <option value="{{ $i }}" {{ $item->port == $i ? 'selected' : '' }}>
                                            {{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="index_inc" class="form-label">Index Inc</label>
                                <input class="form-control" type="text" id="index_inc" name="index_inc"
                                    maxlength="3" required value="{{ $item->index_inc }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach



    {{-- modal hapus ges --}}
    @foreach ($port as $item)
        <div class="modal fade" id="delete{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ route('route.port.destroy', $item->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Apakah Anda Yakin Ingin Menghapus data?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        $("#jsGridTable").jsGrid({
            width: "100%",
            height: "400px",

            filtering: true,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            controller: {
                loadData: function(filter) {
                    return $.ajax({
                        type: "GET",
                        url: "/api/ports-get",
                        data: filter,
                        dataType: "json"
                    }).then(function(result) {
                        result = result.filter(function(item) {
                            return (!filter.No || item.No.toString().indexOf(filter
                                        .No) !== -
                                    1) &&
                                (!filter.Slot || item.Slot.toString().indexOf(filter
                                        .Slot) !== -
                                    1) &&
                                (!filter.Port || item.Port.toString().indexOf(filter
                                        .Port) !== -
                                    1) &&
                                (!filter.IndexInc || item.IndexInc.toString()
                                    .indexOf(filter
                                        .IndexInc) !== -
                                    1) &&
                                (!filter.NoPelanggan || item.NoPelanggan.toString()
                                    .indexOf(
                                        filter.NoPelanggan) !== -
                                    1);

                        });

                        return result;
                    });
                }
            },

            // data: ports,
            fields: [{
                    name: "No",
                    type: "number",
                    width: 20,
                    align: "center",
                },
                {
                    name: "Actions",
                    type: "text",
                    width: 25,
                    align: "center",
                    filtering: false,
                    itemTemplate: function(value, item) {
                        return $("<div>").addClass("dropdown")
                            .append($("<button>").addClass("btn p-0 dropdown-toggle hide-arrow")
                                .attr(
                                    "type", "button").attr("data-bs-toggle", "dropdown")
                                .append($("<i>").addClass("bx bx-dots-vertical-rounded")))
                            .append($("<div>").addClass("dropdown-menu")
                                .append($("<button>").addClass("dropdown-item").attr(
                                        "data-bs-toggle",
                                        "modal").attr("data-bs-target", "#update" + item.id)
                                    .append($("<i>").addClass("bx bx-edit-alt me-1")).append(
                                        "Edit"))
                                .append($("<button>").addClass("dropdown-item").attr(
                                        "data-bs-toggle",
                                        "modal").attr("data-bs-target", "#delete" + item.id)
                                    .append($("<i>").addClass("bx bx-trash me-1")).append(
                                        "Delete"))
                            );
                    }
                },
                {
                    name: "Slot",
                    type: "number",
                    width: 50,
                    align: "center",

                },
                {
                    name: "Port",
                    type: "number",
                    width: 50,
                    align: "center",

                },
                {
                    name: "IndexInc",
                    type: "number",
                    width: 50,
                    align: "center",

                },
                {
                    name: "NoPelanggan",
                    type: "text",
                    width: 50,
                    align: "center",
                    sorting: false,
                },
            ]
        });
    </script>
@endsection
