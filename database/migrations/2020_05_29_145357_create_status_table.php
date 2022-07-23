<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('name')->nullable();
            $table->char('class')->nullable();
            $table->char('label')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        factory(App\Models\Status::class)->create([
            'class' => 'badge-success',
            'name'  => 'released',
            'label' => 'Diffusée',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-warning',
            'name'  => 'in_progress',
            'label' => 'Offre en cours',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-info',
            'name'  => 'draft',
            'label' => 'Brouillon',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-warning',
            'name'  => 'pending',
            'label' => 'En attente de confirmation',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-warning',
            'name'  => 'payment',
            'label' => 'En attente de paiement',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-success',
            'name'  => 'accepted',
            'label' => 'Paiement accepté',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-danger',
            'name'  => 'refused',
            'label' => 'Offre refusée',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-info',
            'name'  => 'shipped',
            'label' => 'Commande expédiée',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-secondary',
            'name'  => 'delivered',
            'label' => 'Livraison confirmée',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-primary',
            'name'  => 'refunded_partially',
            'label' => 'Remboursement partiel',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-primary',
            'name'  => 'refunded',
            'label' => 'Remboursé',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-info',
            'name'  => 'new/origin',
            'label' => "Neuf, d'origine",
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-info',
            'name'  => 'new/compatible',
            'label' => 'Neuf, compatible',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-info',
            'name'  => 'used/origin',
            'label' => 'Occasion, d\'origine',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'badge-info',
            'name'  => 'used/compatible',
            'label' => 'Occasion, compatible',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'gold',
            'name'  => 'matched',
            'label' => 'Un match a été fait',
        ]);
        factory(App\Models\Status::class)->create([
            'class' => 'success',
            'name'  => 'created',
            'label' => 'a été créé(e)',
        ]);factory(App\Models\Status::class)->create([
            'class' => 'success',
            'name'  => 'assigned',
            'label' => 'a été assigné(e)',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status');
    }
}
