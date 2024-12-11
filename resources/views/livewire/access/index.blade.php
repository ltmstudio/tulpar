<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Пользователи
        @endslot
        @slot('title')
            Администраторы
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

    @include('livewire.access.create')
    @include('livewire.access.delete')

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
                        <th>Имя</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Статус</th>
                        <th>Создан</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                @switch($item->role)
                                    @case('USR')
                                        Пользователь
                                    @break

                                    @case('DRV')
                                        Водитель
                                    @break

                                    @case('MNG')
                                        Менеджер
                                    @break

                                    @case('ADM')
                                        Администратор
                                    @break

                                    @default
                                @endswitch
                            </td>
                            <td>
                                @if ($item->status == 1)
                                    <i class="uil-lock-open-alt fs-5 text-success"></i>
                                @endif
                                @if ($item->status == 0)
                                    <i class="uil-lock-alt fs-5 text-danger"></i>
                                @endif
                                {{-- {{ $item->status }} --}}
                            </td>
                            <td>{{ $item->created_at->translatedFormat('d M. Y') }}</td>
                            <td>
                                <a title="Редактировать" wire:click= "editItem({{ $item->id }})"
                                    class="px-3 text-primary"><i class="uil uil-pen font-size-18"></i></a>
                                @if ($item->id != Auth::user()->id)
                                    <a title="Удалить" wire:click= "openDeleteModal({{ $item->id }})"
                                        class="px-3 text-danger"><i class="uil uil-trash font-size-18"></i></a>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="11" align="center">Записи во пользователям отсутствуют</td>
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
