<form wire:submit.prevent="rejectItem" class="custom-validation">
    <div class="modal fade createItem" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($item_edit_id != '')
                            Просмотр данных для модерации
                        @else
                            Данные не найдены
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeCreateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label class="form-label" for="name">Имя</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="name" wire:model="name" readonly
                                placeholder="Не указано">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="lastname">Фамилия</label>
                            @error('lastname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="lastname" wire:model="lastname" readonly
                                placeholder="Не указано">

                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="birthdate">Дата рождения</label>
                            @error('birthdate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="birthdate" wire:model="birthdate" readonly
                                placeholder="Не указано">

                        </div>
                    </div>
                    <h5 class="font-size-14 mt-4 mb-3"><i class="uil-circle text-primary me-1"></i> Авто
                    </h5>
                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label class="form-label" for="car_name">Марка авто</label>
                            @error('car_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="car_name" wire:model="car_name" readonly
                                placeholder="Не указано">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="car_model_name">Модель</label>
                            @error('car_model_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="car_model_name" wire:model="car_model_name"
                                readonly placeholder="Не указано">

                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="car_year">Год</label>
                            @error('car_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="car_year" wire:model="car_year" readonly
                                placeholder="Не указано">

                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="car_gos_number">Гос номер</label>
                            @error('car_gos_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="car_gos_number" wire:model="car_gos_number"
                                readonly placeholder="Не указано">

                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="form-label" for="car_vin">VIN</label>
                            @error('car_vin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="car_vin" wire:model="car_vin" readonly
                                placeholder="Не указано">

                        </div>
                    </div>
                    <div class="row">
                        @foreach ($car_images as $car_image)
                            <div class="col-lg-3">
                                <img src="{{ url('/file?path=' . $car_image) }}" alt=""
                                    class="img-fluid rounded"
                                    onclick="showImageModal('{{ url('/file?path=' . $car_image) }}')">
                            </div>
                        @endforeach
                    </div>
                    <h5 class="font-size-14 mt-4 mb-3"><i class="uil-circle text-primary me-1"></i>Водительское
                        удостоверение</h5>
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="driver_license_number">Номер</label>
                            @error('driver_license_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="driver_license_number"
                                wire:model="driver_license_number" readonly placeholder="Не указано">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="driver_license_date">Дата выдачи</label>
                            @error('driver_license_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="driver_license_date"
                                wire:model="driver_license_date" readonly placeholder="Не указано">

                        </div>
                    </div>
                    <div class="row">
                        @foreach ($driver_license_images as $driver_license_image)
                            <div class="col-lg-6">
                                <img src="{{ url('/file?path=' . $driver_license_image) }}" alt=""
                                    class="img-fluid rounded"
                                    onclick="showImageModal('{{ url('/file?path=' . $driver_license_image) }}')">
                            </div>
                        @endforeach
                    </div>
                    <h5 class="font-size-14 mt-4 mb-3"><i class="uil-circle text-primary me-1"></i> Тех пасспорт
                    </h5>
                    <div class="row">
                        @foreach ($ts_passport_images as $ts_passport_image)
                            <div class="col-lg-6">
                                <img src="{{ url('/file?path=' . $ts_passport_image) }}" alt=""
                                    class="img-fluid rounded"
                                    onclick="showImageModal('{{ url('/file?path=' . $ts_passport_image) }}')">
                            </div>
                        @endforeach
                    </div>
                    <h5 class="font-size-14 mt-4 mb-3"><i class="uil-circle text-primary me-1"></i> Действия
                    </h5>
                    <h5 class="font-size-14 mt-4 mb-3"></i> Текущий статус @switch($status)
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
                                <span class="badge bg-secondary">{{ $status }}</span>
                        @endswitch
                    </h5>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border border-danger">
                                <div class="card-header bg-transparent border-danger">
                                    <h5 class="my-0 text-danger font-size-14"><i
                                            class="uil uil-times-circle me-3"></i>Отклонить</h5>
                                </div><!-- end card-header -->
                                <div class="card-body">
                                    <p class="card-text">Данные заполнены неправильно. Отклонить с указанием
                                        причины и дальнейших действий</p>
                                    <label class="form-label" for="reject_message">Причина</label>
                                    @error('reject_message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <input type="text" class="form-control mb-3" id="reject_message"
                                        wire:model="reject_message" placeholder="Не указано">
                                    <button type="submit" class="btn btn-danger">Отправить</button>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div>
                        <div class="col-lg-6">
                            <div class="card border border-success">
                                <div class="card-header bg-transparent border-success">
                                    <h5 class="my-0 text-success font-size-14"><i
                                            class="uil uil-check-circle me-3"></i>Принять</h5>
                                </div><!-- end card-header -->
                                <div class="card-body">
                                    <p class="card-text">Все данные заполнены правильно. Создать профиль водителя</p>
                                    <label class="form-label " for="status">Класс авто</label>
                                    @error('class_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <select class="form-select  mb-3" wire:model="class_id">
                                        <option value="">Выберите класс авто</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" @selected($class_id == $class->id)>
                                                {{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-success"
                                        wire:click="createDriverProfile()">Подтвердить</button>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div>
                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="closeCreateModal()">Закрыть</button>

                </div>
            </div>
        </div>
    </div>
</form>
<div class="modal fade imageModal" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Просмотр изображения</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Car Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>
