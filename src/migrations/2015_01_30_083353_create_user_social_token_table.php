<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSocialTokenTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social_token', function(Blueprint $table)
        {
            $table->increments('id');

            $table->string('type');
            $table->longText('entity_id');
            $table->string('entity_name')->nullable();

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('short_lived_token');
            $table->text('long_lived_token')->nullable();
            $table->string('expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_social_token', function (Blueprint $table) {
            $table->dropForeign('user_social_token_user_id_foreign');
        });

        Schema::drop('user_social_token');
    }

}
