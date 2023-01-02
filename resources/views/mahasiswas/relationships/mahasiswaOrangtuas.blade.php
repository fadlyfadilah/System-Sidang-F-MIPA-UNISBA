<div class="content">
    @can('orangtua_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.orangtuas.create') }}">
                    Tambah Data Orang Tua
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Daftar Data Orang Tua
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Orangtua">
                            <thead>
                                <tr>
                                    <th width="10">

                                    </th>
                                    <th>
                                        Mahasiswa
                                    </th>
                                    <th>
                                        Nama Ibu
                                    </th>
                                    <th>
                                        Pekerjaan Ibu
                                    </th>
                                    <th>
                                        Nama Ayah
                                    </th>
                                    <th>
                                        Pekerjaan Ayah
                                    </th>
                                    <th>
                                        Alamat
                                    </th>
                                    <th>
                                        No Handphone
                                    </th>
                                    <th>
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orangtuas as $key => $orangtua)
                                    <tr data-entry-id="{{ $orangtua->id }}">
                                        <td>

                                        </td>
                                        <td>
                                            {{ $orangtua->mahasiswa->user->name ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orangtua->nama_ibu ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Orangtua::PEKERJAAN_IBU_SELECT[$orangtua->pekerjaan_ibu] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orangtua->nama_ayah ?? '' }}
                                        </td>
                                        <td>
                                            {{ App\Models\Orangtua::PEKERJAAN_AYAH_SELECT[$orangtua->pekerjaan_ayah] ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orangtua->alamat ?? '' }}
                                        </td>
                                        <td>
                                            {{ $orangtua->no_hp ?? '' }}
                                        </td>
                                        <td>
                                            @can('orangtua_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.orangtuas.show', $orangtua->id) }}">
                                                    View
                                                </a>
                                            @endcan

                                            @can('orangtua_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.orangtuas.edit', $orangtua->id) }}">
                                                    Edit
                                                </a>
                                            @endcan

                                            @can('orangtua_delete')
                                                <form action="{{ route('admin.orangtuas.destroy', $orangtua->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                                </form>
                                            @endcan

                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('orangtua_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.orangtuas.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).nodes(), function(entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            $.extend(true, $.fn.dataTable.defaults, {
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            });
            let table = $('.datatable-mahasiswaOrangtuas:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        })
    </script>
@endsection
