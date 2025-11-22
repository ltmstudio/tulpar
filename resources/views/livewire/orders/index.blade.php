<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Меню
        @endslot
        @slot('title')
            Заказы
        @endslot
    @endcomponent


    {{-- <div class="d-flex justify-content-between">
        <div class="mb-3">

        </div>
        <div class="row row-cols-lg-auto gx-3 gy-2 align-items-center mb-3">
            <div class="col-12">
                <label class="visually-hidden" for="search">Введите название</label>
                <div class="input-group">
                    <div class="input-group-text"><i class="uil-search"></i></div>
                    <input type="text" class="form-control" id="search" wire:model="search">
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-secondary waves-effect waves-light w-md"
                        wire:click="setSearch">Применить</button>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-light waves-effect waves-light w-md"
                        wire:click="resetSearch">Сбросить</button>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- @include('livewire.drivers.create') --}}
    {{-- @include('livewire.drivers.delete') --}}

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="uil uil-check me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif


    <div class="row" wire:loading.class = "opacity-75">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Телефон</th>
                        <th>Тип</th>
                        <th>Назначение</th>
                        <th>Класс</th>
                        <th>Указанная стоимость</th>
                        <th>Водитель</th>
                        <th>Дата создания</th>
                        {{-- <th>Действия</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>+7 {{ $item->phone }}</td>
                            <td>{{ optional($item->type)->name ?? '--' }}</td>
                            <td>
                                @if ($item->type_id == 1)
                                    {{ $item->point_a ?? '--' }}
                                    @if ($item->geo_a)
                                        <i class="uil uil-map-marker-alt fs-6" style="color: rgb(0, 209, 0)"></i>
                                    @endif
                                    <i class="uil uil-arrow-right fs-6 text-primary"></i>
                                    {{ $item->point_b ?? '--' }}
                                    @if ($item->geo_b)
                                    <i class="uil uil-map-marker-alt fs-6" style="color: rgb(0, 209, 0)"></i>
                                    @endif
                                    @else
                                    {{ optional($item->cityA)->name ?? '--' }}
                                    <i class="uil uil-arrow-right fs-6 text-primary"></i>
                                    {{ optional($item->cityB)->name ?? '--' }}
                                @endif
                            </td>
                            <td>{{ optional($item->class)->name ?? '--' }}</td>
                            <td>{{ $item->user_cost }}</td>
                            <td>{{ optional($item->driver)->name ?? '--' }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            {{-- <td>
                                <a title="Edit" wire:click="editItem({{ $item->id }})"
                                    class="px-3 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                <a title="Delete" wire:click="openDeleteModal({{ $item->id }})"
                                    class="px-3 text-danger"><i class="uil uil-trash font-size-18"></i></a>
                            </td> --}}
                        </tr>
                    @empty
                        <tr>
                            <td colspan="18" align="center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{ $items->links() }}
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
