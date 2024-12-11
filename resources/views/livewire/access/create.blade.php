<form wire:submit.prevent="createItem" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($item_edit_id != '')
                            Изменить профиль пользователя
                        @else
                            Создать профиль пользователя
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeCreateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="card-title mb-3">Общая информация о пользователе</h4>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="name">Имя</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="name" wire:model="name"
                                placeholder="Введите имя">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-text">@</div>
                                <input type="text" class="form-control" id="email" wire:model="email"
                                    placeholder="Введите адрес эл. почты">
                            </div>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="role">Роль</label>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <select class="form-select" wire:model="role"
                                @if ($item_edit_id == Auth::user()->id) disabled @endif>
                                <option>Выберите роль</option>
                                <option value="ADM" @selected($status == 'ADM')>Администратор</option>
                                <option value="MNG" @selected($status == 'MNG')>Менеджер</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="status">Cтатус</label>
                            @error('status')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <select class="form-select" wire:model="status"
                                @if ($item_edit_id == Auth::user()->id) disabled @endif>
                                <option value="1" @selected($status == 1)>Активно</option>
                                <option value="0" @selected($status == 0)>Заблокировано</option>
                            </select>
                        </div>
                    </div>
                    @if ($item_edit_id == Auth::user()->id)
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <i class="uil uil-exclamation-octagon me-2"></i>
                            Вы не можете изменить роль и статус текущей сессии.
                        </div>
                    @endif
                    @if ($item_edit_id != '' || $item_edit_id == null)
                        <h4 class="card-title mt-5 mb-3">Сменить пароль (необязательно)</h4>
                    @endif
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label class="form-label" for="password">Пароль</label>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="password" class="form-control" id="password" wire:model="password"
                                placeholder="Введите пароль">

                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="password_confirmation">Подтверждение пароля</label>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="password" class="form-control" id="password_confirmation"
                                wire:model="password_confirmation" placeholder="Подтвердите пароль">

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
    </div>
</form>
