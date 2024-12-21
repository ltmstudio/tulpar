<?php

namespace App\Livewire;

use App\Models\TxCarClass;
use App\Models\TxLang;
use App\Models\TxTranslation;
use Livewire\Component;
use Livewire\WithFileUploads;

class TranslationsComponent extends Component
{
    use WithFileUploads;

    public $selectedModules = [];
    public $selectedLanguages = [];
    public $localization_file;


    public function render()
    {
        $langs = TxLang::all();

        return view('livewire.translation.index', ['langs' => $langs])
            ->extends('layouts.master')
            ->section('content');
    }

    public function downloadLocalization()
    {
        $translations = TxTranslation::whereIn('module', $this->selectedModules)
            ->whereIn('tx_lang_id', $this->selectedLanguages)
            ->whereIn('module', $this->selectedModules)
            ->get();
        $grouped_translations = array();

        foreach ($translations as $translation) {
            $lang = $translation->tx_lang_id;
            $module = $translation->module;
            $key = $translation->key;
            $value = $translation->value;
            $grouped_translations[$lang][$module][$key] = $value;
        }

        $result = array();

        // Modules
        // car_classes - TxCarClass::name
        // 
        if (in_array('car_classes', $this->selectedModules)) {
            $carClasses = TxCarClass::get()->pluck('name')->toArray();
            foreach ($this->selectedLanguages as $lang) {
                foreach ($carClasses as $car_class) {
                    $result[$lang]['car_classes'][$car_class] = $grouped_translations[$lang]['car_classes'][$car_class] ?? "";
                }
            }
        }
        

        $jsonLocalizationData = json_encode($result, JSON_UNESCAPED_UNICODE);

        return response()->streamDownload(function () use ($jsonLocalizationData) {
            echo $jsonLocalizationData;
        }, 'localization.json', [
            'Content-Type' => 'application/json; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="localization.json"',
        ]);
    }



    public function uploadLocalization()
    {
        $this->validate([
            'localization_file' => 'required|file|mimes:json',
        ]);

        $file = $this->localization_file->getRealPath();
        $jsonContent = file_get_contents($file);
        $localizationData = json_decode($jsonContent, true);
        $updated = 0;
        foreach ($localizationData as $lang => $modules) {
            foreach ($modules as $module => $translations) {
                foreach ($translations as $key => $value) {
                    if ($value !== '') {
                        TxTranslation::updateOrCreate(
                            [
                                'tx_lang_id' => $lang,
                                'module' => $module,
                                'key' => $key,
                            ],
                            [
                                'value' => $value,
                            ]
                        );
                        $updated++;
                    }
                }
            }
        }
        $this->localization_file = null;
        session()->flash('message', 'Файл локализации загружен. Обновлено записей - ' . $updated . '.');
    }
}
