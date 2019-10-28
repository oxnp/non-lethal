<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->addColumn('tinyInteger', 'published', ['length' => 4]);
            $table->integer('access')->unsigned()->default(0);
            $table->integer('ordering')->default(0);
            $table->integer('type');
            $table->addColumn('tinyInteger', 'licsystem', ['length' => 4])->default(1);
            $table->string('name', 50);
            $table->string('code', 13);
            $table->string('default_majver', 4)->default(1);
            $table->char('prefix_full', 5)->nullable();
            $table->char('prefix_upgrade', 5)->nullable();
            $table->char('prefix_temp', 5)->nullable();
            $table->text('features')->nullable();
            $table->addColumn('tinyInteger', 'debug_mode', ['length' => 1])->default(0);
            $table->text('feature_prefixes')->nullable();
            $table->addColumn('tinyInteger', 'isbeta', ['length' => 1])->default(0);
            $table->string('mail_address', 255);
            $table->string('mail_from', 255)->nullable();
            $table->string('mail_bcc', 255)->nullable();
            $table->string('mail_subject', 255)->nullable();
            $table->mediumText('mail_body');
            $table->string('upgradeable_products', 255)->nullable();
            $table->string('paddle_pid', 10)->nullable();
            $table->string('paddle_upgrade_pid', 10)->nullable();
            $table->string('notes', 255)->nullable();
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
        Schema::dropIfExists('products');
    }
}
