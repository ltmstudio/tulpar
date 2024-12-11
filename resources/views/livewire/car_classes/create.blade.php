<form wire:submit.prevent="createItem" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($item_edit_id != '')
                            Изменить класс поездки
                        @else
                            Создать новый класс поездки
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeCreateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="name">Наименование</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="name" wire:model="name"
                                placeholder="Введите наименование">
                            <label class="form-label mt-3" for="cost">Мин. стоимсоть</label>
                            @error('cost')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="input-group">
                                <input type="number" class="form-control" id="cost" wire:model="cost"
                                placeholder="Введите стоимость">
                                <div class="input-group-text">₸</div>
                            </div>
                            <label class="form-label mt-3" for="priority">Приоритет класса</label>
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="number" class="form-control" id="priority" wire:model="priority"
                            placeholder="Введите приоритет">
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label" for="image">Изображение</label>
                                @if ($image_select)
                                    <img src="{{ $image_select->temporaryUrl() }}" alt=""
                                        class="driver-avatar mb-3">
                                @else
                                    @if ($image)
                                        <img src="{{ asset($image) }}" alt="" style="border-radius: 20px"
                                            class="driver-avatar mb-3">
                                    @endif
                                @endif

                            </div>
                            <input type="file" class="form-control" name="image_select" id="image_select"
                                wire:model="image_select" accept="image/png, image/jpeg, image/webp">
                            @error('image_select')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
