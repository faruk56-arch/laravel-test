<?php

use App\Models\Status;
use App\Models\Translation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusTable extends Migration
{

    //    Neuf
    //Très bon
    //Bon
    //Satisfaisant
    //A rénover

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->newStatus('badge-info', [
            'fr_FR' => 'Neuf', 'en_EN' => 'New', 'de_DE' => 'Neu',
        ]);
        $this->newStatus('badge-info', [
            'fr_FR' => 'Très bon', 'en_EN' => 'Very good',
            'de_DE' => 'Sehr gut',
        ]);
        $this->newStatus('badge-info', [
            'fr_FR' => 'Bon', 'en_EN' => 'Good', 'de_DE' => 'Gut',
        ]);
        $this->newStatus('badge-info', [
            'fr_FR' => 'Statisfaisaint', 'en_EN' => 'Satisfactory',
            'de_DE' => 'Zufriedenstellend',
        ]);
        $this->newStatus('badge-info', [
            'fr_FR' => 'A rénover', 'en_EN' => 'To renovate',
            'de_DE' => 'Renovieren',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }

    /**
     * @param  string  $badge
     * @param  array  $translations
     */
    private function newStatus(string $badge, array $translations): void
    {
        $status              = Status::create(['class' => $badge]);
        $status->translation = $translations;
        $status->save();
    }
}
