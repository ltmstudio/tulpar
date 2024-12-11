<form wire:submit.prevent="createItem" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($item_edit_id != '')
                            Изменить грейд
                        @else
                            Создать новый грейд
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
                            <label class="form-label mt-3" for="count">Кол-во смен</label>
                            @error('count')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="input-group">
                                <input type="number" class="form-control" id="count" wire:model="count"
                                    placeholder="Введите количество">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label mt-3" for="color">Цвет значка</label>
                            @error('color')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="color" wire:model="color"
                                placeholder="Введите цвет в формате HEX">

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
