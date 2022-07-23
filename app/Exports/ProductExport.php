<?php

namespace App\Exports;

use App\Actions\Translations\CreateTranslation;
use App\Models\Status;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    protected $merchant;

    public function __construct($merchant)
    {
        $this->merchant = $merchant;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            '#',
            'Titre de l\'annonce',
            'Pièce',
            'Marque/Modèle',
            'Référence interne',
            'Prix',
            'Stock',
            'Etat',
            'Status',
            'Description',
        ];
    }

    public function map($product): array
    {
        $etats = [
                '12'=> 'Neuf',
                '13' => 'Très bon',
                '14'=> 'Bon',
                '15'=> 'Satisfaisant',
                '16'=> 'A rénover',
            ];

        return [
            $product->id,
            $product->original_name,
            $product->part ? $product->part->translation['name'][CreateTranslation::language()] : $product->suggestion,
            $product->modele->first()->brand->name.' '.$product->modele->first()->name,
            $product->intern,
            $product->price,
            $product->stock,
            $product->condition ? $etats[$product->condition] : '',
            $product->status ? Status::find($product->status)->label : '',
            $product->original_description,
        ];
    }

    public function collection()
    {
        $products = $this->merchant->products()
            ->get();

        return $products;
    }
}
