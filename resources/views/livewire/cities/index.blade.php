<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Система
        @endslot
        @slot('title')
            Города
        @endslot
    @endcomponent

    <div class="row mb-2">
        <div class="col-md-6">
            <div class="mb-3">
                <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="addItem()"><i
                        class="mdi mdi-plus me-2"></i> Добавить</button>
            </div>
        </div>
    </div>

    @include('livewire.cities.create')
    @include('livewire.cities.delete')

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="uil uil-check me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    <div class="row" wire:loading.class="opacity-75">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 35px;">#</th>
                        <th>Наименование</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cities as $city)
                        <tr>
                            <th scope="row">{{ $city->id }}</th>
                            <td>{{ $city->name }}</td>
                            <td>
                                <a title="Редактировать" wire:click="editItem({{ $city->id }})"
                                    class="px-3 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                <a title="Удалить" wire:click="openDeleteModal({{ $city->id }})"
                                    class="px-3 text-danger"><i class="uil uil-trash font-size-18"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" align="center">Города отсутствуют</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@section('script')
    <script>
        window.addEventListener('open-create-modal', event => {
            $('#addItem').modal('show');
        });
        window.addEventListener('close-create-modal', event => {
            $('#addItem').modal('hide');
        });
        window.addEventListener('open-delete-modal', event => {
            $('#deleteConfirm').modal('show');
        });
        window.addEventListener('close-delete-modal', event => {
            $('#deleteConfirm').modal('hide');
        });
    </script>
@endsection