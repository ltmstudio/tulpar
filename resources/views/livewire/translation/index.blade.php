<div>
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Система
        @endslot
        @slot('title')
            Локализация
        @endslot
    @endcomponent

    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="uil uil-check me-2"></i>
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif


    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6>Скачать файл локализации</h6>
                    <p>Выберите модуль и язык, которые будут включены в файл скачивания</p>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="downloadLocalization">
                        @csrf
                        <div class="form-group">
                            <label for="module">Модуль</label>
                            <div id="module">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="module[]"
                                        value="car_classes" id="car_classes" wire:model="selectedModules">
                                    <label class="form-check-label" for="car_classes">
                                        Классы поездок
                                    </label>
                                </div>
                                {{-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="module[]"
                                        value="task_statuses" id="task_statuses" wire:model="selectedModules">
                                    <label class="form-check-label" for="task_statuses">
                                        Статусы задач
                                    </label>
                                </div> --}}
                                <!-- Add more checkboxes as needed -->
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="language">Язык</label>
                            <div id="language">
                                @foreach ($langs as $lang)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="language[]"
                                            value="{{ $lang->id }}" id="{{ $lang->code }}"
                                            wire:model="selectedLanguages">
                                        <label class="form-check-label" for="{{ $lang->code }}">
                                            {{ $lang->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mt-3">
                            Скачать файл локализации
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6>Загрузить файл локализации</h6>
                    <p>Выберите файл локализации для загрузки</p>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="uploadLocalization">
                        @csrf
                        <div class="form-group">
                            <label for="localization_file">Файл локализации</label>
                            <div class="input-group">
                                <input type="file" wire:model="localization_file" class="form-control"
                                    id="localization_file">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">Загрузить файл локализации</button>
                                </div>
                            </div>
                            @error('localization_file')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
