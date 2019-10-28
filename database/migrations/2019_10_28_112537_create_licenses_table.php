<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('buyer_id');
            $table->integer('product_id');
            $table->integer('max_majver')->default(1);
            $table->string('serial', 50)->nullable();
            $table->string('ilok_code', 50)->nullable();
            $table->dateTime('date_purchase');
            $table->dateTime('date_activate')->nullable();
            $table->addColumn('tinyInteger', 'type', ['length' => 4])->default(0);
            $table->integer('seats')->default(1);
            $table->integer('support_days')->default(365);
            $table->integer('license_days')->default(365);
            $table->integer('prod_features')->default(0);
            $table->mediumText('notes')->nullable();
            $table->integer('paddle_oid')->nullable();
            $table->integer('paddle_sid')->nullable();
            $table->string('paddle_status', 20)->nullable();
            $table->text('paddle_cancelurl')->nullable();
            $table->text('paddle_updateurl')->nullable();
            $table->date('paddle_next_billdate')->nullable();
            $table->addColumn('tinyInteger', 'paddle_queue_cancel', ['length' => 3])->default(0);
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
        Schema::dropIfExists('licenses');
    }
}
