<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Система
        @endslot
        @slot('title')
            Модерация водителей
        @endslot
    @endcomponent

    <style>
        .modal-backdrop.show {
            z-index: 1040;
        }

        .addItem .modal-backdrop.show {
            z-index: 1040;
        }
        
        #addItem {
            z-index: 1041;
        }

        .imageModal .modal-backdrop.show {
            z-index: 1050;
        }

        #imageModal {
            z-index: 1051;
        }
    </style>
    {{-- 
    <div class="row mb-2">
        <div class="col-md-6">
            <div class="mb-3">
                <button type="button" class="btn btn-primary waves-effect waves-light" wire:click="addItem()"><i
                        class="mdi mdi-plus me-2"></i> Добавить</button>
            </div>
        </div>
    </div> --}}

    @include('livewire.moderation.create')


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
                        <th>ФИО / дата рождения</th>
                        <th>Авто</th>
                        <th>VIN авто</th>
                        <th>Гос номер авто</th>
                        <th>Фото авто</th>
                        <th>ВУ</th>
                        <th>ПТС</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $moderation)
                        <tr>
                            <th scope="row">{{ $moderation->id }}</th>
                            <td>{{ $moderation->name }} {{ $moderation->lastname }}
                                @if ($moderation->birthdate)
                                    / {{ \Carbon\Carbon::parse($moderation->birthdate)->format('d.m.Y') }}
                                @else
                                    Дата рождения не указана
                                @endif
                            </td>
                            <td>
                                @if ($moderation->car_id)
                                    {{ optional($moderation->car)->name ?? '--' }}
                                @else
                                    --
                                @endif
                                @if ($moderation->car_model_id)
                                    {{ $moderation->carModel->name ?? '--' }}
                                @else
                                    --
                                @endif
                                @if ($moderation->car_year)
                                    {{ $moderation->car_year }} г
                                @endif
                            </td>
                            <td>
                                @if ($moderation->car_vin)
                                    {{ $moderation->car_vin }}
                                @else
                                    <span><i class="text-danger uil-times-circle"></i> VIN не указан </span>
                                @endif
                            </td>
                            <td>
                                @if ($moderation->car_gos_number)
                                    {{ $moderation->car_gos_number }}
                                @else
                                    <span><i class="text-danger uil-times-circle"></i> Гос номер не указан </span>
                                @endif
                            </td>
                            <td>
                                @if (empty($moderation->car_images))
                                    <span><i class="text-danger uil-times-circle"></i> Нет фото</span>
                                @else
                                    <span><i class="text-success uil-check-circle"></i>
                                        {{ count($moderation->car_images) }} фото</span>
                                @endif
                            </td>
                            <td>
                                {{-- №{{ $moderation->driver_license_number ?? '---' }} <br>
                                @if ($moderation->driver_license_date)
                                    {{ \Carbon\Carbon::parse($moderation->driver_license_date)->format('d M Y') }} <br>
                                @else
                                    Дата ВУ не указана <br>
                                @endif --}}
                                @if (empty($moderation->driver_license_images))
                                    <span><i class="text-danger uil-times-circle"></i> Нет фото</span>
                                @else
                                    <span><i class="text-success uil-check-circle"></i>
                                        {{ count($moderation->driver_license_images) }} фото</span>
                                @endif
                            </td>

                            <td>
                                @if (empty($moderation->ts_passport_images))
                                    <span><i class="text-danger uil-times-circle"></i> Нет фото</span>
                                @else
                                    <span><i class="text-success uil-check-circle"></i>
                                        {{ count($moderation->ts_passport_images) }} фото</span>
                                @endif

                            </td>
                            <td>
                                {{-- preparation, moderation, approved, rejected --}}
                                @switch($moderation->status)
                                    @case('preparation')
                                        <span class="badge bg-info">Подготовка</span>
                                    @break

                                    @case('moderation')
                                        <span class="badge bg-warning">Модерация</span>
                                    @break

                                    @case('approved')
                                        <span class="badge bg-success">Одобрено</span>
                                    @break

                                    @case('rejected')
                                        <span class="badge bg-danger">Отклонено</span>
                                    @break

                                    @default
                                        <span class="badge bg-secondary">{{ $moderation->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <a title="Редактировать" wire:click="editItem({{ $moderation->id }})"
                                    class="px-3 text-primary"><i class="uil uil-eye font-size-18"></i></a>
                                <a title="Удалить" wire:click="openDeleteModal({{ $moderation->id }})"
                                    class="px-3 text-danger"><i class="uil uil-trash font-size-18"></i></a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="21" align="center">Модерации отсутствуют</td>
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

            function showImageModal(imageUrl) {
                var modalImage = document.getElementById('modalImage');
                modalImage.src = imageUrl;
                var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                imageModal.show();
                setTimeout(() => {
                    var backdrop = document.querySelector('.modal-backdrop.fade.show:last-of-type');
                    if (backdrop) {
                        backdrop.style.zIndex = 1050;
                    }
                }, 50);
            }
        </script>
    @endsection
