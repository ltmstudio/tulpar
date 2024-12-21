<form wire:submit.prevent="createItem" class="custom-validation">
    <div class="modal fade" wire:ignore.self id="addItem" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="addItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemLabel">
                        @if ($car_edit_id != '')
                            Изменить автомобиль
                        @else
                            Создать новый автомобиль
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeCreateModal">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="name">Название</label>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="name" wire:model="name"
                                placeholder="Введите название">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="cyrillic_name">Название на кириллице</label>
                            @error('cyrillic_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="cyrillic_name" wire:model="cyrillic_name"
                                placeholder="Введите название на кириллице">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="popular">Популярный</label>
                            @error('popular')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="checkbox" class="form-check-input" id="popular" wire:model="popular">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="country">Страна</label>
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="country" wire:model="country"
                                placeholder="Введите страну">
                        </div>
                        <div class="mb-3 col-lg-6">
                            <label class="form-label" for="image">Изображение</label>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <input type="text" class="form-control" id="image" wire:model="image"
                                placeholder="Введите URL изображения">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                        wire:click="closeCreateModal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
</form>
