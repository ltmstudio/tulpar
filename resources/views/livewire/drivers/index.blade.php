<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Профили
        @endslot
        @slot('title')
            Водители
        @endslot
    @endcomponent


    <div class="d-flex justify-content-between">
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
    </div>

    @include('livewire.drivers.create')
    @include('livewire.drivers.delete')
    @include('livewire.drivers.balance')

    <style>
        .driver-avatar {
            border-radius: 10px;
            box-shadow: 0 0 5px #dedede;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .driver-avatar-sm {
            border-radius: 10px;
            box-shadow: 0 0 5px #dedede;
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>

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
                        <th style="width: 35px;">#</th>
                        <th style="width: 65px;">Фото</th>
                        <th>Имя</th>
                        <th>Номер телефона</th>
                        <th>Автомобиль</th>
                        <th>Номер авто</th>
                        <th>Фото авто</th>
                        <th>Класс</th>
                        <th>Курьер</th>
                        <th>Груз.</th>
                        <th>Баланс</th>
                        <th>Статус</th>
                        <th>Дата регистрации</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>
                                @if ($item->avatar)
                                    <img src="{{ asset('storage/' . $item->avatar) }}"
                                        alt="" class="driver-avatar-sm mb-3">
                                @endif
                            </td>
                            <td>{{ $item->name }} {{ $item->lastname }}</td>
                            <td>+7 {{ $item->phone }}</td>
                            <td>{{ $item->car_name }}</td>
                            <td>{{ $item->car_number }}</td>
                            <td>
                                @if (empty($item->car_images))
                                    <span><i class="text-danger uil-times-circle"></i> Нет фото</span>
                                @else
                                    <span><i class="text-success uil-check-circle"></i>
                                        {{ count($item->car_images) }} фото</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->class)
                                    {{ $item->class->name }}
                                @else
                                    --
                                @endif
                            </td>
                            <td>
                                @if ($item->delivery == 1)
                                    <i class="uil uil-check-circle fs-6" style="color: rgb(0, 209, 0)"></i>
                                @else
                                    <i class="uil uil-times-circle fs-6" style="color: rgb(218, 37, 6)"></i>
                                @endif
                                {{-- {{ $item->status }} --}}
                            </td>
                            <td>
                                @if ($item->cargo == 1)
                                    <i class="uil uil-check-circle fs-6" style="color: rgb(0, 209, 0)"></i>
                                @else
                                    <i class="uil uil-times-circle fs-6" style="color: rgb(218, 37, 6)"></i>
                                @endif
                                {{-- {{ $item->status }} --}}
                            </td>

                            <td>{{ $item->balance }} ₸</td>
                            <td>
                                @if ($item->status == 1)
                                    <i class="uil uil-check-circle fs-6" style="color: rgb(0, 209, 0)"></i>
                                @endif
                                @if ($item->status == 0)
                                    <i class="uil uil-times-circle fs-6" style="color: rgb(218, 37, 6)"></i>
                                @endif
                                {{-- {{ $item->status }} --}}
                            </td>
                            <td>{{ $item->user->created_at->translatedFormat('d M. Y') }}</td>
                            <td>
                                <a title="Редактировать" wire:click= "editItem({{ $item->id }})"
                                    class="px-3 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                <a title="Баланс водителя" wire:click= "editBalance({{ $item->id }})"
                                    class="px-3 text-primary"><i class="uil uil-dollar-alt font-size-18"></i></a>
                                <a title="Удалить" wire:click= "openDeleteModal({{ $item->id }})"
                                    class="px-3 text-danger"><i class="uil uil-trash font-size-18"></i></a>
                                {{-- <a wire:click = "openDeleteModal({{ $item->id }})" class="px-3 text-danger"><i
                                        class="uil uil-trash-alt font-size-18"></i></a> --}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" align="center">Записи во водителям отсутствуют</td>
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
        window.addEventListener('open-balance-modal', event => {
            $('#balanceModal').modal('show');
        });
        window.addEventListener('close-balance-modal', event => {
            $('#balanceModal').modal('hide');
        });
        window.addEventListener('open-delete-modal', event => {
            $('#deleteConfirm').modal('show');
        });
        window.addEventListener('close-delete-modal', event => {
            $('#deleteConfirm').modal('hide');
        });
    </script>
@endsection
