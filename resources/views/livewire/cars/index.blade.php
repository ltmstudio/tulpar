<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Система
        @endslot
        @slot('title')
            Автомобили
        @endslot
    @endcomponent

    <div class="d-flex justify-content-between">
        <div class="mb-3">
            <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="addItem()"><i
                    class="mdi mdi-plus me-2"></i> Добавить</button>
        </div>
        <div class="row row-cols-lg-auto gx-3 gy-2 align-items-center">
            <div class="col-12">
                <label class="visually-hidden" for="search_popular">Популярный</label>
                <select class="form-select" id="search_popular" wire:model="search_popular">
                    <option value="-1">Все</option>
                    <option value="1">Только популяные</option>
                    <option value="0">Не популярные</option>
                </select>
            </div>
            <div class="col-12">
                <label class="visually-hidden" for="search">Введите название</label>
                <div class="input-group">
                    <div class="input-group-text"><i class="uil-search"></i></div>
                    <input type="text" class="form-control" id="search"  wire:model="search">
                </div>
            </div>
            
            <div class="col-12">
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-secondary waves-effect waves-light w-md" wire:click="setSearch">Применить</button>
                </div>
            </div>
            <div class="col-12">
                <div class="d-flex flex-wrap gap-3">
                    <button type="button" class="btn btn-light waves-effect waves-light w-md" wire:click="resetSearch">Сбросить</button>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.cars.create')
    @include('livewire.cars.delete')

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
                        <th>Название</th>
                        <th>Русское название</th>
                        <th>Популярный</th>
                        <th>Страна</th>
                        <th>Изображение</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cars as $car)
                        <tr>
                            <th scope="row">{{ $car->id }}</th>
                            <td>{{ $car->name }}</td>
                            <td>{{ $car->{'cyrillic-name'} }}</td>
                            <td>{{ $car->popular ? 'Да' : 'Нет' }}</td>
                            <td>{{ $car->country }}</td>
                            <td>
                                @if ($car->image)
                                    <img src="{{ $car->image }}" alt="{{ $car->name }}" width="50">
                                @else
                                    -
                                @endif
                            <td>
                                <a title="Редактировать" wire:click="editItem('{{ $car->id }}')"
                                    class="px-3 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                <a title="Удалить" wire:click="openDeleteModal('{{ $car->id }}')"
                                    class="px-3 text-danger"><i class="uil uil-trash font-size-18"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" align="center">Автомобили отсутствуют</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
</div>
