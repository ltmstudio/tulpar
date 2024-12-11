<form wire:submit.prevent="createItem" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($item_edit_id != '')
                            Изменить профиль водителя
                        @else
                            Создать профиль водителя
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeCreateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="card-title mb-3">Общая информация о водителе</h4>
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="name">Имя</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="name" wire:model="name"
                                placeholder="Введите имя">
                            <label class="form-label mt-3" for="lastname">Фамилия</label>
                            @error('lastname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="lastname" wire:model="lastname"
                                placeholder="Введите фамилию">
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label" for="image">Фото водителя</label>
                                @if ($avatar_select)
                                    <img src="{{ $avatar_select->temporaryUrl() }}" alt=""
                                        class="driver-avatar mb-3">
                                @else
                                    @if ($avatar)
                                        <img src="{{ asset($avatar) }}" alt="" style="border-radius: 20px"
                                            class="driver-avatar mb-3">
                                    @endif
                                @endif

                            </div>
                            <input type="file" class="form-control" name="avatar_select" id="avatar_select"
                                wire:model="avatar_select" accept="image/png, image/jpeg, image/webp">
                            @error('avatar_select')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <label class="form-label mt-3" for="phone">Номер телефона</label>
                            <div class="input-group">
                                <div class="input-group-text">+7</div>
                                <input type="text" class="form-control" id="phone" wire:model="phone"
                                    placeholder="Введите номер телефона">
                            </div>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <h4 class="card-title mb-3 mt-5">Информация об автомобиле</h4>
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="car_name">Марка/модель авто</label>
                            <input type="text" class="form-control" id="car_name" wire:model="car_name"
                                placeholder="Заполните поле">
                            @error('car_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <label class="form-label mt-2" for="people">Количество мест</label>
                            <input type="number" class="form-control" id="people" wire:model="people"
                                placeholder="Введите количество мест">
                            @error('people')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="car_number">Гос номер авто</label>
                            <input type="text" class="form-control" id="car_number" wire:model="car_number"
                                placeholder="Введите гос номер авто">
                            @error('car_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <label class="form-label mt-2" for="status">Класс авто</label>
                            @error('class_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <select class="form-select" wire:model="class_id">
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" @selected($class_id == $class->id)>
                                        {{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <h4 class="card-title mb-3 mt-5">Доступ к приложению</h4>
                    <div class="row">
                        <div class="mb-3 col-lg-4">
                            <label class="form-label" for="status">Cтатус</label>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <select class="form-select" wire:model="status">
                                <option value="1" @selected($status == 1)>Активно</option>
                                <option value="0" @selected($status == 0)>Заблокировано</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="closeCreateModal()">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</form>
