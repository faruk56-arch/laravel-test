<?php

use App\Models\MessageTemplate;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessageTemplatesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->softDeletes();
            $table->timestamps();
        });
        $template = MessageTemplate::create();
        $template->setTranslationAttribute(
            [
                'fr_FR' =>
                    'Aucune des trouvailles ne conviennent au client. Voici son message : {0}',
                'de_DE' => 'Aucune des trouvailles ne conviennent au client. Voici son message : {0}',
                'en_EN' => 'Aucune des trouvailles ne conviennent au client. Voici son message : {0}',
            ]
        );
        $template->save();
        $template = MessageTemplate::create();
        $template->setTranslationAttribute(
            [
                'fr_FR' =>
                    'Le produit ne correspond pas au client, voici son message : {0}',
                'de_DE' => 'Le produit ne correspond pas au client, voici son message : {0}',
                'en_EN' => 'Le produit ne correspond pas au client, voici son message : {0}',
            ]
        );
        $template->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_templates');
    }
}
